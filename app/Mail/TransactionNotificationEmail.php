<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;

class TransactionNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $transactionType;
    public $totalPaid;
    public $vatValue;
    public $vat;
    public $credit;

        /**
     * Create a new message instance.
     *
     * @param  string  $userName
     * @param  string  $transactionType
     * @param  float   $totalPaid
     * @param  float   $vatValue
     * @param  float   $vat
     * @param  float   $transactionFeeValue
     * @param  float   $transactionFee
     * @param  float  $credit
     * @return void
     */
    public function __construct($userName, $transactionType, $totalPaid, $vatValue, $vat, $credit)
    {
        $this->userName = $userName;
        $this->transactionType = $transactionType;
        $this->totalPaid = $totalPaid;
        $this->vatValue = $vatValue;
        $this->vat = $vat;
        $this->credit = $credit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.transaction_notification')
            ->subject('Transaction Notification')
            ->with([
                'userName' => $this->userName,
                'transactionType' => $this->transactionType,
                'totalPaid' => $this->totalPaid,
                'vat' => $this->vat,
                'vatValue' => $this->vatValue,
                'credit' => $this->credit
            ]);
    }
}
