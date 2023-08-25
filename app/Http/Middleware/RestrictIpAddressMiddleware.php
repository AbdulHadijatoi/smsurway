<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestrictIpAddressMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // Blocked IP addresses
    // public $restrictedIp = ['192.168.0.1', '202.173.125.72', '192.168.0.3', '202.173.125.71'];
// ,'127.0.0.1'
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // dd( $this->restrictedIp);
        // if (in_array($request->ip(), $this->restrictedIp)) {
        //     return response()->json(['message' => "Your IP Address is blocked by Admin. You are not allowed to access this site."]);
        // }
        // return $next($request);
    }
}
