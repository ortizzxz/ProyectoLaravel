<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Inscription;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    public function payWithPayPal(Request $request)
    {
        // Verificamos los datos recibidos
        // dd($request->all()); // Verificar los valores que estamos recibiendo

        // Guardar los valores en la sesión antes de redirigir al pago
        session(['evento_id' => $request->evento_id]);
        session(['tipo_inscripcion' => $request->tipo_inscripcion]);

        // Verificar si el usuario ya está inscrito en este evento
        $existingInscription = Inscription::where('user_id', $request->user_id)
            ->where('evento_id', $request->evento_id)
            ->first();
            
        if ($existingInscription) {
            return redirect()->route('dashboard')->with('error', 'Ya estás inscrito en este evento.');
        }
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => number_format((float) $request->total, 2, '.', '')
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
    
                return redirect()->route('dashboard')->with('success', 'Pago realizado con éxito. Inscripción confirmada.');
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
