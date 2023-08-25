<?php


namespace App\Http\Controllers;

use App\Mail\LowBalanceNotificationEmail;
use App\Models\SendMsg;
use App\Models\AddressBook;
use App\Models\Transactions;
use App\Models\ManageKeyword;
use App\Models\Compaign;
use App\Models\User;
use App\Services\NanoBoxSMS;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
class HollaTag extends Controller
{
    public $model;
    public function __construct()
    {
        $this->model = new SendMsg;
    }
    public function sendMsg(Request $request){
        // Core PHP cURL for Send Request.
        $request->validate([
            'from' => 'required|max:11',
            'msg' => 'required',
            'to' => 'required_without:group',
            'group' => 'required_without:to',
        ]);
        $from=$_POST["from"];
        $to=$_POST["to"];
        $group=$_POST["group"];
        $msg=$_POST["msg"];
        $contact=$_POST["contact"];
        // $date1=Carbon::parse($request->sendtime);
        $msg=strtolower($request->msg);
        $filter=$this->model->filter_keyword($msg);
        if(!$filter->isEmpty()){
            $string='';
            foreach($filter as $key){
                $string .=ucfirst("$key").', ';
            }
            return back()->with('error','<b>Prohibited Keywords: </b> '.$string);
        }
        $count=$this->model->count_msg($msg);
        if($contact=='contact_group'){
            $to= AddressBook::select('numbers')->where('name', $group)->where('user_id', auth()->user()->id)->first()->numbers;
        }
        $msg_price=[];
        $setNmbr=[];
        $to = Str::of($to)->replaceMatches('/[^A-Za-z0-9]++/', ',');
        $user=auth()->user();
        if($user->reseller_id !==null){
            $msg_price=$this->model->count_credit($to,$count,auth()->user()->reseller_id);
            $credit=$msg_price['credit'];
            $to=$msg_price['send'];
            $msg_price=$msg_price['msg_price'];
            // dd($msg_price);
            $reseller_debet=$this->model->count_credit($to,$count,null);
            // dd($reseller_debet);
            $reseller_bill=$reseller_debet['credit'];
            $reseller_credit=User::where('id',$user->reseller_id)->first()->credit;
            $debet=$reseller_credit- $reseller_bill;
            // dd($debet);
            if($reseller_credit<$reseller_bill){
                // dd("Insufficient balance of reseller");
                return back()->with('error','Something went wrong, please contact your admin');

            }
            else if($user->credit < $credit){
                // dd("Insufficient balance of user");
                return back()->with('error','Insufficient credit.Please recharge your account.');

            }
            // else{
            //     dd("Msg sent");
            // }
            // dd("Reseller bill ".$reseller_bill. " and Reseller Credit ".$reseller_credit. " User Balance is ". auth()->user()->credit . " and User Debet is ". $msg_price['credit']);
            $reseller_credit_update=User::where('id',$user->reseller_id)->update(['credit'=> $debet]);
            // dd($reseller_credit_update);
        }
        else{
            $msg_price=$this->model->count_credit($to,$count,$user->reseller_id);
            $credit=$msg_price['credit'];
            $to=$msg_price['send'];
            $msg_price=$msg_price['msg_price'];
            // return $to;
            // dd($msg_price);
            $usercredit=$user->credit;
            if($usercredit<$credit){
                return back()->with('error','Insufficient credit! Please recharge your account.');
            }
        }
        // dd($user->reseller_id);
        
        // if($date1>now())
        // {
        //     // dd("Date is greater then now ".$date1);
        //     $status = Compaign::create([
        //         'from' => $from,
        //         'to' => $to,
        //         'msg' => $request->msg,
        //         'user_id' => auth()->user()->id,
        //         'msg_type' => $request->type,
        //         'msg_price' => $msg_price,
        //         'sendtime' => $request->sendtime,
        //     ]);
        //     if($status){
        //         return back()->with(
        //             'success','SMS Compaign has been Scheduled Successfully.'
        //         );
        //     }    
        // }
        // else
        // {
            // dd($credit);
            // dd("Date is less then now ".$date1);
            // Save msg for schedule queue
            // dd($to);
            
            // $phoneNumbers = explode(',', $to);
            // return $phoneNumbers;
            // foreach ($phoneNumbers as $phone) {
            $status = SendMsg::create([
                'from' => $from,
                'to' => $to,
                'msg' => $request->msg,
                'user_id' => auth()->user()->id,
                'msg_type' => 0,
                // 'msg_id' => $request->msg_id,
                'msg_count' => $count,
                'msg_price' => $msg_price,
                // 'sendtime' => $request->sendtime,
                'sendtime' => now(),
            ]);
            // dd("Date is less then now ".$date1);
            // Send CURL to HollaTag server
            // $response=$this->model->send_curl($from,$to,$request->msg);
            $user->credit = $user->credit - $credit;
            $user->save();
            // }
            

            $response = NanoBoxSMS::sendSMS($request->msg,$to,$from);
            
            if ($response && $response['status'] === true) {
                if(isset($response['data']['smsReferences'])){
                    $refs = $response['data']['smsReferences'];
                    foreach ($refs as $ref) {
                        $getMesg = SendMsg::where('to',$ref['msisdn'])->latest()->first();
                        $getMesg->msg_id = $ref['messageId'];
                        $getMesg->save();
                    }
                }else{
                    $status=$status->update([
                        'msg_id'=> $response['data']['smsReference'],
                    ]);
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
                return back()->with(
                    'success','SMS Sent Successfully.To see your delivery report, please wait 15 mins and then click on the REFRESH button under reports section'
                );
            }else{
                // return $response;
                return back()->with(
                    'error','Failed to send sms, please try again later!'
                );
            }

            
            // if($response=='error_limit'){
            //     return back()->with(
            //         'error','Number Limit Error. Please Try Again. Must Use Valid Number.'
            //     );
            // }
            // elseif($response=='error_number'){
            //     return back()->with(
            //         'error','DESTINATION Error. Please Try Again . Must Use Separator Like Comma(,) etc.'
            //     );
            // }
            // elseif($response=='error_restricted_senderid'){
            //     return back()->with(
            //         'error','Sender ID Error. Please enter valid sender ID and Try Again.'
            //     );
            // }
            // elseif($response=='error_user_id'){
            //     return back()->with(
            //         'error','User ID Error. Please enter valid User ID and Try Again.'
            //     );
            // }
            // elseif($response=='error_billing'){
            //     return back()->with(
            //         'error','We were unable to debit your account. Usually a temporary error message.'
            //     );
            // }
            // elseif($response=='error_credit'){
            //     return back()->with(
            //         'error','Insufficient credit.Please recharge your account.'
            //     );
            // }
            // elseif($response=='error_gw'){
            //     return back()->with(
            //         'error','Gateway is down, busy or not responding.'
            //     );
            // }
            // else{
            //     $user->credit=$usercredit-$credit;
            //     $user->save();
            //     $request->msg_id=$response;
            //     $status = SendMsg::create([
            //         'from' => $from,
            //         'to' => $to,
            //         'msg' => $request->msg,
            //         'user_id' => auth()->user()->id,
            //         'msg_type' => $request->type,
            //         'msg_id' => $request->msg_id,
            //         'msg_price' => $msg_price,
            //         'sendtime' => $request->sendtime,
            //     ]);
            //     if($status && $response){
            //         return back()->with(
            //             'success','SMS Sent Successfully.To see your delivery report, please wait 15 mins and then click on the REFRESH button under reports section'
            //         );
            //     }
            //     else{
            //         return back()->with(
            //             'error','Unexpacted error Try again'
            //         );
            //     }
            // }
        // }
    }
    public function fultterwave(Request $request){
        $amount = preg_replace("/([^0-9\\.])/i", "", $request->amount);
        $redirect_url=redirect()->route('buy');
        // Live Mode
        // $secret_key='FLWSECK-2210ef24c412c45930ee73c5a9b532b7-X';
        // $public_key='FLWPUBK-7d98e8963fce4ed48871fc3b696fba3d-X';
        
        //Test Mode
        // $secret_key='FLWSECK_TEST-d3479332090c46a2579e11e7ca3444b5-X';
        // $public_key='FLWPUBK_TEST-b7b96f8f630aa590f0e9f90f0e12c34a-X';
        
        // Helper Function
        $secret_key=@get_setting('flutterwave_secret_key')->value;
        $public_key=@get_setting('flutterwave_public_key')->value;
        $curl=curl_init("https://checkout.flutterwave.com/v3/hosted/pay");
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(["amount"=>$amount,"public_key"=>$public_key,"customer[email]"=>auth()->user()->email,"customer[name]"=>auth()->user()->name,"currency"=>'NGN',"tx_ref"=>'bitethtx-019203',"meta[token]"=>'54',"redirect_url"=>$redirect_url]));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        $response= curl_exec($curl);
        curl_close($curl);
        

        // return $response;


       //* Prepare our rave request
        $req = [
            'tx_ref' => time(),
            'amount' => $request->amount,
            'currency' => 'NGN',
            'redirect_url' => url('/').'/verifyPayment',
            'customer' => [
                'email' => auth()->user()->email,
                'name' => auth()->user()->name
            ],
            
        ];
        
        //* Ca;; f;iterwave emdpoint
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.flutterwave.com/v3/payments',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($req),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$secret_key,
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($response);
        if($res->status == 'success')
        {
            return redirect($res->data->link);
            // $curl=curl_init("https://checkout.flutterwave.com/v3/hosted/pay");
            // curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(["amount"=>$res->data->charged_amount,"public_key"=>$public_key,"customer[email]"=>auth()->user()->email,"customer[name]"=>auth()->user()->name,"tx_ref"=>'bitethtx-019203',"currency"=>'NGN',"meta[token]"=>'54',"redirect_url"=>redirect()->route('buy')]));
            // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            // $response= curl_exec($curl);
            // curl_close($curl);
            // return $response;
        }
        else
        {
            return $redirect_url;
        }

    



    }
    public function verifyPayment(Request $request){
        $redirect_url=redirect()->route('buy');
        
        // Live Mode
        // $secret_key='FLWSECK-2210ef24c412c45930ee73c5a9b532b7-X';
        // $public_key='FLWPUBK-7d98e8963fce4ed48871fc3b696fba3d-X';
        
        
        //Test Mode
        // $secret_key='FLWSECK_TEST-d3479332090c46a2579e11e7ca3444b5-X';
        // $public_key='FLWPUBK_TEST-b7b96f8f630aa590f0e9f90f0e12c34a-X';
    
        // Helper Function
        $secret_key=@get_setting('flutterwave_secret_key')->value;
        $public_key=@get_setting('flutterwave_public_key')->value;
        if(isset($_GET['status']))
        {
            //* check payment status
            if($_GET['status'] == 'cancelled')
            {
                return $redirect_url;
            }
            elseif($request->status='successful')
            {
                $txid = $_GET['transaction_id'];
                $tx_ref=$_GET['tx_ref'];
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/{$txid}/verify",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer ".$secret_key
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $res = json_decode($response);
                if($res->status=='success')
                {
                    $amountPaid = $res->data->charged_amount;
                    $amountToPay = $res->data->amount;
                    if($amountPaid >= $amountToPay)
                    {
                        $user=auth()->user();
                        $usercredit=$user->credit;
                        $user->credit=$usercredit+$amountToPay;
                        $user->save();
                        $status = Transactions::create([
                            'tx_id' => $txid,
                            'tx_ref' => $tx_ref,
                            'amount' => $amountToPay,
                            'user_id' => auth()->user()->id,
                        ]);
                        //* Continue to give item to the user
                        return redirect()->route('buy')->with('success', 'Credit Updated Successfully!');;
                    }
                    else
                    {
                        return redirect()->route('buy')->with('error', 'Fraud transaction detected');;
                    }
                }
                else
                {
                    return 'Can not process payment';
                }
            }
        }
    }
    public function filterKeyword(Request $request){
        
        $msg=strtolower($request->msg);
        $msg = str_replace('.', ' ', $msg);
        // $msg_arr =explode(" ",$msg);
        $msg_arr =explode(" ",$msg);
        $key_arr=array();
        $collection1 = collect($msg);
        // $Arr = array_map('data', explode(',', $msg));
        // $Arr = json_encode($msg);
        // array_diff($array1, $array2);
        // return $msg_arr;
        foreach($msg_arr as $key){
            $keyword=ManageKeyword::where('keyword',$key)->get('keyword')->first();
            if($keyword!=null)
            array_push($key_arr,$keyword);
            // else
            // return 'OK';
        }
        // $filter=$collection1->intersect($key_arr);
        
        // return $filter;
        return $key_arr;
        // return $collection1;
        // $pro=array();
        // if(!$filter->isEmpty()){
        //     $string='';
        //     foreach($filter as $key){
                // return $key;
                // $string .=ucfirst("$key").', ';
        //         array_push($pro,$key);
        //     }
        //     return $pro;
        //     return '<b>Prohibited Keywords: </b> '.$string;
        // }else{
        //     return 'OK';
        // }
    }
}