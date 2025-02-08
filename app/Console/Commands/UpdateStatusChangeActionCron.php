<?php

namespace App\Console\Commands;

use App\BodCandidate;
use App\Candidate;
use App\CandidateJobStatusComment;
use App\EmployerJob;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;



class UpdateStatusChangeActionCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-status-change-action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $today_date = Carbon::now();
        $before14Days = $today_date->subDays(3);
        $formattedDate = $before14Days->format('Y-m-d');

        $candidateJobStatus = CandidateJobStatusComment::whereDate('updated_at', '<', $formattedDate)
        ->where(function ($query) {
            $query->where([

                'field_status' => 'None',
                'is_active_status' => '1',
            ])->orWhere([

                'field_status' => 'Stand By',
                'is_active_status' => '1',
            ])->orWhere([
                'field_status' => 'No Response',
                'is_active_status' => '1',
            ]);
        })
        ->get();

        foreach ($candidateJobStatus as $value) {



            if($value->is_bod_candidate_id =='1'){
                $candidateType ="bodAppliedJobs";
                $users_email = User::whereHas('roles', function ($query) {
                    $query->whereIn('role_id', [1, 2]);
                })->pluck('email')->toArray();

            }else{
                $candidateType ="appliedJobs";
                $users_email = User::where('id',$value->job_creator_id)->value('email');
            }



            $back_path = "?path=admin/appliedJobs/$value->job_id";

            $send_url = url('admin/'.$candidateType.'/candidatesViewJobStatus/'.encrypt_data($value->applied_job_id).$back_path);

            $eJob = EmployerJob::find($value->job_id);

            $employer = User::where('id',$eJob->user_id)->with('EmployerDetail')->first();
            $roleName = getUserRole($eJob->user_id);
            if (in_array($roleName, array('Super Admin', 'Admin'))) {
                $company_name ="Admin";
                $employer_name = "Admin";
            }else{
                $company_name =$employer->EmployerDetail->company_name;
                $employer_name = $employer->first_name;
            }

            if($value->is_bod_candidate_id == '1'){
                $candidate = BodCandidate::where('candidate_id',$value->candidate_id)->first();
            }else{
                $candidate = Candidate::where('candidate_id',$value->candidate_id)->first();
            }

            $mailData = [
                'subject' => 'Follow-Up: '.$candidate->name.' for '.$eJob->job_title.' at '.$company_name,
                'Round' => $value->status,
                'candidateName' => $candidate->name,
                'status' => $value->field_status,
                'candidateId' => $value->candidate_id,
                'job_title' => $eJob->job_title,
                'company_name' => $company_name,
                'employer_name' => $employer_name,
                'send_url' => $send_url,
            ];

            \Mail::send('emails.UpdateStatusChangeAction', ['mailDataResponce' => $mailData], function ($message) use ($mailData,$users_email)  {
                $message->to($users_email)
                    ->subject($mailData['subject']);
            });

        }

        // $CandidateJobStatusComment1 = CandidateJobStatusComment::whereDate('updated_at', '<=', $formattedDate)
        // ->where(function ($query) {
        //     // where('status', '=', 'Final Selection')
        //     where('field_status', 'Stand By')
        //     ->OrWhere('field_status', 'None')
        //     ->where('is_active_status', '1')
        //     ->get();
        // dd($CandidateJobStatusComment1);

        // foreach ($CandidateJobStatusComment1 as $value) {

        //     $updated_at = Carbon::parse($value->updated_at)->format('Y-m-d');

        //     $today_date = Carbon::now();

        //     $diffInDays = $today_date->diffInDays($updated_at);

        //     if ($diffInDays >= 3) {

        //         // send mail




        //     }
        // }
    }
}
