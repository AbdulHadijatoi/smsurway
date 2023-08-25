<?php

namespace App\Console\Commands;

use App\Models\TempMsg;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SendSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $smsManagement = TempMsg::whereNull('msg_id')->take(20)->get();
        foreach ($smsManagement as $sms){
            $response=$this->model->send_curl($sms->from,$sms->to,$sms->msg);
            $collection = Str::of($response)->afterLast(',');
            $status=$sms->update([
                'msg_id'=> $collection,
            ]);
        }
        // foreach ($smsManagement as $sms){
            // $sms->delete();
            // $report= SmsReport::where("msg_id", $sms->msg_id)->where("destination", $sms->to)->first();
            // $response=$this->model->send_curl($sms->from,$sms->to,$sms->msg);
            // $collection = explode("~", $nmbr['msg_id']);
            //         $sms_id=[];
            //         foreach ($collection as $msg_id) {
            //             // $slice = Str::of($msg_id)->beforeLast(',');
            //             // $collection = Str::of($msg_id)->explode(',');
            //             $id = Str::of($msg_id)->after(',');
            //             $chk=Str::of($id)->isUuid();
            //             if($chk)
            //             $sms_id[]=$id;
            //         }
        //     $sms->update([
        //         'msg_id'=> $response,
        //     ]);
        // }
        Log::info("Cron is working fine!");
        return Command::SUCCESS;
    }
}
