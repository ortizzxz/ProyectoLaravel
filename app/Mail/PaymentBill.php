<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentBill extends Mailable
{       
    use Queueable, SerializesModels;

    public $payment;
    public $user;
    public $event;

    public function __construct($payment, $user, $event)
    {
        $this->payment = $payment;
        $this->user = $user;
        $this->event = $event;
    }

    public function build()
    {
        return $this->view('emails.payment-bill')
                    ->subject('Comprobante de pago. Jornada Junta AN.');
    }
}
