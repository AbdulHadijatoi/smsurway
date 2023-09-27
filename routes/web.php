<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AddressBookController;
use App\Http\Controllers\ManagekeywordController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\HollaTag;
use App\Mail\CreditRequestEmail;
use App\Http\Controllers\GsmController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DeliveryReportController;
use App\Http\Controllers\NewsLetterController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\SendSMSController;
use App\Http\Controllers\SmsQueueProcess;
use App\Http\Controllers\TransactionController;
use App\Mail\WelcomeMail;
use App\Models\AddressBook;
use App\Models\SmsReport;
use App\Models\SendMsg;
use App\Models\Compaign;
use App\Models\User;
use App\Models\Message;
use App\Notifications\cronSmsNotify;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionNotificationEmail;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Services\NanoBoxSMS;
use App\Services\OneRouteService;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::post('/ordlr', [DeliveryReportController::class, 'oneRouteReport']);

Route::get('/', function () {
    // return view('auth.login');
    return redirect()->route('login-post');
});

Route::get('/email-design', function () {
    return view('email.welcome');
});
// Route::get('compaign', function () {
//     $currentDate = Carbon::now()->format('Y-m-d H:i:00');
//         $smsManagement = Compaign::where('sendtime','<=',$currentDate)->get();
//         foreach ($smsManagement as $sms){
//             $user_data = User::find($sms->user_id);
//             $msg_model = new SendMsg();
//             $msg_model->compaign($sms,$user_data);
//             $sms->delete();
//             Notification::send($user_data, new cronSmsNotify());
//         }
// });

 // QueueJob
 Route::get('compaign', [SmsQueueProcess::class, 'compaignSms']);
 Route::get('scheduleSms', [SmsQueueProcess::class, 'scheduleSms']);
 Route::get('sendSms', [SmsQueueProcess::class, 'sendSms']);
 Route::get('getReport', [SmsQueueProcess::class, 'getReport']);

 
Route::get('artisan', function () {
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    return 'Optimized Successfully.';
});

Route::get('/link', function () {
    Artisan::call('storage:link');
    return 'Storage Link created.';
});

Route::get('addreport', function(){
    $username=@get_setting('username')->value;
    $userpass=@get_setting('password')->value;
    $sms= SendMsg::where('msg_id','!=','error_limit')->where('msg_id','!=','error_msgid')->where('msg_id','!=','error_restricted_senderid')->get();
    foreach($sms as $item){
        $repo = explode("~", $item->msg_id);
        if($item->msg_id!='error_limit' && $item->msg_id!='error_msgid' && $item->msg_id!="") 
        {
            $price = explode(",", $item->msg_price);
            foreach($repo as $index => $nmbr){
                    $sms_id = explode(",", $nmbr);
                    $chk=SmsReport::where('destination',$sms_id[0])->where('msg_id',$item->id)->where('user_id',$item->user_id)->count();
                    if($chk==0){
                        $curl=curl_init("https://sms.hollatags.com/api/report");
                        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(["user"=>$username,"pass"=>$userpass,"msgid"=>$sms_id[1]]));
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                        $response= curl_exec($curl);
                        curl_close($curl);
                        $status = SmsReport::create([
                            'msg_id' => $item->id,
                            'user_id' => $item->user_id,
                            'destination' => $sms_id[0],
                            'send_id' => $sms_id[1],
                            'units' => $price[$index],
                            'status' => $response,
                        ]);
                    }
                    else{
                        $chk=SmsReport::where('user_id',$item->user_id)->where('status','PROCESSED')->get();
                        if (count($chk->toArray()) > 0){
                            foreach($chk as $i){
                                $curl=curl_init("https://sms.hollatags.com/api/report");
                                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(["user"=>$username,"pass"=>$userpass,"msgid"=>$i->send_id]));
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                                $response= curl_exec($curl);
                                curl_close($curl);
                                $report = SmsReport::find($i->id);
                                $report->update([
                                    'status'=> $response,
                                ]);
                            }
                        }
                    }
            }
        }
    }
    
    return "Report Done.";
});
// Profile Setting

