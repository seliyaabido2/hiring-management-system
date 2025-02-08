<?php

namespace App\Http\Controllers\Admin;

use App\AppliedJob;
use App\Candidate;
use App\EmployerJob;
use App\EmployerDetail;
use App\AssessmentLink;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use App\CandidateJobStatusComment;
use App\Jobs\SendQueueNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\CandidateSelectionChangeStatus;
use App\User;

class BodAppliedJobsController extends Controller
{
    public function index(Request $request)
    {

        abort_if(Gate::denies('bod_job_applied_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userId =Auth::user()->id;
        $roleName = getUserRole($userId);


            $appliedJobs = DB::table('applied_jobs')
            ->join('employer_jobs', 'applied_jobs.job_id', '=', 'employer_jobs.id')
            ->join('bod_candidates', 'applied_jobs.candidate_id', '=', 'bod_candidates.candidate_id')
            ->select('applied_jobs.job_id', 'employer_jobs.job_title', 'employer_jobs.job_type', 'employer_jobs.job_start_date', 'employer_jobs.status', 'employer_jobs.job_recruiment_duration', DB::raw('COUNT(applied_jobs.candidate_id) as total_candidates'))
            ->whereNull('bod_candidates.deleted_at')
            ->groupBy('applied_jobs.job_id')
            ->groupBy('employer_jobs.job_title')
            ->groupBy('employer_jobs.job_type')
            ->groupBy('employer_jobs.job_start_date')
            ->groupBy('employer_jobs.status')
            ->groupBy('employer_jobs.job_recruiment_duration')
            ->where('applied_jobs.is_bod_candidate_id',1)
            ->whereNull('employer_jobs.deleted_at')
            ->get();



        return view('admin.bodAppliedJobs.index', compact('appliedJobs'));
    }

    public function edit($candidateJobStatusCommentid)
    {
        abort_if(Gate::denies('bod_job_applied_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = decrypt_data($candidateJobStatusCommentid);
        //  dd($id);

        $candidateJobStatusComment = CandidateJobStatusComment::where('id',$id)->first();


        return view('admin.bodAppliedJobs.edit', compact('candidateJobStatusComment'));
    }

    public function show($JobId)
    {

        abort_if(Gate::denies('bod_job_applied_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userId =Auth::user()->id;
        $roleName = getUserRole($userId);

        $appliedJobCandidates = AppliedJob::with('bod_candidate')->with('assessment_links')->with('AppliedJobActivestatus')->where('job_id', $JobId)->where('is_bod_candidate_id',1)->get();

        $getJobDetail = EmployerJob::where('id',$JobId)->first();


        return view('admin.bodAppliedJobs.AppliedCandidates', compact('appliedJobCandidates','getJobDetail'));
    }


    public function update(Request $request)
    {


        if($request->input('job_status') == 'Selected'){
            $status  = $request->input('selected_job_status');
        }else{
            $status  = $request->input('job_status');
        }

        $applied_job_id = $request->input('applied_job_id');
        $job_id =$request->input('job_id');

        $appliedjobupdate = AppliedJob::where('id',$applied_job_id)->first();
        $appliedjobupdate->status =$status;
        $appliedjobupdate->save();


        if($request->has('additional_note')){
            $additional_note  = $request->input('additional_note');
        }else{
            $additional_note  = 'skip_additional_note';
        }


        $candidateDataArr = array(
            'job_id' => $request->input('job_id'),
            'candidate_id' => $request->input('candidate_id'),
            'status' => $status,
            'additional_note' => $additional_note,
        );

        if ($status == 'Phone Interview' || $status == 'Assessment' ) {
            $arrNew = array('links' => $request->input('assessment_link'));
            $candidateDataArr =  array_merge($candidateDataArr, $arrNew);
        }

        $data = CandidateJobStatusComment::updateOrCreate(
            [
                'job_id' => $request->input('job_id'),
                'candidate_id' => $request->input('candidate_id'),
                'status' => $status
            ],
            $candidateDataArr
        );

        if ($data == false) {

            return redirect()->route('admin.bodAppliedJobs.candidatesViewJobStatus',encrypt_data($applied_job_id))->with('error', 'somthing went wrong');
        } else {
            return redirect()->route('admin.bodAppliedJobs.candidatesViewJobStatus',encrypt_data($applied_job_id))->with('success', 'status updated successfully.');
        }
    }

    public function singleUpdate(Request $request)
    {


        $candidateId = $request->input('candidate_id');
        $job_id = $request->input('job_id');
        $status = $request->input('status');

        $additional_note = $request->input('additional_note');

        $candidateActiveJobStatusComment = CandidateJobStatusComment::where([
            'job_id' => $job_id,
            'candidate_id' => $candidateId,
            'is_active_status' => '1',
        ])->first();
        CandidateJobStatusComment::where(['job_id' => $job_id, 'candidate_id' => $candidateId])->update(['is_active_status' => '0']);



        $data = CandidateJobStatusComment::updateOrCreate(
            [
                'job_id' => $job_id,
                'candidate_id' => $candidateId,
                'status' => $status
            ],
            [
                'additional_note' => $additional_note,
                'is_active_status' => '1',
                'job_id' => $job_id,
                'user_id' => $candidateActiveJobStatusComment->user_id,
                'job_creator_id' => $candidateActiveJobStatusComment->job_creator_id,
                'applied_job_id' => $candidateActiveJobStatusComment->applied_job_id,
                'is_bod_candidate_id' => $candidateActiveJobStatusComment->is_bod_candidate_id,
            ]
        );


        if ($data == false) {

            return back()->with('error', 'somthing went wrong');
        } else {
            return back()->with('success', 'Notes updated successfully.');
        }
    }

    public function candidatesChangeJobStatus(Request $request)
    {
        $id = decrypt_data($request->input('id'));
        $status = $request->input('status');

        $updateStatus = AppliedJob::where('id', $id)->update(['status' => $status]);

        if ($updateStatus  == true) {

            return true;
        } else {

            return false;
        }
    }

    public function candidatesChangeJobStatusAction(Request $request)
    {


        $id = decrypt_data($request->input('id'));
        $link = $request->input('assessment_link');

        $appliedJobData = AppliedJob::where('id', $id)->first();
        $status = $request->input('candidatejobstatus');

        $updateStatus = $appliedJobData->update(['status' => $status]);
        $data = array(
            'job_id' => $appliedJobData->job_id,
            'candidate_id' => $appliedJobData->candidate_id,
            'status' => $status,
            'link' => $link,

        );

        $candidate = AssessmentLink::updateOrCreate(
            ['status' => $status, 'candidate_id' => $appliedJobData->candidate_id],
            $data
        );



        // AssessmentLink::wh>
        if ($candidate == false) {
            return redirect()->back()->with('error', 'somthing went wrong');
        } else {
            return redirect()->back()->with('success', 'booking url added.');
        }
    }

    public function candidatesViewJobStatus(Request $request,$id)
    {
        $backPagePath = $request->path;
        $id = decrypt_data($id);
        $employerdetails=null;
        $userId =Auth::user()->id;
        $roleName = getUserRole($userId);

        if($roleName=='Employer'){
           $employerdetails = EmployerDetail::select('calendly_details')->where('user_id',$userId)->first();

           if(!empty($employerdetails) && !empty($employerdetails->calendly_details)){
                $calendlyDetails = json_decode($employerdetails->calendly_details);
                if(isset($calendlyDetails->collection[0]->user->scheduling_url) && !empty($calendlyDetails->collection[0]->user->scheduling_url)){
                    $scheduling_url=$calendlyDetails->collection[0]->user->scheduling_url;
                }

                // if(!empty($calendlyDetails['user'])){

                // }
           }else{

           }
        }
        // dd($employerdetails);

        $appliedJobData = AppliedJob::where('id', $id)->with('bodSingleCandidate')->with('getJobDetail')->first();
        // dd($appliedJobData);
        $candidateJobStatusComment = CandidateJobStatusComment::where(['candidate_id'=>$appliedJobData->candidate_id,'job_id'=>$appliedJobData->job_id])->get();
        $candidateJobCurrentStatus = CandidateJobStatusComment::where(['candidate_id'=>$appliedJobData->candidate_id,'job_id'=>$appliedJobData->job_id,'is_active_status'=>'1'])->first();

        return view('admin.bodAppliedJobs.candidateJobStatus', compact('backPagePath','appliedJobData','candidateJobStatusComment','candidateJobCurrentStatus'));
    }

    public function candidateFieldChangeStatus(Request $request){

        // dd($request->all());
        $candidateName = $request->input('candidateName');
        $candidateId = $request->input('candidateId');
        $job_id = $request->input('jobId');
        $field_status = $request->input('currentStatus');
        $status = $request->input('status');


        //  dd($request->all());

        $candidateActiveJobStatusComment = CandidateJobStatusComment::where([
            'job_id' => $job_id,
            'candidate_id' => $candidateId,
            'is_active_status' => '1'
        ])->first();

        $candidateJobStatusComment = CandidateJobStatusComment::where([
            'job_id' => $job_id,
            'candidate_id' => $candidateId,
            'status' => $status
        ])->first();

        CandidateJobStatusComment::where(['job_id' => $job_id,'candidate_id' => $candidateId])->update(['is_active_status' => '0']);

        $data = CandidateJobStatusComment::updateOrCreate(
            [
                'job_id' => $job_id,
                'candidate_id' => $candidateId,
                'status' => $status
            ],
            [
                'is_active_status' => '1',
                'job_id' => $job_id,
                'candidate_id' => $candidateId,
                'applied_job_id' => $candidateActiveJobStatusComment->applied_job_id,
                'user_id' => $candidateActiveJobStatusComment->user_id,
                'job_creator_id' => $candidateActiveJobStatusComment->job_creator_id,
                'status' => $status,
                'field_status' => $field_status,
                'is_bod_candidate_id' => $candidateActiveJobStatusComment->is_bod_candidate_id,
                'additional_note' => isset($candidateJobStatusComment->additional_note) ? $candidateJobStatusComment->additional_note : null,
                'links' => isset($candidateJobStatusComment->links) ? $candidateJobStatusComment->links : null,

            ]
        );

        if($status == 'Final Selection'){


            $details = [
                'subject' => 'Candidate Selection Change Status',
                'Round' => $status,
                'candidateName' => $candidateName,
                'status' => $field_status,
                'candidateId' => $candidateId,
            ];

            $job = (new \App\Jobs\SendQueueEmail($details))
                    ->delay(now()->addSeconds(1));

            dispatch($job);


            // Mail::to($getSuperAdminEmail->email)->send(new CandidateSelectionChangeStatus($mailData));


        }


        if($data == true){
             //notification code
          $notificationItem =array();
          $job = EmployerJob::where('id',$job_id)->first();
          $recevers =  User::whereHas('roles', function ($query)  {
              $query->where('role_id','=',1)->orWhere('role_id','=',2);
          })->orWhere('id','=',$candidateActiveJobStatusComment->user_id)->pluck('id')->toArray();
          $sender =User::where('id',$candidateActiveJobStatusComment->job_creator_id)->first();
          $notificationItem['sender_id'] = $sender->id;
          $notificationItem['recever_ids'] = $recevers;
          $notificationItem['title'] = "Candidate (" . $candidateName . ") Status updated in -" . $job->job_title;
          $notificationItem['type'] = "candidate_job_round_status";
          $notificationItem['message'] = "Candidate (".$candidateName.") Status updated in Job -".$job->job_title."  round-".$status." as ".$field_status;

          $job = (new SendQueueNotification($notificationItem))
                  ->delay(now()->addSeconds(2));

          dispatch($job);

          //end notification code
            return response()->json(['status' => true, 'message' => 'status Updated successfully.']);
        }else{
            return response()->json(['status' => true, 'message' => 'status Update query issue.']);
        }

    }

    public function candidateSkipFieldChangeStatus(Request $request){


        $candidateId = $request->input('skip_candidate_id');
        $candidateName = $request->input('candidateName');

        $job_id = $request->input('skip_job_id');
        $skip_status = $request->input('skip_status');
        $skip_field_status = $request->input('skip_field_status');

        // dd($skip_field_status);


        $candidateActiveJobStatusComment = CandidateJobStatusComment::where([
            'job_id' => $job_id,
            'candidate_id' => $candidateId,
            'is_active_status' => '1'
        ])->first();

        $candidateJobStatusComment = CandidateJobStatusComment::where([
            'job_id' => $job_id,
            'candidate_id' => $candidateId,
            'status' => $skip_field_status
        ])->first();

        CandidateJobStatusComment::where(['job_id' => $job_id,'candidate_id' => $candidateId])->update(['is_active_status' => '0']);

        $data = CandidateJobStatusComment::updateOrCreate(
            [
                'job_id' => $job_id,
                'candidate_id' => $candidateId,
                'status' => $skip_field_status
            ],
            [
                'is_active_status' => '1',
                'job_id' => $job_id,
                'candidate_id' => $candidateId,
                'status' => $skip_status,
                'applied_job_id' => $candidateActiveJobStatusComment->applied_job_id,
                'user_id' => $candidateActiveJobStatusComment->user_id,
                'job_creator_id' => $candidateActiveJobStatusComment->job_creator_id,
                'field_status' => $skip_field_status,
                'is_bod_candidate_id' => $candidateActiveJobStatusComment->is_bod_candidate_id,
                'additional_note' => isset($candidateJobStatusComment->additional_note) ? $candidateJobStatusComment->additional_note : null,
                'links' => isset($candidateJobStatusComment->links) ? $candidateJobStatusComment->links : null,

            ]
        );


        if($data == true){

               //notification code
               $notificationItem =array();
               $job = EmployerJob::where('id',$job_id)->first();
               $recevers =  User::whereHas('roles', function ($query)  {
                   $query->where('role_id','=',1)->orWhere('role_id','=',2);
               })->orWhere('id','=',$candidateActiveJobStatusComment->user_id)->pluck('id')->toArray();
               $sender =User::where('id',$candidateActiveJobStatusComment->job_creator_id)->first();
               $notificationItem['sender_id'] = $sender->id;
               $notificationItem['recever_ids'] = $recevers;
               $notificationItem['title'] = "Candidate (" . $candidateName . ") Status updated in -" . $job->job_title;
               $notificationItem['type'] = "candidate_job_round_status";
               $notificationItem['message'] = "Candidate (".$candidateName.") Status updated in Job -".$job->job_title."  round-".$skip_status." as ".$skip_field_status;

               $job = (new SendQueueNotification($notificationItem))
                       ->delay(now()->addSeconds(2));

               dispatch($job);

               //end notification code

            return back()->with('success', 'status Updated successfully.');
        }else{
            return back()->with('success', 'status Update query issue.');

        }

    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('bod_job_applied_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = $request->input('id');
        AppliedJob::where('id',$id)->delete();
        return back()->with('success', 'Job Applied Deleted successfully.');

    }
}
