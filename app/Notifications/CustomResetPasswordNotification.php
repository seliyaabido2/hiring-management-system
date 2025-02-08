<?php


namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends ResetPasswordNotification
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
    ->subject('Custom Reset Password Notification')
    ->view('emails.CustomForgotPassword', [
        'actionUrl' => url(config('app.url') . route('password.reset', ['token' => $this->token, 'email' => $notifiable->email], false)),
        'actionText' => 'Reset Password',
        'user'=>$notifiable,
    ]);
    }
}