// Reseller and Admin Common functions
Route::middleware(['auth','verified'])->group(function () {
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::post('profile-update', [UserController::class, 'profileUpdate'])->name('profile-update');
    Route::post('changePassword', [UserController::class, 'changePassword'])->name('changePassword');
    Route::post('credit', [AdminController::class, 'credit'])->name('credit');
    Route::get('gsm_networks', [GsmController::class, 'gsmNetworks'])->name('gsm_networks');
    Route::post('addGsmNetwork', [GsmController::class, 'addGsmNetwork'])->name('addGsmNetwork');
    Route::post('updateGsmNetwork', [GsmController::class, 'updateGsmNetwork'])->name('updateGsmNetwork');
    Route::post('delGsm', [GsmController::class, 'delGsm'])->name('delGsm');
    Route::get('profileAction/{id}', [AdminController::class, 'profileAction'])->name('profileAction');
    Route::get('ViewCredit1', [ResellerController::class, 'ViewCredit1'])->name('ViewCredit1');
    Route::get('AddCredit/{id}', [AdminController::class, 'AddCredit'])->name('AddCredit');
    Route::post('UpdateCredit/{id}', [AdminController::class, 'UpdateCredit'])->name('UpdateCredit');
    // Manual Verify User
    Route::get('userStatus1', [AdminController::class, 'emailStatus'])->name('userStatus1');
    Route::get('verifyEmail1/{id}', [AdminController::class, 'verifyEmail'])->name('verifyEmail1');
    
    Route::post('addUser', [ResellerController::class, 'addUser'])->name('addUser');
    Route::post('delUser', [AdminController::class, 'delUser'])->name('delUser');
    
 
});
// Conctact Us Feeds
// Route::get('feed', [ContactUsController::class, 'show'])->name('feed');
Route::get('/feed', function () {        
    return view('feed');    
})->name('feed');

// Group Middleware for Reseller
Route::middleware(['auth','verified','role:reseller'])->group(function () {
    Route::get('/home1', function () {
        $day1 = Carbon::now()->subDays(1)->format("Y-m-d");
        $day30 = Carbon::now()->subDays(30)->format("Y-m-d");
        $count['address']= AddressBook::count();
        $count['day1']= SmsReport::whereDate('created_at', '>=', $day1)->count();
        $count['day30']= SmsReport::whereDate('created_at', '>=', $day30)->count();
        return view('home',compact('count'));    
    })->name('home1');
    // User Section
    Route::get('usersList1', [ResellerController::class, 'usersList1'])->name('usersList1');
    Route::get('usersStatus/{status}', [AdminController::class, 'usersStatus'])->name('usersStatus1');
    Route::post('userAction/{id}', [AdminController::class, 'userAction'])->name('userAction1');
    // Route::post('delUser', [AdminController::class, 'delUser'])->name('delUser1'); 
    // Manual Transactions
    Route::get('managetransactions1', [ResellerController::class, 'managetransactions1'])->name('managetransactions1');
    Route::post('approvetransactions/', [AdminController::class, 'approvetransactions'])->name('approvetransactions1');
    Route::post('resellerLogo', [ResellerController::class,'resellerLogo'])->name('resellerLogo');
 });
