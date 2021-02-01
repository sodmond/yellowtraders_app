<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMou extends Mailable
{
    use Queueable, SerializesModels;
    public $mouFile;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mouFile)
    {
        $this->mouFile = $mouFile;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.mou')
                ->subject("Your MOU Just Arrived!!")
                ->attachFromStorage($this->mouFile);
    }
}
