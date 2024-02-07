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

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function dashboard()
    {
        return view('dashboard');
    }
    
    public function send()
    {
        $address = AddressBook::where('user_id', auth()->user()->id)->get();
        $senderIds = OneRouteService::fetchChannels();
        // $senderIds = auth()->user()->senderIds->sender_ids;
        return view('user.send',compact('address', 'senderIds'));
    }
    
    public function report(){
        $user = Auth::user();
        $role = $user->role;

        //$query = SendMsg::with('user')->select('created_at', 'from', 'msg', 'msg_count', 'id','user_id');
        
        \DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

        $query = SendMsg::with('user')
                ->groupBy('sms_stamp')
                ->select('created_at', 'from', 'msg', 'msg_count', 'id','user_id', \DB::raw('COUNT(*) as sms_count')); 
        
        if ($role != 'admin') {
            $query->where('user_id', $user->id);
        }

        $report = $query->get();

        return view('user.report', compact('report'));
    }
    
    public function reportDay()
    {
        $user = Auth::user();
        $role = $user->role;

        $day = Carbon::now()->subDays();
        
        //$query = SendMsg::with('user')->whereDate('created_at', '>=' ,$day)->select('created_at', 'from', 'msg', 'msg_count', 'id','user_id');
        \DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

        $query = SendMsg::with('user')
                ->whereDate('created_at', '>=' ,$day)
                ->groupBy('sms_stamp')
                ->select('created_at', 'from', 'msg', 'msg_count', 'id','user_id', \DB::raw('COUNT(*) as sms_count')); 
                
        if ($role != 'admin') {
            $query->where('user_id', $user->id);
        }

        $report = $query->get();

        return view('user.report', compact('report'));
    }
    
    public function reportMonth(){
        return view('user.report');
    }

    public function getReportData(Request $request){
      
        // Page Length
        $pageNumber = ( $request->start / $request->length )+1;
        $pageLength = $request->length;
        $skip       = ($pageNumber-1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        // get data from products table
        $user = Auth::user();
        $role = $user->role;

        $currentDate = Carbon::now();
        $daysToSubtract = $currentDate->day;
    
        $startDate = $currentDate->subDays($daysToSubtract)->format('Y-m-d');
        
        // $query = SendMsg::with('user')->whereDate('created_at', '>=', $startDate)->select('created_at', 'from', 'msg', 'msg_count', 'id','user_id');
        \DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

        $query = SendMsg::with('user')
                ->whereDate('created_at', '>=', $startDate)
                ->groupBy('sms_stamp')
                ->select('created_at', 'from', 'msg', 'msg_count', 'id','user_id','sms_stamp', \DB::raw('COUNT(*) as sms_count')); 


        
        if ($role != 'admin') {
            $query->where('user_id', $user->id);
        }

        // Search
        $search = $request->search;
        $query = $query->where(function($query) use ($search){
            $query->orWhere('from', 'like', "%".$search."%");
            $query->orWhere('to', 'like', "%".$search."%");
            $query->orWhere('msg', 'like', "%".$search."%");
            $query->orWhereHas('user', function($q) use ($search){
                $q->where('name', 'like', "%".$search."%");
            });
        });

        
        $query = $query->orderBy('created_at', 'desc');
        $recordsFiltered = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();

        $formattedData = $data->map(function ($report, $index) {
            $datework = Carbon::createFromDate($report->created_at);
            $now = Carbon::now();
            $testdate = $datework->diffInDays($now);
            $diff = Carbon::parse($report->created_at)->diffInDays(Carbon::now());

            $report->index = $index;
            $report->user_name = $report->user ? $report->user->name : '-';
            $report->created_date = $report->created_at->diffForHumans() ?? '-';
            $report->from = $report->from ?? "-";
            $report->msg_count = $report->msg_count ?? '-';
            $report->sms_stamp = $report->sms_stamp;
            $report->message = \Illuminate\Support\Str::limit($report->msg, 50, $end = '...');

            $report->view_report = Auth::user()->role === 'admin' ? 
            route('getReportDetail', ['send_id' => $report->sms_stamp, 'msg' => $report->msg, 'from' => $report->from]) :
            route('reportDetail', ['send_id' => $report->sms_stamp, 'msg' => $report->msg, 'from' => $report->from]);

            $report->message_button = '<button type="button" class="btn btn-sm" data-toggle="popover" title="Message" data-content="' . $report->msg . '">' .
                            \Illuminate\Support\Str::limit($report->msg, 50, $end='...') .
                            '</button>';
            
            return $report;
        });

        return response()->json([
            "draw" => $request->draw,
            "recordsTotal" => $recordsFiltered,
            "recordsFiltered" => $recordsFiltered,
            'data' => $formattedData,
        ], 200);

    }
    
    // public function reportDetail(Request $request)
    // {
    //     $from = $request->from;
    //     $msg = $request->msg;
    //     $report = Message::where('send_id', $request->send_id)->get(['created_at','to','msg','msg_count','msg_price','delivery_status']);
    //     return view('user.reportDetail',compact('report','from','msg'));
    // }
    
    public function reportDetail(Request $request) {
        $from = $request->from;
        $msg = $request->msg;
        
        $sms_stamp = $request->send_id;

        $smsReports = SendMsg::where('sms_stamp', $sms_stamp)->get(['id']);

        if ($smsReports->isNotEmpty()) {
            $report_ids = $smsReports->pluck('id')->toArray();
        } else {
            $report_ids = [];
        }
        
        $report = Message::whereIn('send_id', $report_ids)->get(['created_at','to','msg','msg_count','msg_price','delivery_status']);
        return view('user.reportDetail',compact('report','from','msg'));
    }
    
    public function voicecall()
    {
        $report= SmsReport::where('user_id', auth()->user()->id) ->join('send_msgs', 'sms_reports.msg_id', '=', 'send_msgs.id')->orderBy('sms_reports.id','desc')->get();
        return view('user.report',compact('report'));
    }
    public function inbox()
    {
        $report= SmsReport::where('user_id', auth()->user()->id) ->join('send_msgs', 'sms_reports.msg_id', '=', 'send_msgs.id')->orderBy('sms_reports.id','desc')->get();
        return view('user.report',compact('report'));
    }
    public function profile()
    {
        $resellerLogo='default';
        if(auth()->user()->role=='reseller'){
           $count= ResellerLogo::where('reseller_id',auth()->user()->id)->count();
           if($count>0){
            $resellerLogo= ResellerLogo::where('reseller_id',auth()->user()->id)->first('logo');
            }
            else{
                $resellerLogo='default';
            }
        }
        return view('profile', compact('resellerLogo'));
    }
    public function buy(Request $request, DPOService $service, Transactions $transactions)
    {
        $validated = $request->validate([
            'TransID' => ['sometimes', 'string'],
            'TransactionToken' => ['sometimes', 'string'],
            'CompanyRef' => ['sometimes', 'string']
        ]);

        $getVat = Setting::where('key', 'vat')->first();
        $vatValue = $getVat->value;
        if ($request->has('TransID')) {
            if ($request->has('cancel')) {
                return view('user.buy', [
                    'transactionSuccessful' => false,
                    'transactionCode' => $validated['CompanyRef'],
                    'vatValue'=>$vatValue,
                ]);
            }

            $transaction = $transactions
                                ->whereToken($validated['TransID'])
                                ->whereTxId($validated['CompanyRef'])
                                ->first();

            if ($transaction) {
                try {
                    $result = $service->verifyToken($transaction->token);

                    if ($result === '900') {
                        return redirect(
                            get_setting('dpo_pay_url')->value . $transaction->token
                        );
                    } elseif ($result === '000') {
                        $service->updateTransactionRecord($transaction);

                        return view('user.buy', ['transactionSuccessful' => true,'vatValue'=>$vatValue]);
                    } else {
                        return view('user.buy', [
                            'transactionSuccessful' => false,
                            'transactionCode' => $validated['CompanyRef'],
                            'vatValue'=>$vatValue
                        ]);
                    }
                } catch (Throwable $e) {
                    return back()->with('error', $e->getMessage());
                }
            } else {
                return view('user.buy', [
                    'transactionSuccessful' => false,
                    'transactionCode' => $validated['CompanyRef'],
                    'vatValue'=>$vatValue
                ]);
            }
        }

        return view('user.buy', ['transactionSuccessful' => '', 'vatValue'=>$vatValue]);
    }
    public function contact()
    {
        $report= SmsReport::where('user_id', auth()->user()->id) ->join('send_msgs', 'sms_reports.msg_id', '=', 'send_msgs.id')->orderBy('sms_reports.id','desc')->get();
        return view('user.report',compact('report'));
    }
    public function profileUpdate(Request $request) 
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.auth()->id()
        ]);
        $user = auth()->user();
        // dd($user);
        $user->update([
            'username'=> $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'country' => $request->country
        ]);
        return redirect()->route('profile')->with('success','Profile updated successfully.');
    }
    public function profileAction(Request $request) 
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.auth()->id()
        ]);
        $user = auth()->user();
        $user->update([
            'username'              => $request->username,
            'name'              => $request->name,
            'email'             => $request->email,
            'mobile'             => $request->mobile,
            'country'             => $request->country,
            'role'             => $request->role,
        ]);
        return redirect()->route('profileAction')->with('success','User updated successfully.');
    }
    public function changePassword(Request $request)
    {
         
        if (!(Hash::check($request->currentpassword, Auth::user()->password))) {
            return back()->with(
                'msg_currentpassword','Your current password does not matches with the password you provided! Please try again.'
            );
        }
        
        if(strcmp($request->currentpassword, $request->password) == 0){
            return back()->with(
                'msg_currentpassword','New Password cannot be same as your current password! Please choose a different password.'
            );
        }
        $this->validate($request, [
            'currentpassword' => 'required',
            'password'     => 'required|string|min:8|confirmed',
        ]);
        $user = Auth::user();
        $user->password = bcrypt($request->get('password'));
        $user->save();
        Auth::logout();
        return redirect()->route('login');
    }
    public function manualTransaction(Request $request){
        $request->validate([
            'username'  => 'required',
            'amount'  => 'required',
            'image' => 'required',
        ]);
        $chk=User::where('username', $request->username )->exists();
        if($chk!=null){
            // $name = $request->file('proof')->getClientOriginalName();
            // $path = $request->file('proof')->store('public/images');
            // return $request->all();
            $filename='';
            if($request->file('image')){
                $file= $request->file('image');
                $filename = date('YmdHi').$file->getClientOriginalName();
                $file-> move(public_path('storage'), $filename);
                // $name = $request->file('image')->getClientOriginalName();
                // $filename = $request->file('image')->store('public/storage');
                Transactions::create([
                    'user_id' => auth()->user()->id,
                    'media' => $filename,
                    'amount' => $request->amount,
                ]);
                // return $filename;

                $adminEmail = "support@smsurway.com.ng";
                Mail::to($adminEmail)->send(new CreditRequestEmail(auth()->user()->name,$request->amount));
                $adminEmail = "infotekps24@gmail.com";
                // $adminEmail = "hamzabajwa300@gmail.com";
                Mail::to($adminEmail)->send(new CreditRequestEmail(auth()->user()->name,$request->amount));
                return back()->with(
                    'success','Credit Request Submitted Successfully. We will get back to you after review.'
                );
            }
        }
        else{
            return back()->with(
                'error','Invalid Username.'
            );
        }
        
    }
}