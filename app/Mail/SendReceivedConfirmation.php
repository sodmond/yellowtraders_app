<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReceivedConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    public $investment;
    public $inv_type;
    public $newInv;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $investment, $inv_type, array $newInv = [])
    {
        $this->investment   = $investment;
        $this->inv_type     = $inv_type;
        $this->newInv       =   $newInv;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.received_payments')
                    ->replyTo("yellowcare@yellowtraders.org")
                    ->subject("Payment Confirmation");
    }
}
