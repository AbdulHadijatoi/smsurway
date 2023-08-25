<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Message;
use App\Models\SmsReport;
use App\Models\TempMsg;

class GetReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    public $model;
    
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $smsManagement = TempMsg::all()->whereNull('delivery_status')->whereNotNull('msg_id')->take(15);
        foreach ($smsManagement as $sms){
            $this->model = new SmsReport;
            $delivery_status=$this->model->getReport($sms->msg_id);
            dump($delivery_status);
            $status=Message::updateOrCreate([
                'user_id' => $sms['user_id'],
                'send_id' => $sms['send_id'],
                'from' => $sms['from'],
                'to' => $sms['to'],
                'delivery_status' => $delivery_status,
                'msg_id' => $sms['msg_id'],
                'msg' => $sms['msg'],
                'msg_price' => $sms['msg_price'],
            ]);
            dump($delivery_status);
        }
        Log::info("Cron is working fine!");
        return Command::SUCCESS;
    }
}
