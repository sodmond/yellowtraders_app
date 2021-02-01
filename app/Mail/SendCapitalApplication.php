<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCapitalApplication extends Mailable
{
    use Queueable, SerializesModels;
    public $trader_id;
    public $fullname;
    public $amount;
    public $amount_words;
    public $bankname;
    public $acctnum;
    public $bank_stmt;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($trader_id, $fullname, $amount, $amount_words, $bankname, $acctnum, $bank_stmt)
    {
        $this->trader_id        = $trader_id;
        $this->fullname         = $fullname;
        $this->amount           = $amount;
        $this->amount_words     = $amount_words;
        $this->bankname         = $bankname;
        $this->acctnum          = $acctnum;
        $this->bank_stmt        = $bank_stmt;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.capital_request')
                    ->subject('Capital Request Application')
                    ->attachFromStorage($this->bank_stmt);
    }
}
