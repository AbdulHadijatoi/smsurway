<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\CreditRequestEmail;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Mail;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validator=$request->validate([
            'username' => ['required','unique:users','  max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed','string','min:6' , Rules\Password::defaults()],
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'mobile' => $request->mobile,
            'country' => $request->country,
            'role' => 'user',
            'password' => Hash::make($request->password),
            'reseller_id' => $request->reseller
        ]);

        // event(new Registered($user));
        // $adminEmail = "hamzabajwa300@gmail.com";
        Mail::to($request->email)->send(new WelcomeMail($request->username,$request->email));
        
        Auth::login($user);

        // Mail::to($request->email)->send(new WelcomeMail($request->username,$request->email));
        // $adminEmail = "hamzabajwa300@gmail.com";
        // Mail::to($adminEmail)->send(new CreditRequestEmail("Hamza Bajwa",500));
       
        // return redirect()->route('register')->with('success','User created successfully. Check your mailbox for verify email address');
        return redirect(RouteServiceProvider::HOME);
    }
}
