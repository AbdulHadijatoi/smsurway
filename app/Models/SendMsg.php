<?php

namespace App\Models;

use App\Services\NanoBoxSMS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class SendMsg extends Model
{
    use HasFactory;
    // `from`, `to`, `msg`, `msg_type`, `msg_id`, `limit`, `user_id`, `sendtime`,`msg_count`
    protected $fillable = [
        'to',
        'from',
        'msg',
        'msg_type',
        'msg_price',
        'msg_id',
        'limit',
        'user_id',
        'sendtime',
        'msg_count',
        'is_scheduled',
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function compaign($request,$user){
        // $username=$username=@get_setting('username')->value;
        // $userpass=$username=@get_setting('password')->value;
        $price = explode(',', $request->msg_price);
        array_sum($price);
        $credit=array_sum($price);
        $usercredit=$user->credit;
        $user->credit=$usercredit-$credit;
        

        $response = NanoBoxSMS::sendSMS($request->msg,$request->to,$request->from);
        $jsonResponse = json_decode($response, true);
        if($jsonResponse && isset($jsonResponse['status']) && $jsonResponse['status'] === true){
            $user->save();
            $request->msg_id=$response;
            $status = SendMsg::create([
                'from' => $request->from,
                'to' => $request->to,
                'msg' => $request->msg,
                'user_id' => $request->user_id,
                'msg_type' => $request->type,
                'msg_id' => $request->msg_id,
                'msg_price' => $request->msg_price,
                'sendtime' => $request->sendtime,
            ]);
    
            if($status){
                return 'SMS  Sent/Saved Successfully';
            }
            else{
                return 'Unexpacted error Try again';
            }
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
    public function send_curl($from,$to,$msg){
        $response = NanoBoxSMS::sendSMS($msg,$to,$from);
        // return "From".$from."To".$to."Msg".$msg;
        // $username=@get_setting('username')->value;
        // $userpass=@get_setting('password')->value;
        // // return $username." Pass".$userpass;
        // $url = 'https://sms.hollatags.com/api/send/'; //this is the url of the gateway's interface
        // $ch = curl_init(); //initialize curl handle
        // curl_setopt($ch, CURLOPT_URL, $url); //set the url
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(["user"=>$username,"pass"=>$userpass,"from"=>$from,"to"=>$to,"msg"=>$msg,"enable_msg_id"=>true])); //set the POST variables
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return as a variable
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        // curl_setopt($ch, CURLOPT_POST, 1); //set POST method
        // $response = curl_exec($ch); // grab URL and pass it to the browser. Run the whole process and return the response
        // curl_close($ch);
        return $response;
    }
}