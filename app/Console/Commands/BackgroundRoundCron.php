<?php

namespace App\Console\Commands;

use App\BodCandidate;
use App\Candidate;
use App\CandidateJobStatusComment;
use App\EmployerJob;
use App\User;
use Illuminate\Console\Command;
    use Carbon\Carbon;
use DateTime;

class BackgroundRoundCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'background-round-cron';

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
        $currentDate = Carbon::now();
        $before14Days = $currentDate->subDays(14);
        $formattedDate = $before14Days->format('Y-m-d');


        $candidateJobStatus = CandidateJobStatusComment::whereDate('updated_at', '<=', $formattedDate)
        ->where(function ($query) {
            $query->where([
                'status' => 'Background Check',
                'field_status' => 'None',
                'is_active_status' => '1',
            ])->orWhere([
                'status' => 'In person Interview',
                'field_status' => 'Selected',
                'is_active_status' => '1',
            ])->orWhere([
                'status' => 'In person Interview',
                'field_status' => 'Skip',
                'is_active_status' => '1',
            ]);
        })
        ->get();



        if (!empty($candidateJobStatus)) {
            foreach ($candidateJobStatus as $key => $value) {


               $candidate_status =  CandidateJobStatusComment::where('id', $value->id)->first();
               $candidateJobStatusComment = CandidateJobStatusComment::where(['job_id'=> $candidate_status->job_id,'job_id'=> $candidate_status->candidate_id,'status'=>"Background Check"])->first();

               $data = CandidateJobStatusComment::updateOrCreate(
                [
                    'job_id' => $candidate_status->job_id,
                    'candidate_id' => $candidate_status->candidate_id,
                    'status' => "Background Check"
                ],
                [
                    'is_active_status' => $candidate_status->is_active_status,
                    'job_id' => $candidate_status->job_id,
                    'candidate_id' => $candidate_status->candidate_id,
                    'applied_job_id' => $candidate_status->applied_job_id,
                    'user_id' => $candidate_status->user_id,
                    'job_creator_id' => $candidate_status->job_creator_id,
                    'status' => "Background Check",
                    'field_status' => "Selected",
                    'is_bod_candidate_id' => $candidate_status->is_bod_candidate_id,
                    'additional_note' => isset($candidateJobStatusComment->additional_note) ? $candidateJobStatusComment->additional_note : null,
                    'links' => isset($candidateJobStatusComment->links) ? $candidateJobStatusComment->links : null,

                ]
            );
            $candidate_status->update(['is_active_status'=>"0"]);

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

                $details = [
                    'subject' => 'Update on '.$eJob->job_title.' Position:'.$candidate->name.' Final Selection',
                    'Round' => "Background Check",
                    'candidateName' => $candidate->name,
                    'status' => "Selected",
                    'candidateId' => $value->candidate_id,
                    'job_title' => $eJob->job_title,
                    'company_name' => $company_name,
                    'employer_name' => $employer_name,
                ];

                $job = (new \App\Jobs\SendQueueEmail($details))
                    ->delay(now()->addSeconds(1));

                dispatch($job);
            }
        }

    }
}
