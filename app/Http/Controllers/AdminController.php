<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Transactions;

class AdminController extends Controller
{
    public function usersList()
    {
        $user= User::where('role','!=','admin')->where('id', '!=', Auth::user()->id)->get()->reverse();
        return view('admin.users',compact('user'));
    }
    public function emailStatus()
    {
        $user= User::where('role','!=','admin')->where('id', '!=', Auth::user()->id)->where('email_verified_at', null)->get()->reverse();
        return view('admin.emailStatus',compact('user'));
    }
    public function usersStatus(Request $request)
    {
        if($request->status!=null){
            $user= User::where('role','!=','admin')->where('status','!=',$request->status)->where('id', '!=', Auth::user()->id)->get()->reverse();
        }
        else{
            $user= User::where('role','!=','admin')->where('id', '!=', Auth::user()->id)->get()->reverse();
        }
        return view('admin.users',compact('user'));
    }
    public function ViewCredit()
    {
        $user= User::where('id', '!=', Auth::user()->id)->get()->reverse();
        return view('admin.ViewCredit',compact('user'));
    }
    public function AddCredit(Request $request)
    {
        // dd($request->id);
        $user = User::find($request->id);
        return view('admin.AddCredit',compact('user'));
    }
    public function profileAction(Request $request)
    {
        $user = User::find($request->id);
        if ($user->senderIds) {
            $senderIds = implode(PHP_EOL, $user->senderIds->sender_ids);
        } else {
            $senderIds = null;
        }

        if($user){
            return View('profileAction', compact('user', 'senderIds'));
        }
        return back();
    }
    public function verifyEmail(Request $request)
    {
        $user = User::find($request->id);
        if($user){
            $user->email_verified_at = now();
            $user->save();
        }
        return back()->with('success','Email Verified Successfully');
    }
    public function UpdateCredit(Request $request) 
    {
        $request->validate([
            'username'  => 'required|string|max:255',
            // 'credit' => 'required',
            // 'credit_price' => 'required',
        ]);
        $user = User::find($request->id);
        if($user){
            // if($request->addcredit>0){
            //     $request->credit=$request->credit+$request->addcredit;
            // }
            $user->credit=$request->credit + $request->addcredit;
            // $user->credit = $request->credit;
            // $user->credit_price = $request->credit_price;
            $user->save();
            if(auth()->user()->role=='admin'){
            return redirect('ViewCredit')->with('success', "Credit Info Updated Sucessfully");
            }
            else{
                return redirect('ViewCredit1')->with('success', "Credit Info Updated Sucessfully");
            }
        }
        return back();
    }
    public function userAction(Request $request) 
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'senderIds' => 'sometimes|string',
            'email' => 'required|string|email|max:255|unique:users,email,'.$request ->id
        ]);

        $senderIds = explode(PHP_EOL, $validated['senderIds']);

        $user = User::find($request->id);

        if($user){
            if($request->role == 'reseller'){
                $reseller = $request->id;
            }
            else{
                $reseller =null;
            }

            $user->username = $request->username;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->country = $request->country;
            $user->role = $request->role;
            $user->status = $request->status;
            $user->reseller_id = $reseller;
            $user->save();

            if ($user->senderIds == null) {
                $user->senderIds()->create([
                    'sender_ids' => $senderIds
                ]);
            } else {
                $user->senderIds()->update([
                    'sender_ids' => $senderIds
                ]);
            }

            return back()->with('success', "Profile Info Updated Sucessfully");
        }
        return back();
    }
    // public function delUser(Request $request){
    //     $user = User::find($request->id)->delete();
    //     return back()->with('success', "User Deleted Sucessfully");
    // }
    public function delUser(Request $request){
        $user = User::find($request->id);
        if($user){
            $user->delete();
        }else{
            return back()->with('error', "User not found");
        }
        return back()->with('success', "User Deleted Sucessfully");
    }
    
    public function credit(Request $request){
        
        $command = "curl --location 'http://vas.interconnectnigeria.com/nanobox/api/v1/sms/balance' \
        --header 'Content-Type: application/json' \
        --header 'Authorization: Bearer NB_liveMjhBQ0NERUY2RjY2OTIzNUMxRjQyNDk5NTMwMDhFMTEzMEMxODlDODI4MDc3RUFDMEUyRDRGRUUyNDNFRkYyRQ=='";
    
        exec($command, $output, $returnVar);
    
        if ($returnVar === 0) {
            $responseJson = implode('', $output); // Convert the array to a single string
            $responseArray = json_decode($responseJson, true);
            
            // Extract the balance and status
            $balance = $responseArray['balance'];
            $status = $responseArray['status'];
            
            if($status){
                $request->session()->put('balance', $balance);
            }
            
            return $balance;
        } else {
            $request->session()->put('balance', 'error');
            return 0;
        }
    }
    public function managetransactions(){
        $transaction= Transactions::whereNull('tx_id')->get();
        return view('admin.managetransactions', compact('transaction'));
    }
    public function approvetransactions(Request $request){
        // return $request->id;
        // $request->validate([
        //     'id' => 'required',
        // ]);
        // return $request->all();
        if($request->ajax()){
            $transaction=Transactions::find($request->id);
            // return $transaction;
            if($transaction){
                // Update Transaction Status
                $tx_id=$transaction->id.$transaction->amount.$transaction->user_id.time();
                $transaction->tx_id = $tx_id;
                $transaction->tx_ref = 1;
                $transaction->save();

                // Update Balance in users table
                $user=User::find($transaction->user_id);
                $user->credit = $user->credit+$transaction->amount;
                $user->save();
                return back()->with('success', "Credit Updated");
            }
            else{
                return back()->with('error', "Unexpacted error try again");
            }
        }
    }
}