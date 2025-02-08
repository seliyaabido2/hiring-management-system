<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {

        // dd('jhvhg');
        // Perform your custom logic here

        // Example: Check if the user is eligible to reset their password

        // Custom validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // Perform additional checks or database queries

        // Send the password reset email
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        // Example: Handle different responses

        switch ($response) {

            case \Illuminate\Support\Facades\Password::RESET_LINK_SENT:

                return redirect('login')->with('success', trans($response));

            case \Illuminate\Support\Facades\Password::INVALID_USER:

                return redirect()->back()->with('error', trans($response));

            default:

                return redirect()->back()->withErrors([
                    'email' => trans($response),
                ]);
        }
    }
}
