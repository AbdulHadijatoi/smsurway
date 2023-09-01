<?php

namespace App\Http\Controllers;

use App\Mail\CreditRequestEmail;
use App\Models\AddressBook;
use App\Models\Message;
use App\Models\ResellerLogo;
use App\Models\SendMsg;
use App\Models\SmsReport;
use App\Models\Transactions;
use App\Models\User;
use App\Services\DPOService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;
use App\Services\OneRouteService;
use Throwable;
use Carbon\Carbon;

class DeliveryReportController extends Controller
{
    
    
    public function oneRouteReport(Request $request){
        $payload = $request->json()->all();
     
        // Extract data from the payload
        $event = $payload['event'];

        if($event && $event == "lowUnitBalance"){
            $balance = $payload['units'];
            $getOneRouteBalance = Setting::where('key', 'oneroute_low_balance')->first();
            if($getOneRouteBalance && $balance <= 40000){
                $getOneRouteBalance->value = 1;
                $getOneRouteBalance->save();
            }
        }
        $message = $payload['message'];
        $conversation = $payload['conversation'];

        
        if(!$conversation['externalId']){
            return false;
        }
        // var_dump($payload);
        // return;
        // Find the SendMsg record using the reference value
        $sendMsg = SendMsg::where('msg_id', $conversation['externalId'])->first();

        
        if ($sendMsg) {
            // Insert a new SmsReport record
            SmsReport::create([
                'user_id' => $sendMsg->user_id,
                'msg_id' => $sendMsg->id,
                'destination' => $conversation['to'],
                'status' => $conversation['status'],
            ]);

            Message::updateOrCreate([
                'user_id' => $sendMsg->user_id,
                'send_id' => $sendMsg->id,
                'from' => $sendMsg->from,
                'to' => $sendMsg->to,
                'delivery_status' => $conversation['status'],
                'msg_id' => $sendMsg->msg_id,
                'msg' => $sendMsg->msg,
                'msg_price' => $sendMsg->msg_price,
            ]);

        }

        return response('Callback received and processed.', 200);
    }
}