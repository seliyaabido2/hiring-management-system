<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mail;

class SendQueueEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    public $timeout = 7200; // 2 hours
    /**
     * Create a new job instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {


        $getSuperAdminEmail = User::join('role_user as r', 'r.user_id', '=', 'users.id')
        ->where('r.role_id', 1)
        ->value('email');


            $mailData['subject'] = $this->details['subject'];
            $mailData['email'] = $getSuperAdminEmail;
            $mailDataResponce['data'] = $this->details;

            try {
                // Your code that might throw an exception
                \Mail::send('emails.CandidateSelectionChangeStatus', ['mailDataResponce' => $mailDataResponce['data']], function ($message) use ($mailData) {
                    $message->to($mailData['email'])
                        ->subject($mailData['subject']);
                });
                // Or any other code that might cause an exception
            } catch (\Exception $e) {
                // Handle the exception here
                Log::error('An error occurred: ' . $e->getMessage());
                // You can also redirect the user, display a friendly error message, etc.
            }




    }
}
