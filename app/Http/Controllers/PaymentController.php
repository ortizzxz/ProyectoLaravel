<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function procesarPago(Request $request)
    {
        // Aquí va la integración con PayPal

        // Si el pago es exitoso, registramos el pago
        $pago = new Payment();
        $pago->user_id = auth()->user()->id;
        $pago->monto = $request->monto;
        $pago->estado = 'completado';
        $pago->transaction_id = $request->transaction_id;
        $pago->save();

        return redirect()->route('eventos.index')->with('success', 'Pago procesado con éxito.'); 
    }
}
