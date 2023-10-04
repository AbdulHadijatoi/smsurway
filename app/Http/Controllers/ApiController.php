<?php

namespace App\Http\Controllers;

use App\Models\SendMsg;
use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Setting;
use App\Mail\LowBalanceNotificationEmail;
use App\Models\GsmNetwork;
use App\Models\GsmPrefix;
use App\Models\ManageKeyword;
use App\Services\OneRouteService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ApiController extends Controller
{

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }
 	
 		//Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }
 
    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

		//Request is validated, do logout        
        try {
            JWTAuth::invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
 
    public function get_user(Request $request){
        $this->validate($request, [
            'token' => 'required'
        ]);
        $user = JWTAuth::authenticate($request->token);
        return response()->json(['credit' => $user->credit]);
    }

    public function report(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $product = $user->getreport()->where('msg_id',$request->msgid)->first();
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, report not found.'
            ], 400);
        }
        return response()->json([
            'status' => $product->status
        ]);
    }

    public function credit(){
        $user = JWTAuth::parseToken()->authenticate();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user not found.'
            ], 400);
        }
        return response()->json([
            'credit' => $user->credit
        ]);
        return $user;
    }
    
    public function fetchSenders(Request $request){
        $senderIds = OneRouteService::fetchChannels();
        $channels = [];
        if($senderIds){
            foreach ($senderIds as $sender) {
                $tempChannel = [];
                $tempChannel['id'] = $sender['id'];
                $tempChannel['name'] = $sender['name'];
                $channels[] = $tempChannel;
            }
        }

        if(count($channels) > 0){
            return response()->json([
                'success' => true,
                'senders' => $channels,
            ], Response::HTTP_OK);
        }else{
            return response()->json([
                'success' => false,
                'senders' => [],
            ], 422);
        }
    }

    public function send(Request $request,$sender = null){
        // return $request->all();
        $user = JWTAuth::parseToken()->authenticate();
        $credentials = $request->only('to','msg','token');
        //valid credential
        $validator = Validator::make($credentials, [
            'to' => 'required',
            'msg' => 'required|string|min:1|max:950',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $to = $request->to;
        
        $msg = $request->msg;
        // Demo Code
        $count=$this->count_msg($request->msg);
        $to = Str::of($to)->replaceMatches('/[^A-Za-z0-9]++/', ',');
        $msg_price=$this->count_credit($to,$count);
        $credit=$msg_price['credit'];
        $to=$msg_price['send'];
        $msg_price=$msg_price['msg_price'];
        // $user->credit;
        $usercredit=$user->credit;

        if($usercredit<$credit){
            return response()->json(['error' => $validator->messages()], 403);
        }

        if($sender && $sender == "oneotp"){
            $channelId = "5e30e06f-b1b4-4feb-b974-cf071635254d";
            $channelName = "OneOTP";
        }else if($sender && $sender == "onealert"){
            $channelId = "0ce6cb88-69b7-4c77-913f-64776082a975";
            $channelName = "OneAlert";
        }else if($sender && $sender == "traction"){
            $channelId = "635b6642-0710-42ee-a2e4-b15ac4cd54f5";
            $channelName = "Traction";
        }else{
            return response()->json([
                'success' => false,
                'message' => 'SMS not send. SenderId not found!',
                'response' => null,
            ], Response::HTTP_OK);
        }

        SendMsg::create([
            'from' => $channelName,
            'to' => $to,
            'msg' => $msg,
            'user_id' => $user->id,
            'msg_type' => 0,
            'msg_count' => $count,
            'msg_price' => $msg_price,
            'sendtime' => now(),
        ]);

        $response = OneRouteService::sendSMS($msg,$to,$channelId);
        
        if ($response['success']) {
            $msg_ids = [];
            $user->credit = $user->credit - $credit;
            $user->save();
            if(isset($response['body'])){
                $receipients = explode(',',$to);
                $refs = $response['body'];
                foreach ($refs as $key => $ref) {
                    if($ref['status'] == 'success'){
                        $getMesg = SendMsg::where('to',$receipients[$key])->latest()->first();
                        if($getMesg){
                            $getMesg->msg_id = $ref['response'];
                            $getMesg->save();
                            $msg_ids['recipient'] = $receipients[$key];
                            $msg_ids['msg_id'] = $getMesg->id;
                        }
                    }
                }
            }
            
            $userCredit = $user->credit;
            $low_balance = Setting::where('key', 'low_balance')->first();
            if($low_balance){
                if($userCredit < $low_balance->value && $user->low_balance != 1){
                    //send email for low balance notification
                    Mail::to($user->email)->send(new LowBalanceNotificationEmail(
                        $user->name
                    ));
                    $user->low_balance = 1;
                    $user->save();
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'SMS created successfully',
                'response' => $msg_ids,
            ], Response::HTTP_OK);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'SMS not send. Check credentials and try again',
                'response' => null,
            ], Response::HTTP_OK);
        }

    }


    public function filter_keyword($msg){
        $msg = str_replace('.', ' ', $msg);
        $msg_arr =explode(" ",$msg);
        $key_arr=array();
        $collection1 = collect($msg_arr);
        $keyword=ManageKeyword::get('keyword');
        foreach($keyword as $key){
            $collect= $key->keyword;
            array_push($key_arr,$collect);
        }
        $filter=$collection1->intersect($key_arr);
        return $filter;
    }
    public function count_msg($msg){
        $char=strlen($msg);
        $count=1;
        if ($char > 950) { return back()->with("error","You exceed maximum limited");}
        else if ($char >= 626 && $char <= 950) {  $count=6; }
        else if ($char >= 506 && $char <= 625) { $count=5;  } 
        else if ($char >= 401 && $char <= 505) { $count=4;  } 
        else if ($char >= 291 && $char <= 400) { $count=3;  } 
        else if ($char >= 161 && $char <= 280) { $count=2;  }
        else if ($char >= 1 && $char <= 160)   { $count=1;  }
        else{ $count=1; }
        return $count;
    }
    
    public function count_credit($to,$count,$user_id = null){
        $mtn=[];
        $glo=[];
        $airtel=[];
        $mobile9=[];
        $unknown=[];
        $msg_price=[];
        $mt=0;$gl=0;$ar=0;$m9=0;$unP=0;
        $network=GsmPrefix::all();
        foreach($network as $name)
        {
            
            if($name->network_name=='MTN'){
                array_push($mtn,$name->network_prefix);
            }
            elseif($name->network_name=='Glo'){
                array_push($glo,$name->network_prefix);
            }
            elseif($name->network_name=='Airtel'){
                
                array_push($airtel,$name->network_prefix);
            }
            elseif($name->network_name=='9Mobile'){
                array_push($mobile9,$name->network_prefix);
            }
            else{
                array_push($unknown,'Unknown Network.'); 
            }
        }
        if ($user_id !==null){
            $arPrice=GsmNetwork::where('network_name','AirTel')->where('user_id', $user_id)->first()->network_price;
            $mtPrice=GsmNetwork::where('network_name','MTN')->where('user_id', $user_id)->first()->network_price;
            $glPrice=GsmNetwork::where('network_name','Glo')->where('user_id', $user_id)->first()->network_price;
            $m9Price=GsmNetwork::where('network_name','9Mobile')->where('user_id', $user_id)->first()->network_price;
            $default=GsmNetwork::where('network_name','Default')->where('user_id', $user_id)->first()->network_price;
            // return "Here is if";
        }
        else{
            $user_id = 2;
            $mtPrice=GsmNetwork::where('network_name','MTN')->where('user_id', $user_id)->first()->network_price;
            $glPrice=GsmNetwork::where('network_name','Glo')->where('user_id', $user_id)->first()->network_price;
            $arPrice=GsmNetwork::where('network_name','AirTel')->where('user_id', $user_id)->first()->network_price;
            $m9Price=GsmNetwork::where('network_name','9Mobile')->where('user_id', $user_id)->first()->network_price;
            $default=GsmNetwork::where('network_name','Default')->where('user_id', $user_id)->first()->network_price;
            // return "Here is else";
            // return $user_id;
        }
        $setNmbr=[];
        $to = explode(',', $to);
        $to = array_unique($to);
        foreach($to as $nmbr){
            if(strlen($nmbr)>10){
                $pre=substr($nmbr, 0, 1);
                if($pre==0){
                $nmbr= substr_replace($nmbr, "234", 0, 1);
                }
                array_push($setNmbr,$nmbr);
                $prefix=substr($nmbr, 0, 6);
                if(in_array($prefix, $mtn)){
                    $mt++;
                    // $msg_price=$msg_price*$count;
                array_push($msg_price,$mtPrice*$count);
                }
                elseif(in_array($prefix, $glo)){
                    $gl++;
                    array_push($msg_price,$glPrice*$count);
                }
                elseif(in_array($prefix, $airtel)){
                    $ar++;
                    array_push($msg_price,$arPrice*$count);
                }
                elseif(in_array($prefix, $mobile9)){
                    $m9++;
                    array_push($msg_price,$m9Price*$count);
                }
                else{
                    $unP++;
                    array_push($msg_price,$default*$count);
                }
            }
        }
        
        $to=$setNmbr;
        // return $to;
        $credit=($mt*$mtPrice+$gl*$glPrice+$ar*$arPrice+$m9*$m9Price+$unP*$default)*$count;
        // return $arPrice;
        // return $mt*$mtPrice*$count;
        $to = implode(',', $to);
        $msg_price = implode(',', $msg_price);
        $response=[];
        $response['send']=$to;
        $response['msg_price']=$msg_price;
        $response['credit']=$credit;
        return $response;
    }
}
