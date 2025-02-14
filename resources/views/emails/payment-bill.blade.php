<h1>Comprobante de Pago</h1>

<p>Querido {{ $user->name }},</p>

<p>Muchas gracias por su pago, aquí tiene los detalles:</p>

<ul>
    <li>Evento: {{ $event->name }}</li>
    <li>Costo del Precio: €{{ $payment->monto }}</li>
    <li>ID de Transaccion: {{ $payment->transaction_id }}</li>
    <li>Fecha: {{ $payment->created_at->format('Y-m-d H:i:s') }}</li>
</ul>

<p>¡Gracias por su participación!</p>
