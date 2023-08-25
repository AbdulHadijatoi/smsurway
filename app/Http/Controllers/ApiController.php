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
use Carbon\Carbon;

class ApiController extends Controller
{
    protected $user;
    public $model;
    public function __construct()
    {
        $this->model = new SendMsg();
    }
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
 
    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
        $user = JWTAuth::authenticate($request->token);
        return response()->json(['credit' => $user->credit]);
    }
    public function report(Request $request)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $product = $this->user->getreport()->get()->where('msg_id',$request->msgid)->first();
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
    public function credit()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        if (!$this->user) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user not found.'
            ], 400);
        }
        return response()->json([
            'credit' => $this->user->credit
        ]);
        return $this->user;
    }
    public function send(Request $request)
    {
        // return $request->all();
        $this->user = JWTAuth::parseToken()->authenticate();
        $credentials = $request->only('from', 'to','msg','token');
        //valid credential
        $validator = Validator::make($credentials, [
            'from' => 'required',
            'to' => 'required',
            'msg' => 'required|string|min:1|max:950',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $to=$request->to;
        // Demo Code
        $count=$this->model->count_msg($request->msg);
        $to = Str::of($to)->replaceMatches('/[^A-Za-z0-9]++/', ',');
        $msg_price=$this->model->count_credit($to,$count);
        $credit=$msg_price['credit'];
        $to=$msg_price['send'];
        $msg_price=$msg_price['msg_price'];
        // $this->user->credit;
        $usercredit=$this->user->credit;

        if($usercredit<$credit){
            return response()->json(['error' => $validator->messages()], 403);
        }

        $response=$this->model->send_curl($request->from,$to,$request->msg);

        if ($response && $response['status'] === true) {
            if(isset($response['data']['smsReferences'])){
                $refs = $response['data']['smsReferences'];
                foreach ($refs as $ref) {

                    $product = $this->user->sendmsg()->create([
                        'to' => $to,
                        'from' => $request->from,
                        'msg' => $request->msg,
                        'user_id' => $this->user->id,
                        'msg_id' => $ref['messageId'],
                        'msg_count' => $count,
                        'msg_price' => $msg_price
                    ]);
                    //Product created, return success response
                    if($product){
                        $this->user->credit=$usercredit-$credit;
                        $this->user->save();
                    }
                }
            }else{
                $product = $this->user->sendmsg()->create([
                    'to' => $to,
                    'from' => $request->from,
                    'msg' => $request->msg,
                    'user_id' => $this->user->id,
                    'msg_id' => $response['data']['smsReference'],
                    'msg_count' => $count,
                    'msg_price' => $msg_price
                ]);
                //Product created, return success response
                if($product){
                    $this->user->credit=$usercredit-$credit;
                    $this->user->save();
                }
            }
            
            $userCredit = $this->user->credit;
            
            $low_balance = Setting::where('key', 'low_balance')->first();
            if($low_balance){
                if($userCredit < $low_balance->value && $this->user->low_balance != 1){
                    //send email for low balance notification
                    Mail::to($this->user->email)->send(new LowBalanceNotificationEmail(
                        $this->user->name
                    ));
                    $this->user->low_balance = 1;
                    $this->user->save();
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'SMS created successfully',
                'response' => [
                    'msg_id'=>$product->id
                ],
            ], Response::HTTP_OK);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'SMS not send. Check credentials and try again',
            ], Response::HTTP_OK);
        }

    }
}
