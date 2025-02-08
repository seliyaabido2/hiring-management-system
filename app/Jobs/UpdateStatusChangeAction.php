<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\User;
use Illuminate\Support\Facades\DB;
use Mail;

class UpdateStatusChangeAction implements ShouldQueue
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

        $getSuperAdminEmail = DB::table('users as a')
                                ->join('role_user as r', 'r.user_id', '=', 'a.id')
                                ->select('a.email')
                                ->where('r.role_id', 1)
                                ->first();

            $mailData['subject'] = $this->details['subject'];
            $mailData['email'] = $getSuperAdminEmail->email;
            $mailDataResponce['data'] = $this->details;


            \Mail::send('emails.CandidateSelectionChangeStatus', ['mailDataResponce' => $mailDataResponce['data']], function ($message) use ($mailData) {
                $message->to($mailData['email'])
                    ->subject($mailData['subject']);
            });

    }
}
