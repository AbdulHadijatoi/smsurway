<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;

class LowBalanceNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;

        /**
     * Create a new message instance.
     *
     * @param  string  $userName
     * @return void
     */
    public function __construct($userName)
    {
        $this->userName = $userName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.low_balance_notification')
            ->subject('Urgent! Low SMS Balance Notification')
            ->with([
                'userName' => $this->userName,
            ]);
    }
}
