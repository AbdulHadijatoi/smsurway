<?php

namespace App\Console\Commands;

use App\Models\Compaign;
use App\Models\SendMsg;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Notifications\cronSmsNotify;
use Illuminate\Support\Facades\Notification;


class cronSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compaign:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
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
        $currentDate = Carbon::now()->format('Y-m-d H:i:00');
        $smsManagement = Compaign::where('sendtime','<=',$currentDate)->get();
        foreach ($smsManagement as $sms){
            $user = User::find($sms->user_id);
            $msg_model = new SendMsg();
            $msg_model->compaign($sms);
            $sms->delete();
           Notification::send($user, new cronSmsNotify());
        }
      return 0;
    }
}
