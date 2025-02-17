<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayWithPayPalRequest;
use App\Mail\PaymentBill;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Inscription;
use Mail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    public function payWithPayPal(PayWithPayPalRequest $request)
    {
        $user = auth()->user();
        $evento = Event::findOrFail($request->evento_id);

        // Verificar límites de inscripción
        $conferencias_inscritas = Inscription::where('user_id', $user->id)
            ->whereHas('evento', function ($query) {
                $query->where('tipo', 'conferencia');
            })->count();
    
        $talleres_inscritos = Inscription::where('user_id', $user->id)
            ->whereHas('evento', function ($query) {
                $query->where('tipo', 'taller');
            })->count();
    
        if ($evento->tipo === 'conferencia' && $conferencias_inscritas >= 5) {
            return redirect()->route('dashboard')->with('error', 'Ya has alcanzado el límite de 5 conferencias.');
        }
    
        if ($evento->tipo === 'taller' && $talleres_inscritos >= 4) {
            return redirect()->route('dashboard')->with('error', 'Ya has alcanzado el límite de 4 talleres.');
        }
    
        // Definir el monto según el tipo de inscripción
        $monto = match ($request->tipo_inscripcion) {
            'virtual' => 1.00,
            'presencial' => 2.00,
            'gratuita' => 0.00,
            default => 0.00
        };
    
        // Definir el monto según el tipo de inscripción
        $monto = match ($request->tipo_inscripcion) {
            'virtual' => 1.00,
            'presencial' => 2.00,
            'gratuita' => 0.00,
            default => 0.00
        };

        // Guardar los valores en la sesión
        session([
            'evento_id' => $request->evento_id,
            'tipo_inscripcion' => $request->tipo_inscripcion,
            'monto_pagado' => $monto
        ]);

        // Verificar si el usuario ya está inscrito en este evento
        $existingInscription = Inscription::where('user_id', $user->id)
            ->where('evento_id', $request->evento_id)
            ->first();

        if ($existingInscription) {
            return redirect()->route('dashboard')->with('error', 'Ya estás inscrito en este evento.');
        }

        // inscripcion gratis ? validar el email y registrar directamente
        if ($request->tipo_inscripcion === 'gratuita') {
            if (str_ends_with($user->email, '@ayala.es')) {
                Inscription::create([
                    'user_id' => $user->id,
                    'evento_id' => $request->evento_id,
                    'tipo_inscripcion' => 'gratuita',
                    'monto_pagado' => 0,
                    'estado' => 'confirmado'
                ]);

                return redirect()->route('dashboard')->with('success', 'Inscripción gratuita confirmada.');
            } else {
                return redirect()->route('dashboard')->with('error', 'Solo los usuarios con email @ayala.es pueden acceder gratuitamente.');
            }
        }

        // si el monto es mayor que 0, se procesa el pago con PayPal
        if ($monto > 0) {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "EUR",
                            "value" => number_format($monto, 2, '.', '')
                        ]
                    ]
                ],
                "application_context" => [
                    "cancel_url" => route('payment.cancel'),
                    "return_url" => route('payment.success')
                ]
            ]);

            if (isset($response['id']) && $response['status'] == "CREATED") {
                foreach ($response['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        return redirect()->away($link['href']);
                    }
                }
            }

            return redirect()->route('dashboard')->with('error', 'Error al iniciar el pago.');
        }

        return redirect()->route('dashboard')->with('error', 'Tipo de inscripción no válido.');
    }



    public function paymentSuccess(Request $request)
    {
        // Obtener el valor de 'evento_id' desde la sesión
        $eventoId = session('evento_id');
        $tipoInscripcion = session('tipo_inscripcion');

        // Verificar que el evento ID esté presente
        if (!$eventoId) {
            return redirect()->route('dashboard')->with('error', 'No se pudo recuperar el evento.');
        }

        $userId = auth()->id();

        // Verificar si el evento aún tiene cupo
        $evento = Event::find($eventoId);
        if (!$evento || $evento->cupo_maximo <= 0) {
            return redirect()->route('dashboard')->with('error', 'El evento ya no tiene cupo disponible.');
        }

        // Procesar el pago con PayPal
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $provider->setAccessToken($paypalToken);

        $orderId = $request->query('token');

        if (!$orderId) {
            return redirect()->route('dashboard')->with('error', 'Pago no válido.');
        }

        try {
            // Capturar el pago
            $response = $provider->capturePaymentOrder($orderId);

            if (isset($response['status']) && $response['status'] === 'COMPLETED') {
                // Obtener el monto pagado
                $montoPagado = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];

                // Registrar el pago en la base de datos
                $pago = Payment::create([
                    'user_id' => $userId,
                    'monto' => $montoPagado,
                    'estado' => 'completado',
                    'transaction_id' => $response['id'], // ID de transacción de PayPal
                ]);

                // Registrar la inscripción en la base de datos
                Inscription::create([
                    'user_id' => $userId,
                    'evento_id' => $eventoId,
                    'tipo_inscripcion' => $tipoInscripcion,
                    'monto_pagado' => $montoPagado,
                    'estado' => 'confirmado'
                ]);

                // obtener usuario y evento
                $user = auth()->user();
                $event = Event::find($eventoId);

                Mail::to($user->email)->send(new PaymentBill($pago, $user, $event));
                return redirect()->route('dashboard')->with('success', 'Pago realizado con éxito. Inscripción confirmada. Se ha enviado una factura a su correo electrónico.');
            } else {
                return redirect()->route('dashboard')->with('error', 'El pago no se completó correctamente.');
            }
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }




    public function paymentCancel()
    {
        return redirect()->route('dashboard')->with('error', 'Has cancelado el pago.');
    }
}