// Group Middleware for Admin
Route::middleware(['auth','verified','role:admin'])->group(function () {
    Route::post('/oneroute-low-balance', function () {

        $getOneRouteBalance = Setting::where('key', 'oneroute_low_balance')->first();
        if($getOneRouteBalance){
            $getOneRouteBalance->value = 0;
            $getOneRouteBalance->save();
        }
        return redirect()->back();
    })->name('oneroute.low_balance'); 
    Route::get('/home', function () { 
        $getOneRouteBalance = Setting::where('key', 'oneroute_low_balance')->first();
        $day1 = Carbon::now()->subDays(1)->format('Y-m-d');
        $day30 = Carbon::now()->subDays(30)->format('Y-m-d');
        // return $day30;
        $count['address']= AddressBook::count();
        $count['day1']= SendMsg::whereDate('created_at', '>=', $day1)->count();
        $count['day30']= SendMsg::whereDate('created_at', '>=', $day30)->count();
        // return $count['day30'];
        if($getOneRouteBalance){
            $is_balance_low = $getOneRouteBalance->value;
        }else{
            $is_balance_low = "0";
        }


        return view('home',compact('count','is_balance_low'));
    })->name('home');

    // User Section
    Route::get('usersList', [AdminController::class, 'usersList'])->name('usersList');
    Route::get('usersStatus/{status}', [AdminController::class, 'usersStatus'])->name('usersStatus');
    Route::post('userAction/{id}', [AdminController::class, 'userAction'])->name('userAction');
    Route::get('ViewCredit', [AdminController::class, 'ViewCredit'])->name('ViewCredit');
    // Route::get('AddCredit/{id}', [AdminController::class, 'AddCredit'])->name('AddCredit');
    // Route::post('UpdateCredit/{id}', [AdminController::class, 'UpdateCredit'])->name('UpdateCredit');

    // Manual Verify User
    Route::get('userStatus', [AdminController::class, 'emailStatus'])->name('userStatus');
    Route::get('verifyEmail/{id}', [AdminController::class, 'verifyEmail'])->name('verifyEmail');


    // GSM Controllers/Routes
    Route::get('gsmPrefix', [GsmController::class, 'gsmPrefix'])->name('gsmPrefix');
    Route::post('addPrefix', [GsmController::class, 'addPrefix'])->name('addPrefix');
    Route::post('updatePrefix', [GsmController::class, 'updatePrefix'])->name('updatePrefix');
    Route::post('delPrefix', [GsmController::class, 'delPrefix'])->name('delPrefix');

    // News Letter
    Route::get('newsletter', [NewsLetterController::class, 'index'])->name('newsletter');
    Route::post('newsletter.store', [NewsLetterController::class, 'store'])->name('newsletter.store');
    // Route::post('users-send-email', [NewsLetterController::class, 'sendEmail'])->name('ajax.send.email');
    // Contact Feeds
    Route::get('contactFeeds', [ContactUsController::class, 'show'])->name('contactFeeds');
    Route::post('delFeed', [ContactUsController::class, 'delFeed'])->name('delFeed');

    // Manage Keywords
    Route::get('keyword', [ManagekeywordController::class, 'index'])->name('keyword');
    Route::post('addkeyword', [ManagekeywordController::class, 'create'])->name('addkeyword');
    Route::get('delkeyword', [ManagekeywordController::class, 'destroy'])->name('delkeyword');

    // Manual Transactions
    Route::get('managetransactions', [AdminController::class, 'managetransactions'])->name('managetransactions');
    Route::post('approvetransactions/', [AdminController::class, 'approvetransactions'])->name('approvetransactions');

    

    Route::get('get-report', [UserController::class, 'report'])->name('report');
    Route::get('get-report/today', [UserController::class, 'reportDay'])->name('report.today');
    Route::get('get-report/month', [UserController::class, 'reportMonth'])->name('report.thisMonth');
    Route::get('getReportDetail', [UserController::class, 'reportDetail'])->name('getReportDetail');
    

});

