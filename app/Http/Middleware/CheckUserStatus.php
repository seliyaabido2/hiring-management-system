<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
        {
            if (auth()->check() && auth()->user()->status == 'Active') {
                return $next($request);
            }

            Auth::guard()->logout();
            $request->session()->invalidate();

            return redirect()->route('login')->with('error', 'Your account is not active'); // You can define this route in your routes file.
        }
}
