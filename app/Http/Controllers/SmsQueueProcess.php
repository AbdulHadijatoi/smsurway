<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\SendMsg;
use App\Models\SmsReport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Jobs\SendWelcomeEmailJob;
use App\Models\Compaign;
use App\Models\TempMsg;
use App\Models\User;
use Illuminate\Support\Str;
use App\Notifications\cronSmsNotify;
use Illuminate\Support\Facades\Notification;

class SmsQueueProcess extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $SendMsg;
    public $getReport;
    public function __construct()
    {
        $this->SendMsg = new SendMsg;
        $this->getReport = new SmsReport;
    }
    public function refreshreport()
    {
        $msg = Message::whereNull('delivery_status')->orWhere('delivery_status','')->orWhere('delivery_status','PROCESSED')->whereNotNull('msg_id')->get();
        dd($msg->count());
        foreach ($msg as $sms){
            // dd($sms);
            TempMsg::updateOrCreate([
                'user_id' => $sms['user_id'],
                'send_id' => $sms['send_id'],
                'msg' => $sms['msg'],
                'from' => $sms['from'],
                'to' => $sms['to'],
                'msg_id' => $sms['msg_id'],
                'msg_price' => $sms['msg_price']
            ]);
        }
        dd("Temp Msg Done!");
    }
    public function updatereport(){
        // $smsManagement = TempMsg::all('msg_id')->whereNull('delivery_status')->whereNotNull('msg_id')->take(15);
        $smsManagement = TempMsg::all('msg_id')->whereNull('delivery_status')->whereNotNull('msg_id');
        dd($smsManagement);
        foreach ($smsManagement as $sms){
            // dd($sms);
            $this->model = new SmsReport;
            $delivery_status=$this->model->getReport($sms->msg_id);
            // dd($delivery_status);
            $msg_exist=Message::where('msg_id',$sms->msg_id)->count();
            // $msg_exist=Message::where('msg_id','8f4c52d8-bcd1-4827-ae9b-33fd9585006r')->count();
            // dd($msg_exist);
            if($msg_exist>0){
                Message::where('msg_id',$sms->msg_id)->update(['delivery_status'=>$delivery_status]);
                // $msg_exist->delivery_status = $delivery_status;
                // $msg_exist->save();
                // dd($msg_exist.' and delivery report is '.$delivery_status);
            }else{
                Message::updateOrCreate([
                    'delivery_status' => $delivery_status,
                    'user_id' => $sms['user_id'],
                    'send_id' => $sms['send_id'],
                    'from' => $sms['from'],
                    'to' => $sms['to'],
                    'msg_id' => $sms['msg_id'],
                    'msg' => $sms['msg'],
                    'msg_price' => $sms['msg_price']
                ]);
                dd($delivery_status);
            }
        }
        // dd('Msg Table updated successfully.');
    }
    public function index()
    {
        $smsManagement = TempMsg::whereNull('msg_id')->take(20)->get();
        foreach ($smsManagement as $sms){
            $response=$this->model->send_curl($sms->from,$sms->to,$sms->msg);
            $jsonResponse = json_decode($response, true);
            if($jsonResponse && isset($jsonResponse['status'])){
                if ($jsonResponse['status'] === true) {
                    $status=$sms->update([
                        'msg_id'=> $jsonResponse['data']['smsReference'],
                    ]);
                }
            }
            // $collection = Str::of($response)->afterLast(',');
            // $status=$sms->update([
            //     'msg_id'=> $collection,
            // ]);
        }
        dd($response." and ".$collection." status".$status);

        // $smsManagement = TempMsg::all()->whereNull('delivery_status')->whereNotNull('msg_id')->take(5);
        // // dd($smsManagement->count());
        // foreach ($smsManagement as $sms){
        //     // dd($sms);
        //     $this->model = new SmsReport;
        //     $delivery_status=$this->model->getReport($sms->msg_id);
        //     // dump($delivery_status);
        //     // $sms->update([
        //     //     'delivery_status'=> $status,
        //     // ]);
        //     $status=Message::updateOrCreate([
        //         'user_id' => $sms['user_id'],
        //         'send_id' => $sms['send_id'],
        //         'msg_type' => $sms['msg_type'],
        //         'from' => $sms['from'],
        //         'to' => $sms['to'],
        //         'delivery_status' => $delivery_status,
        //         'msg_id' => $sms['msg_id'],
        //         'msg' => $sms['msg'],
        //         'msg_price' => $sms['msg_price'],
        //         'msg_count' => $sms['msg_count'],
        //         'sendtime' => $sms['sendtime'],
        //     ]);
        //     // if($delivery_status!="Processed"){
        //     //     $sms->delete();
        //     // }
        //     // dump($delivery_status);
        //     // dd($status);
        // }
        // dump($delivery_status);
        dd("Report Done!");
        $smsManagement = TempMsg::all('msg_id','id')->where('delivery_status','!=Processed')->whereNotNull('delivery_status')->whereNotNull('msg_id');
        foreach ($smsManagement as $sms){
            $status=$this->getReport->getReport($sms->msg_id);
            $sms->update([
                'delivery_status'=> $status,
            ]);
            // dispatch(new SendWelcomeEmailJob($sms));
        }








        $smsManagement = TempMsg::where('to','2349039000021')->get(['to','from','msg','msg_id','id']);
        foreach ($smsManagement as $sms){
            dd($sms);
            $response=$this->SendMsg->send_curl($sms->from,$sms->to,$sms->msg);
            $jsonResponse = json_decode($response, true);
            if($jsonResponse && isset($jsonResponse['status'])){
                if ($jsonResponse['status'] === true) {
                    $status=$sms->update([
                        'msg_id'=> $jsonResponse['data']['smsReference'],
                    ]);
                }
            }
            // $collection = Str::of($response)->afterLast(',');
            // $status=$sms->update([
            //     'msg_id'=> $collection,
            // ]);

            // $sms->delete();
            // $report= SmsReport::where("msg_id", $sms->msg_id)->where("destination", $sms->to)->first();
            
            // $response =$sms->msg_id;
            // $slice = Str::of($response)->beforeLast(',');
            // $collection = explode("~", $response);
            //         $sms_id=[];
            //         foreach ($collection as $msg_id) {
            //             // $slice = Str::of($msg_id)->beforeLast(',');
            //             // $collection = Str::of($msg_id)->explode(',');
            //             $id = Str::of($msg_id)->after(',');
            //             $chk=Str::of($id)->isUuid();
            //             if($chk)
            //             $sms_id[]=$id;
            //         }
           
            
            // dump($sms->id);
            // dump($collection);
            // dump($status);
            // dd($response);
        }


        // dd($slice);

















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
                    'limit' => $value['limit'],
                    'msg_count' => $value['msg_count'],
                    'msg_price' => $price[$index],
                    'msg_type' => $value['id'],
                    'sendtime' => $value['sendtime'],
                ]);
            }
            $value->update([
                'is_scheduled' => 1,
            ]);
            
        }
        dd("Processing Done");


















        // $data = SmsReport::get();
        // dd($input);
        // SELECT `id`, `user_id`, `from`, `to`, `delivery_status`, `msg`, `msg_type`, `msg_id`, `limit`, 
        // `msg_price`, `msg_count`, `sendtime`, `created_at`, `updated_at` FROM `messages` WHERE 1
        
        // SELECT `id`, `user_id`, `msg_id`, `destination`, `units`, `status`, `send_id`, `created_at`, 
        // `updated_at` FROM `sms_reports` WHERE 1
        

            // dd($value->toArray());

            // Temp Hide
            // $qry = SendMsg::where('id', $value['msg_id'])->first(['from','to', 'msg', 'msg_type','limit','sendtime']);
            // $data=$data->toArray();
            // dd($data['to']);
            // dd($repo);
            // $posting_date = $posting_date->format('Y-m-d');
            // $to=collection($data['to']);
            // foreach ($data['to'] as $key => $value)
            $insert_data = [];
            $input = SendMsg::get();
            foreach ($input as $value) {
                $repo = explode(",", $value->to);
                $price = explode(",", $value->msg_price);
                foreach($repo as $index => $key){
                    $data = [
                        'user_id' => $value['user_id'],
                        'id' => $value['id'],
                        'from' => $value['from'], 
                        'to' => $key,
                        'msg' => $value['msg'],
                        'msg_type' => $value['msg_type'],
                        'msg_count' => $value['msg_count'],
                        'msg_price' => $price[$index],
                        'msg_type' => $value['id'],
                        'sendtime' => $value['sendtime'],
                    ];
                    $insert_data[] = $data;
                }
            }
        // dd($insert_data);
        // $insert_data = collect($insert_data)->count();
        // $insert_data = collect($insert_data);
        // dd($insert_data);
        // $chunks = array_chunk($insert_data,20);
        
        // dd($chunks);
        // dispatch(new SendWelcomeEmailJob($chunks));
        // $model="";
        // $smsArr=[];
        //     $smsManagement = Message::whereNull('msg_id');
        // $smsManagement = Message::all('msg_id','id')->whereNull('delivery_status')->whereNotNull('msg_id');
        // dd($smsManagement->count());
        //     $smsManagement = Message::all('msg_id','id')->whereNull('delivery_status')->whereNotNull('msg_id')->take(10);
        //     foreach ($smsManagement as $sms){
            //         $this->model = new SmsReport;
            //         $status=$this->model->getReport($sms);
            //         $sms->update([
                //             'delivery_status'=> $status,
                //         ]);
                //         $smsArr[]=$status;
                //     }
                // dd($smsArr);
                // dd($key+1);
                // $delivery_status = SmsReport::select('status')->where('msg_id',$msg['msg_type'])->where('destination',$msg['to'])->first()->status;
                // $delivery_status= SmsReport::select('status')->where('msg_id',$msg['msg_type'])->where('destination',$msg['to'])->first();
                // $delivery_status= SmsReport::select('status','send_id')->where('msg_id',$msg['msg_type'])->where('destination',$msg['to'])->first();
                // $delivery_status= SmsReport::where('msg_id',$msg['msg_type'])->where('destination',$msg['to'])->pluck('status')->first();
                // $delivery_status= SmsReport::all('status','send_id')->where('msg_id',$msg['msg_type'])->where('destination',$msg['to'])->first();
                
                // $status=$this->model->getReport($msg);
                // $this->validate($msg, [
                    //     'msg_id' => 'required | unique:messages'
                    // ]);
                    // dd($msg);
                    // dump($key+1);
                    // $status=$this->model->getReport($sms);
                    
                    // Message::create($msg);
            $chunks = $insert_data;
            foreach ($chunks as $key=> $msg){
                $send_id= SmsReport::where('msg_id',$msg['msg_type'])->where('destination',$msg['to'])->pluck('send_id')->first();
                
                Message::updateOrCreate([
                    'user_id' => $msg['user_id'],
                    // 'send_id' => $msg['id'],
                    'msg_type' => $msg['msg_type'],
                    'from' => $msg['from'],
                    'to' => $msg['to'],
                    // 'delivery_status' => $delivery_status,
                    'msg_id' => $send_id,
                    'msg' => $msg['msg'],
                    'msg_price' => $msg['msg_price'],
                    'msg_count' => $msg['msg_count'],
                    'sendtime' => $msg['sendtime'],
                    // 'updated_at' => $msg->updated_at,
                    // 'created_at' => $msg->created_at,
                
                ]);
                // dd($msg);
            }
        return "This is queue.";
        
    }
    public function queueJob() {
        // $details['name'] = 'Md Obydullah';
        // $details['email'] = 'hi@obydul.me';
    
        // foreach ($data as $value) {
            // $posting_date = Carbon::parse($value['Posting_Date']);
            // $qry = SendMsg::where('id', $value['msg_id'])->first(['from','to', 'msg', 'msg_type','limit','sendtime']);
            $data = SendMsg::where('msg_id','<','49')->get()->take(70);
            // $data= $data->toArray();
            // dd($data->toArray());
            
            // dd($data);
            // $data=$data->toArray();
            // dd($data['to']);
            // $posting_date = $posting_date->format('Y-m-d');
            // $to=collection($data['to']);
            // $strlnt="2348094947473,2205dd62-4bc6-48a4-970a-7b6582d031cd";
            // dd(strlen($strlnt));
            // dd(count($data));
        $insert_data = [];
            foreach($data as $item => $nmbr){
                // dd($nmbr['msg_id']);
                $qry=[];
                if($nmbr['msg_id']!=null && strlen($nmbr['msg_id'])>49){
                    // dd(count($data));

                    $to = Str::of($nmbr['to'])->replaceMatches('/[^A-Za-z0-9]++/', ',');
                    // dd($to);
                    $repo = explode(",", $to);
                    $price = explode(",", $nmbr['msg_price']);
                    // $sms_id = explode("~", $sms_id);
                    $collection = explode("~", $nmbr['msg_id']);
                    $sms_id=[];
                    foreach ($collection as $msg_id) {
                        // $slice = Str::of($msg_id)->beforeLast(',');
                        // $collection = Str::of($msg_id)->explode(',');
                        $id = Str::of($msg_id)->after(',');
                        $chk=Str::of($id)->isUuid();
                        if($chk)
                        $sms_id[]=$id;
                    }
                    // dd($collection);
                    // dump($sms_id);

                    foreach($repo as $index => $sms){
                        // dd($sms_id[$index]);
                        // $chk=Str::of($sms_id[$index])->isUuid();
                        // if($chk){
                            $qry = [
                                'user_id' => $nmbr['user_id'],
                                'from' => $nmbr['from'], 
                                'to' => $sms,
                                'msg' => $nmbr['msg'],
                                'msg_type' => $nmbr['msg_type'],
                                // 'delivery_status' => $data['status'],
                                'msg_price' => $price[$index],
                                'msg_id' => $sms_id[$index],
                                'sendtime' => $nmbr['sendtime'],
                                // 'updated_at' => $data['updated_at'],
                                // 'created_at' => $data['created_at'],
                            ];
                        // }
                        // dump($qry);

                        $insert_data[] = $qry;
                    }
                }
            }
            
        // }
        // dd($insert_data);
    
        $details = array_chunk($insert_data,100);
        // dd($details);
        dispatch(new SendWelcomeEmailJob($details));
    
        dd('stored');
    }





    public function scheduleSms(){
        $queue = SendMsg::where('is_scheduled',0)->count();
        if($queue>0){
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
                            'limit' => $value['limit'],
                            'msg_count' => $value['msg_count'],
                            'msg_price' => $price[$index],
                            'msg_type' => $value['id'],
                            'sendtime' => $value['sendtime'],
                        ]);
                    }
                    $value->update([
                        'is_scheduled' => 1,
                    ]);
                }
        }
        return "SUCCESS";
    }
    public function sendSms(){
        $smsManagement = TempMsg::whereNull('msg_id')->take(300)->get();
        foreach ($smsManagement as $sms){
            $response=$this->SendMsg->send_curl($sms->from,$sms->to,$sms->msg);
            $jsonResponse = json_decode($response, true);
            if($jsonResponse && isset($jsonResponse['status'])){
                if ($jsonResponse['status'] === true) {
                    $status=$sms->update([
                        'msg_id'=> $jsonResponse['data']['smsReference'],
                    ]);
                }
            }
            // $collection = Str::of($response)->afterLast(',');
            // $status=$sms->update([
            //     'msg_id'=> $collection,
            // ]);
        }
        return "SUCCESS";
    }
    public function getReport(){
        $smsManagement = TempMsg::all()->whereNull('delivery_status')->whereNotNull('msg_id')->sortDesc()->take(200);
        // $smsManagement = TempMsg::all()->whereNull('delivery_status')->whereNotNull('msg_id');
        // dd($smsManagement->count());
        foreach ($smsManagement as $sms){
            $delivery_status=$this->getReport->getReport($sms->msg_id);
            // $checkMsg = Message::where('msg_id', $sms->msg_id)->where('send_id', $sms->send_id)->first();
            // if($checkMsg !=null){
                //     $checkMsg->delivery_status=$delivery_status;
                //     $checkMsg->save();
                // }
            dump($delivery_status);
            $checkMsg = Message::where('msg_id',$sms->msg_id)->count();
            if($checkMsg>0){
                Message::where('msg_id',$sms->msg_id)->update(['delivery_status'=>$delivery_status]);
            }
            else{
                Message::updateOrCreate([
                    'user_id' => $sms['user_id'],
                    'send_id' => $sms['send_id'],
                    'from' => $sms['from'],
                    'to' => $sms['to'],
                    'delivery_status' => $delivery_status,
                    'msg_id' => $sms['msg_id'],
                    'msg' => $sms['msg'],
                    'msg_price' => $sms['msg_price'],
                ]);
            }
            if($delivery_status!="PROCESSED" || $delivery_status!=""){
                $sms->delete();
            }
        }
        return "SUCCESS";
    }
    
    public function compaignSms(){
        $currentDate = Carbon::now()->format('Y-m-d H:i:00');
        $queue = Compaign::where('sendtime','<=',$currentDate)->where('is_scheduled',0)->count();
        // dd($currentDate);
        if($queue>0){
            $smsManagement = Compaign::where('sendtime','<=',$currentDate)->where('is_scheduled',0)->first();
            // $smsManagement2 = Compaign::where('sendtime','<=',$currentDate)->where('is_scheduled',0)->get();
            $user= User::where('id',$smsManagement->user_id)->first();
            $to= $smsManagement->to;
            $from= $smsManagement->from;
            $msg= $smsManagement->msg;
            $type= $smsManagement->type;
            $count= $smsManagement->msg_count;
            // $msg_price1=$this->SendMsg->count_credit($to,$count,$user->reseller_id);
            // $credit=$msg_price1['credit'];
            // $user->credit=$user->credit - $credit;
            // $user->save();
            
            SendMsg::create([
                'from' => $from,
                'to' => $to,
                'msg' => $msg,
                'user_id' => $user->id,
                'msg_type' => $type,
                'msg_count' => $count,
                'msg_price' => $smsManagement->msg_price
            ]);
            $status=Compaign::where('id',$smsManagement->id)->update([
                'is_scheduled' => 1
            ]);
            // foreach ($smsManagement2 as $value) {
                //     // dd($value);
                //     $response=null;
                //     $repo = explode(",", $value->to);
            //     $price = explode(",", $value->msg_price);
            //     foreach($repo as $index => $key){
            //         TempMsg::updateOrCreate([
            //             'user_id' => $value['user_id'],
            //             'send_id' => $value['id'],
            //             'from' => $value['from'], 
            //             'to' => $key,
            //             'msg' => $value['msg'],
            //             'msg_type' => $value['msg_type'],
            //             'msg_id' => $response,
            //             'limit' => $value['limit'],
            //             'msg_count' => $value['msg_count'],
            //             'msg_price' => $price[$index],
            //             'msg_type' => $value['id'],
            //             'sendtime' => $value['sendtime'],
            //         ]);
            //     }
            //     $value->update([
            //         'is_scheduled' => 1,
            //     ]);
            // }
            
            // Notification::send($user, new cronSmsNotify());
        }
        // dd($smsManagement);
        return "SUCCESS";
    }
    
    
}