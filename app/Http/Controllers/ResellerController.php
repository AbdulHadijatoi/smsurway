<?php

namespace App\Http\Controllers;

use App\Models\ResellerLogo;
use App\Models\Transactions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ResellerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    function resellerLogo(Request $request){
        $request->validate([
            'image' => 'required',
        ]);
        // dd($request->all());

        $chk= ResellerLogo::where('id', auth()->user()->id)->count();
        
        if($chk==0){
            // $name = $request->file('proof')->getClientOriginalName();
            // $path = $request->file('proof')->store('public/images');
            // return $request->all();
            $filename='';

            if($request->file('image')){
                $file= $request->file('image');
                $filename = "reseller".auth()->user()->id.$file->getClientOriginalName();
                // dd($filename);

                $file-> move(public_path('storage'), $filename);
                ResellerLogo::create([
                    'reseller_id' => auth()->user()->id,
                    'logo' => $filename,
                ]);
                return back()->with(
                    'success','Logo Uploaded Successfully.'
                );
            }
        }
        elseif($chk==1){
            $filename='';
            if($request->file('image')){
                $file= $request->file('image');
                $filename = "reseller".auth()->user()->id.$file->getClientOriginalName();
                $file-> move(public_path('storage'), $filename);
                ResellerLogo::where('reseller_id', auth()->user()->id)->update([
                    'logo' => $filename,
                ]);
                return back()->with(
                    'success','Logo Updated Successfully.'
                );
            }
        }
        else{
            return back()->with(
                'error','Unexpected Error. Please try again.'
            );
        }
        // return view('admin.users',compact('user'));
    }
    public function usersList1()
    {
        $user= User::where('role','!=','admin')->where('id', '!=', auth()->user()->id)->where('reseller_id', auth()->user()->id)->get()->reverse();
        return view('admin.users',compact('user'));
    }
    public function addUser(Request $request){
        $validator=$request->validate([
            'username' => ['required','unique:users','  max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed','string','min:6' , Rules\Password::defaults()],
        ]);
        // dd($request->all());
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'mobile' => $request->mobile,
            'country' => $request->country,
            'role' => 'user',
            'password' => Hash::make($request->password),
            'reseller_id' => $request->reseller
        ]);
        return back()->with('success','New User created successfully.');
    }
    public function ViewCredit1()
    {
        $user= User::where('id', '!=', auth()->user()->id)->where('reseller_id', auth()->user()->id)->get()->reverse();
        return view('admin.ViewCredit',compact('user'));
    }
    public function managetransactions1(){
        // ->where('reseller_id', auth()->user()->id)
        $transaction= Transactions::whereNull('tx_id')->get();
        return view('admin.managetransactions', compact('transaction'));
    }

}
