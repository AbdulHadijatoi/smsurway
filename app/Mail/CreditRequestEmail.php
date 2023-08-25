<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreditRequestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $data;
    public function __construct($user,$data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.creditRequestEmail')->subject("New Credit Request Email on SMSURWAY");
    }
}
