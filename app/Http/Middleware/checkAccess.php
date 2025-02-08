<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class checkAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {

        // dd(Auth::check());
        if (Auth::check() && Auth::user()->access_token == $request->bearerToken())  {
          return $next($request);
        }
        else{
          return response()->json(['message' => 'Unauthenticated.'], 401);
          // return response()->json(['status' => false, 'message' => 'Unauthenticated', 'data' => (object) []]);
        }
      }
}
