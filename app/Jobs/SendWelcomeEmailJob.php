<?php

namespace App\Jobs;

use App\Mail\SendEmailWelcome;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Message;
use App\Models\SmsReport;


class SendWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->details as $detail){
            foreach ($detail as $sms){
                $this->model = new SmsReport;
                $status=$this->model->getReport($sms);
                $sms->update([
                    'delivery_status'=> $status,
                ]);
                // Message::create($msg);
                // Message::create([
                //     'user_id' => $msg['user_id'],
                //     'from' => $msg['from'],
                //     'to' => $msg['to'],
                //     'msg' => $msg['msg'],
                //     'msg_id' => $msg['msg_id'],
                //     'msg_type' => $msg['msg_type'],
                //     'msg_price' => $msg['msg_price'],
                //     'sendtime' => $msg['sendtime'],
                //     // 'updated_at' => $msg->updated_at,
                //     // 'created_at' => $msg->created_at,
                
                // ]);
            }
        }
        // Mail::to($this->details['email'])->send(new SendEmailWelcome($this->details['name']));
    }
}
