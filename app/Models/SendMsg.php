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
    
    // public function compaign($request,$user){
    //     // $username=$username=@get_setting('username')->value;
    //     // $userpass=$username=@get_setting('password')->value;
    //     $price = explode(',', $request->msg_price);
    //     array_sum($price);
    //     $credit=array_sum($price);
    //     $usercredit=$user->credit;
    //     $user->credit=$usercredit-$credit;
        

    //     $response = NanoBoxSMS::sendSMS($request->msg,$request->to,$request->from);
    //     $jsonResponse = json_decode($response, true);
    //     if($jsonResponse && isset($jsonResponse['status']) && $jsonResponse['status'] === true){
    //         $user->save();
    //         $request->msg_id=$response;
    //         $status = SendMsg::create([
    //             'from' => $request->from,
    //             'to' => $request->to,
    //             'msg' => $request->msg,
    //             'user_id' => $request->user_id,
    //             'msg_type' => $request->type,
    //             'msg_id' => $request->msg_id,
    //             'msg_price' => $request->msg_price,
    //             'sendtime' => $request->sendtime,
    //         ]);
    
    //         if($status){
    //             return 'SMS  Sent/Saved Successfully';
    //         }
    //         else{
    //             return 'Unexpacted error Try again';
    //         }
    //     }
    // }
    
    
}