// Group Middleware for User
Route::middleware(['auth','verified'])->group(function () {
    Route::get('buy', [UserController::class, 'buy'])->name('buy');
    Route::post('buy', [TransactionController::class, 'store'])->name('pay_dpo');
    // Website Setting
    Route::get('setting', [SettingController::class, 'index'])->name('setting');
    Route::post('addsetting', [SettingController::class, 'create'])->name('addsetting');
    Route::post('updateSetting', [SettingController::class, 'update'])->name('updateSetting');
    

});
Route::middleware(['auth','verified','role:user'])->group(function () {
    Route::get('dashboard', function (){
        $day1 = Carbon::now()->subDays(1)->format("Y-m-d");
        // return $day1;
        $day30 = Carbon::now()->subDays(30)->format("Y-m-d");
        $count['address']= AddressBook::where('user_id', auth()->user()->id)->count();
        $count['day1']= SendMsg::whereDate('created_at', '>=', $day1)->where('user_id', auth()->user()->id)->get()->count();
        $count['day30']= SendMsg::whereDate('created_at', '>=', $day30)->where('user_id', auth()->user()->id)->get()->count();
        return view('dashboard',compact('count')); 
    })->name('dashboard');
    Route::get('send', [UserController::class, 'send'])->name('send');
    Route::post('sendSMS', [SendSMSController::class, 'sendSMS'])->name('sendSMS');
    Route::post('msgSave', [UserController::class, 'msgSave'])->name('msgSave');
    
    Route::get('report', [UserController::class, 'report'])->name('report');
    Route::get('report/today', [UserController::class, 'report'])->name('report.today');
    Route::get('report/month', [UserController::class, 'report'])->name('report.thisMonth');
    
    Route::get('reportDetail', [UserController::class, 'reportDetail'])->name('reportDetail');
    
    // New Routes for sms rearrange logic
    Route::get('refreshreport', [SmsQueueProcess::class, 'refreshreport'])->name('refreshreport');
    Route::get('updatereport', [SmsQueueProcess::class, 'updatereport'])->name('updatereport');
    
    Route::get('voicecall', [UserController::class, 'voicecall'])->name('voicecall');
    Route::get('inbox', [UserController::class, 'inbox'])->name('inbox');
    Route::post('fultterwave', [HollaTag::class, 'fultterwave'])->name('fultterwave');
    Route::get('verifyPayment', [HollaTag::class, 'verifyPayment'])->name('verifyPayment');
    Route::post('filterKeyword', [HollaTag::class, 'filterKeyword'])->name('filterKeyword');
    
    // Route::get('/address', [UserController::class, 'address'])->name('address');
    // Route::post('/addgroup', [UserController::class, 'addgroup'])->name('addgroup');
    Route::get('address', [AddressBookController::class, 'address'])->name('address');
    Route::post('addgroup', [AddressBookController::class, 'addgroup'])->name('addgroup');
    Route::post('edit', [AddressBookController::class, 'edit'])->name('edit');

    // Contact Us
    Route::get('contact', [ContactUsController::class, 'index'])->name('contact');
    Route::post('contactUs', [ContactUsController::class, 'create'])->name('contactUs');

    // Manual Transaction
    Route::post('manual', [UserController::class, 'manualTransaction'])->name('manual');

    
    Route::get('queue', [SmsQueueProcess::class, 'index'])->name('queue');
    

});
// New Routes
Auth::routes(['verify' => false]);
require __DIR__.'/auth.php';
// Route For Mailing
Route::get('/email', function(){
    return new WelcomeMail();
});

Route::get('testingRoute2', function(){
    
    $adminEmail = "abdulhadijatoi@gmail.com";
    Mail::to($adminEmail)->send(new CreditRequestEmail(auth()->user()->name,999));
    return 'test email2 sent successfully';
});

Route::get('testingRoute', function(){
    $userName = "Hadi";
    $transactionType = "CREDIT";
    $totalPaid = 1008;
    $vatValue = 0.075;
    $vat = $totalPaid*$vatValue;
    $transactionFeeValue = 0.0162;
    $transactionFee = $totalPaid*$transactionFeeValue;
    $credit = $totalPaid-$vat-$transactionFee;
    Mail::to('abdulhadijatoi@gmail.com')->send(new TransactionNotificationEmail(
        $userName,
        $transactionType,
        $totalPaid,
        $vatValue*100,
        $vat,
        $transactionFeeValue*100,
        $transactionFee,
        $credit,
    ));
    return 'test email sent successfully';
});