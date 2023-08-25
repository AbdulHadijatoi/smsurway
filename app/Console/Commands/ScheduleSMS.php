<?php

namespace App\Console\Commands;

use App\Models\Message;
use App\Models\SendMsg;
use App\Models\SmsReport;
use App\Models\TempMsg;
use Illuminate\Console\Command;

class ScheduleSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $input = SendMsg::where('is_scheduled',0)->get();
        foreach ($input as $value) {
            $response=null;
            $repo = explode(",", $value->to);
            $price = explode(",", $value->msg_price);
            foreach($repo as $index => $key){
                TempMsg::updateOrCreate([
                    'user_id' => $value['user_id'],
                    'send_id' => $value['id'],
                    'from' => $value['from'], 
                    'to' => $key,
                    'msg' => $value['msg'],
                    'msg_type' => $value['msg_type'],
                    'msg_id' => $response,
                    'msg_price' => $price[$index],
                ]);
            }
            $value->update([
                'is_scheduled' => 1,
            ]);
        }
        return Command::SUCCESS;
    }
}
