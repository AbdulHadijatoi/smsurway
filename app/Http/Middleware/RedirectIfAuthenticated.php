<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $role = Auth::user()->role;
                
                if($role == 'admin'){
                    // dd("Your has role ".$role);
                    return redirect(route('home'));
                }elseif($role == 'user'){
                    // dd("user has role ".$role);
                    return redirect(route('dashboard'));
                }elseif($role == 'reseller'){
                    // dd("Reseller has role ".$role);
                    return redirect(route('home1'));
                }else{
                    // dd("defualt has role ".$role);
                    return redirect(route('login'));
                }
            }
        }
        return $next($request);
    }
}
