<?php


namespace App\Http\Controllers;

use App\Mail\LowBalanceNotificationEmail;
use App\Models\SendMsg;
use App\Models\AddressBook;
use App\Models\Transactions;
use App\Models\ManageKeyword;
use App\Models\Compaign;
use App\Models\GsmNetwork;
use App\Models\GsmPrefix;
use App\Models\User;
use App\Services\NanoBoxSMS;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Setting;
use App\Services\OneRouteService;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
class SendSMSController extends Controller
{
    public function sendSMS(Request $request){
        $request->validate([
            'from' => 'required',
            'msg' => 'required',
            'to' => 'required_without:group',
            'group' => 'required_without:to',
        ]);
        // return $request->input();
        $from = $request->from;
        $to = $request->to;
        $group = $request->group;
        $msg = $request->msg;
        $contact = $request->contact;

        $msg=strtolower($request->msg);
        $filter=$this->filter_keyword($msg);

        if(!$filter->isEmpty()){
            $string='';
            foreach($filter as $key){
                $string .=ucfirst("$key").', ';
            }
            return back()->with('error','<b>Prohibited Keywords: </b> '.$string);
        }

        $count=$this->count_msg($msg);

        if($contact=='contact_group'){
            $to= AddressBook::select('numbers')->where('name', $group)->where('user_id', auth()->user()->id)->first()->numbers;
        }

        $msg_price=[];
        $setNmbr=[];
        $to = Str::of($to)->replaceMatches('/[^A-Za-z0-9]++/', ',');
        $user=auth()->user();
        if($user->reseller_id !==null){
            $msg_price=$this->count_credit($to,$count,auth()->user()->reseller_id);
            $credit=$msg_price['credit'];
            $to=$msg_price['send'];
            $msg_price=$msg_price['msg_price'];

            $reseller_debet=$this->count_credit($to,$count,null);

            $reseller_bill=$reseller_debet['credit'];
            $reseller_credit=User::where('id',$user->reseller_id)->first()->credit;
            $debet=$reseller_credit- $reseller_bill;

            if($reseller_credit<$reseller_bill){
                return back()->with('error','Something went wrong, please contact your admin');
            }
            else if($user->credit < $credit){
                return back()->with('error','Insufficient credit.Please recharge your account.');
            }

            User::where('id',$user->reseller_id)->update(['credit'=> $debet]);
        }
        else{
            $msg_price=$this->count_credit($to,$count,$user->reseller_id);
            $credit=$msg_price['credit'];
            $to=$msg_price['send'];
            $msg_price=$msg_price['msg_price'];

            $usercredit=$user->credit;
            if($usercredit<$credit){
                return back()->with('error','Insufficient credit! Please recharge your account.');
            }
        }

        SendMsg::create([
            'from' => $from,
            'to' => $to,
            'msg' => $request->msg,
            'user_id' => auth()->user()->id,
            'msg_type' => 0,
            'msg_count' => $count,
            'msg_price' => $msg_price,
            'sendtime' => now(),
        ]);

        
            

        $response = OneRouteService::sendSMS($request->msg,$to,$from);
        
        if ($response['success']) {
            $user->credit = $user->credit - $credit;
            $user->save();
            
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
            return back()->with(
                'success','SMS Sent Successfully!'
            );
        }else{
            // return $response;
            return back()->with(
                'error','Failed to send sms, please try again later!'
            );
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