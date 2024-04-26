<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        $token = $user->createToken('API Token')->accessToken;

        return response([ 
            'message' => "Registeration successfull!",
            'status' => true,
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($data)) {
            return response([
                'status' => false,
                'message' => 'Invalid username or password'
            ]);
        }

        $tokenData = auth()->user()->createToken('API Token');

        $token = $tokenData->accessToken;

        $expiration = $tokenData->token->expires_at->diffInSeconds(Carbon::now());
        // Convert the expiration time to a CarbonInterval object
        $interval = CarbonInterval::seconds($expiration);

        // Format the interval as a simple readable string
        $readableExpiration = $interval->cascade()->forHumans();

        return response([
            'expiry' => $readableExpiration, 
            'token' => $token, 
            'user' => auth()->user()
        ]);

    }

    public function logout(Request $request) {
        // Retrieve the current authenticated user's tokens
        $user = auth()->user();
        
        // Revoke the user's tokens
        $user->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}