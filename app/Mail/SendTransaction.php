<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTransaction extends Mailable
{
    use Queueable, SerializesModels;
    public $amount;
    public $trans_id;
    public $trader_id;
    public $inv_type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($trader_id, $amount, $trans_id, $inv_type)
    {
        $this->trader_id = $trader_id;
        $this->amount = $amount;
        $this->trans_id = 'trans#'.str_pad($trans_id, 9, '0', STR_PAD_LEFT);
        $this->inv_type = $inv_type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.transaction')
                    ->replyTo("yellowcare@yellowtraders.org")
                    ->subject("Your Transaction Details");
    }
}
