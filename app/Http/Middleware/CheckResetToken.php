<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckResetToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) 
    {
      
      $token = $request->route()->parameter('token');
      $email = $request->email;
      
      // Retrieve the password reset record from the database
      $tokenData = DB::table('password_resets')
          ->where('email', $email)
          ->first();
       
      if(!empty($tokenData)){

        $expires_at = Carbon::parse($tokenData->created_at)->addMinutes(config('auth.passwords.users.expire'));
          
        if (now()->gt($expires_at)) {
              // Token has expired
              return redirect()->route('password.request')->with('error', 'Link has been expired');
        }


      }else{
        abort(419, 'Invalid password reset token');
      }
    
      
     
      return $next($request);
       
     
    }
}
