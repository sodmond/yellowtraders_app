<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendApplication extends Mailable
{
    use Queueable, SerializesModels;
    public $profile;
    public $bank;
    public $inv;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct (array $profile, array $bank, array $inv)
    {
        $this->profile = $profile;
        $this->bank = $bank;
        $this->inv = $inv;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.application')
                    ->replyTo("yellowcare@yellowtraders.org")
                    ->subject("Your Application Info");
    }
}
