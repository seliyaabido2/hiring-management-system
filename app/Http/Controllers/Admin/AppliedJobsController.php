<?php

namespace App\Http\Controllers\Admin;

use App\AppliedJob;
use App\Candidate;
use App\EmployerJob;
use App\EmployerDetail;
use App\AssessmentLink;
use App\BodCandidate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\CandidateJobStatusComment;
use App\Jobs\SendQueueNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\CandidateSelectionChangeStatus;
use App\SavedCandidate;
use App\SavedJobTemplate;
use App\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;


class AppliedJobsController extends Controller
{

    public function recentStatusUpdates()
    {

        abort_if(Gate::denies('recent_status_updates'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userId = Auth::user()->id;
        $roleName = getUserRole($userId);

        $sqlquery = CandidateJobStatusComment::with('getJobDetail', 'getEmployerDetail')->orderBy('id', 'desc');

        if (in_array($roleName, ['Super Admin', 'Admin'])) {
            $recent_20_Data = $sqlquery;
        } else if ($roleName == "Employer") {
            $recent_20_Data = $sqlquery->where('job_creator_id', $userId);
        } else {
            $recent_20_Data = $sqlquery->where('user_id', $userId);
        }

        $recent_20_Data = $recent_20_Data->get();

        $recent_20_Data->map(function ($CandidateJobStatusComment) {
            if ($CandidateJobStatusComment->is_bod_candidate_id == 1) {
                $CandidateJobStatusComment->setRelation('candidate', $CandidateJobStatusComment->singlebodCandidate);
            } else {
                $CandidateJobStatusComment->setRelation('candidate', $CandidateJobStatusComment->singleCandidate);
            }
            return $CandidateJobStatusComment;
        });

        return view('admin.appliedJobs.recentStatusUpdates');
    }

    public function getRecentStatusUpdatesDatatable(Request $request)
    {
        $userId = Auth::user()->id;
        $roleName = getUserRole($userId);

        $query = CandidateJobStatusComment::with(['getJobDetail', 'getEmployerDetail'])
            ->orderBy('id', 'desc');

        // Apply role-specific conditions
        $query->when(!in_array($roleName, ['Super Admin', 'Admin']), function ($q) use ($userId, $roleName) {
            $q->where(function ($subQuery) use ($userId, $roleName) {
                if ($roleName == "Employer") {
                    $subQuery->where('job_creator_id', $userId);
                } else {
                    $subQuery->where('user_id', $userId);
                }
            });
        });

        // Define searchable columns
        $searchableColumns = [
            'getEmployerDetail.id',
            'getJobDetail.job_title',
            'candidate.name',
            'status',
            'additional_note',
        ];

        // Apply search conditions to the query
        foreach ($searchableColumns as $column) {
            if ($request->filled($column)) {
                $query->where($column, 'like', '%' . $request->input($column) . '%');
            }
        }

        // Execute the query
        $dataTable = DataTables::of($query);

        // Define columns for DataTable
        $dataTable->addColumn('id', function ($row) {
            return $row->id;
        });

        $dataTable->addColumn('action', function ($row) {
            // You can add custom actions here if needed
            return '';
        });

        // Add additional columns as needed
        $dataTable->addColumn('job_title', function ($row) {
            return $row->getJobDetail->job_title ?? '';
        });

        $dataTable->addColumn('employer_id', function ($row) {
            return $row->getEmployerDetail->id ?? '';
        });

        $dataTable->addColumn('candidate_name', function ($row) {
            // Include the relationship logic here
            $candidate = $row->is_bod_candidate_id == 1 ? $row->singlebodCandidate : $row->singleCandidate;
            $row->setRelation('candidate', $candidate);

            return $candidate->name ?? '';
        });

        $dataTable->addColumn('candidate_resume', function ($row) {
            // Include the relationship logic here
            $candidate = $row->is_bod_candidate_id == 1 ? $row->singlebodCandidate : $row->singleCandidate;
            $row->setRelation('candidate', $candidate);

            return $candidate->resume ?? '';
        });

        // Include other columns...

        // Set raw columns
        // Set raw columns
        $dataTable->addColumn('actions', function ($row) use ($roleName) {
            $statusViewButton = '<a class="btn btn-xs btn-success" href="' . route('admin.appliedJobs.candidatesViewJobStatus', [encrypt_data($row->applied_job_id), 'path=admin/recentStatusUpdates/appliedJobs']) . '">'
                . trans('global.status_view') . '</a>';

            $actions = $statusViewButton;

            return $actions;
        })
            ->rawColumns(['actions', 'candidate_resume', 'view_link'])
            ->make(true);


        return $dataTable->make(true);
    }




    public function index(Request $request)
    {
        // dd('njn');
        abort_if(Gate::denies('job_applied_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userId = Auth::user()->id;
        $roleName = getUserRole($userId);

        if (in_array($roleName, array('Super Admin', 'Admin'))) {

            $appliedJobs = DB::table('applied_jobs')
                ->join('employer_jobs', 'applied_jobs.job_id', '=', 'employer_jobs.id')
                ->leftJoin('bod_candidates', function ($join) {
                    $join->on('applied_jobs.is_bod_candidate_id', '=', DB::raw(1))
                        ->on('applied_jobs.candidate_id', '=', 'bod_candidates.candidate_id');
                })
                ->leftJoin('candidates', function ($join) {
                    $join->on('applied_jobs.is_bod_candidate_id', '=', DB::raw(0))
                        ->on('applied_jobs.candidate_id', '=', 'candidates.candidate_id');
                })
                ->select(
                    'applied_jobs.job_id',
                    'employer_jobs.job_title',
                    'employer_jobs.job_type',
                    'employer_jobs.job_start_date',
                    'employer_jobs.status',
                    'employer_jobs.job_recruiment_duration',
                    DB::raw('COUNT(applied_jobs.candidate_id) as total_candidates'),

                )
                ->whereNull('candidates.deleted_at')
                ->whereNull('employer_jobs.deleted_at')
                ->whereNull('applied_jobs.deleted_at')
                ->groupBy('applied_jobs.job_id')
                ->groupBy('employer_jobs.job_title')
                ->groupBy('employer_jobs.job_type')
                ->groupBy('employer_jobs.job_start_date')
                ->groupBy('employer_jobs.status')
                ->groupBy('employer_jobs.job_recruiment_duration')
                ->get();
        } else if ($roleName == "Recruiter") {
            $appliedJobs = DB::table('applied_jobs')
                ->join('employer_jobs', 'applied_jobs.job_id', '=', 'employer_jobs.id')
                ->leftJoin('bod_candidates', function ($join) {
                    $join->whereNull('bod_candidates.deleted_at');
                    $join->on('applied_jobs.is_bod_candidate_id', '=', DB::raw(1))
                        ->on('applied_jobs.candidate_id', '=', 'bod_candidates.candidate_id');
                })
                ->leftJoin('candidates', function ($join) {
                    $join->whereNull('candidates.deleted_at');
                    $join->on('applied_jobs.is_bod_candidate_id', '=', DB::raw(0))
                        ->on('applied_jobs.candidate_id', '=', 'candidates.candidate_id');
                })
                ->select(
                    'applied_jobs.job_id',
                    'employer_jobs.job_title',
                    'employer_jobs.job_type',
                    'employer_jobs.job_start_date',
                    'employer_jobs.status',
                    'employer_jobs.job_recruiment_duration',
                    DB::raw('COUNT(applied_jobs.candidate_id) as total_candidates'),

                )
                ->where('applied_jobs.user_id', $userId)
                ->whereNull('candidates.deleted_at')
                ->whereNull('employer_jobs.deleted_at')
                ->whereNull('applied_jobs.deleted_at')
                ->groupBy('applied_jobs.job_id')
                ->groupBy('employer_jobs.job_title')
                ->groupBy('employer_jobs.job_type')
                ->groupBy('employer_jobs.job_start_date')
                ->groupBy('employer_jobs.status')
                ->groupBy('employer_jobs.job_recruiment_duration')
                ->get();
        } else {

            $appliedJobs = DB::table('applied_jobs')
                ->join('employer_jobs', 'applied_jobs.job_id', '=', 'employer_jobs.id')
                ->leftJoin('bod_candidates', function ($join) {
                    $join->whereNull('bod_candidates.deleted_at');
                    $join->on('applied_jobs.is_bod_candidate_id', '=', DB::raw(1))
                        ->on('applied_jobs.candidate_id', '=', 'bod_candidates.candidate_id');
                })
                ->leftJoin('candidates', function ($join) {
                    $join->whereNull('candidates.deleted_at');
                    $join->on('applied_jobs.is_bod_candidate_id', '=', DB::raw(0))
                        ->on('applied_jobs.candidate_id', '=', 'candidates.candidate_id');
                })
                ->select(
                    'applied_jobs.job_id',
                    'employer_jobs.job_title',
                    'employer_jobs.job_type',
                    'employer_jobs.job_start_date',
                    'employer_jobs.status',
                    'employer_jobs.job_recruiment_duration',
                    DB::raw('COUNT(applied_jobs.candidate_id) as total_candidates'),

                )
                ->where('applied_jobs.job_creator_id', $userId)
                ->whereNull('candidates.deleted_at')
                ->whereNull('employer_jobs.deleted_at')
                ->whereNull('applied_jobs.deleted_at')
                ->groupBy('applied_jobs.job_id')
                ->groupBy('employer_jobs.job_title')
                ->groupBy('employer_jobs.job_type')
                ->groupBy('employer_jobs.job_start_date')
                ->groupBy('employer_jobs.status')
                ->groupBy('employer_jobs.job_recruiment_duration')
                ->get();
        }

        // dd($appliedJobs);

        return view('admin.appliedJobs.index', compact('appliedJobs'));
    }

    public function edit($candidateJobStatusCommentid)
    {
        abort_if(Gate::denies('job_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = decrypt_data($candidateJobStatusCommentid);
        $candidateJobStatusComment = CandidateJobStatusComment::where('id', $id)->first();

        // dd($candidateJobStatusComment);

        return view('admin.appliedJobs.edit', compact('candidateJobStatusComment'));
    }

    public function show($JobId)
    {

        // abort_if(Gate::denies('candidate_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $getJobDetail = EmployerJob::where('id', $JobId)->first();
        if ($getJobDetail == null) {
            return back();
        }
        $userId = Auth::user()->id;
        $roleName = getUserRole($userId);

        if (in_array($roleName, array('Super Admin', 'Admin'))) {

            $appliedJobCandidates = AppliedJob::with(['assessment_links', 'AppliedJobActivestatus' => function ($query) use ($JobId) {
                $query->where('job_id', $JobId);
            }])
                ->where('job_id', $JobId)
                ->get()
                ->map(function ($appliedJob) {
                    if ($appliedJob->is_bod_candidate_id == 1) {
                        $appliedJob->setRelation('candidate', $appliedJob->bod_candidate);
                    } else {
                        $appliedJob->setRelation('candidate', $appliedJob->candidate);
                    }
                    return $appliedJob;
                });
        } else if ($roleName == "Recruiter") {
            $appliedJobCandidates = AppliedJob::with(['assessment_links', 'AppliedJobActivestatus' => function ($query) use ($JobId) {
                $query->where('job_id', $JobId);
            }])
                ->where(['job_id' => $JobId, 'user_id' => $userId])
                ->get()
                ->map(function ($appliedJob) {
                    if ($appliedJob->is_bod_candidate_id == 1) {
                        $appliedJob->setRelation('candidate', $appliedJob->bod_candidate);
                    } else {
                        $appliedJob->setRelation('candidate', $appliedJob->candidate);
                    }
                    return $appliedJob;
                });
        } else {

            $appliedJobCandidates = AppliedJob::with([
                'assessment_links',
                'AppliedJobActivestatus' => function ($query) use ($JobId) {
                    $query->where('job_id', $JobId);
                },
                'savedCandidate'
            ])
                ->where(['job_id' => $JobId, 'job_creator_id' => $userId])
                ->get()
                ->map(function ($appliedJob) {
                    if ($appliedJob->is_bod_candidate_id == 1) {
                        $appliedJob->setRelation('candidate', $appliedJob->bod_candidate);
                    } else {
                        $appliedJob->setRelation('candidate', $appliedJob->candidate);
                    }
                    return $appliedJob;
                });
        }



        return view('admin.appliedJobs.AppliedCandidates', compact('appliedJobCandidates', 'getJobDetail'));
    }

    public function update(Request $request)
    {

        if ($request->input('job_status') == 'Selected') {
            $status  = $request->input('selected_job_status');
        } else {
            $status  = $request->input('job_status');
        }

        $applied_job_id = $request->input('applied_job_id');
        $job_id = $request->input('job_id');

        $appliedjobupdate = AppliedJob::where('id', $applied_job_id)->first();
        $appliedjobupdate->status = $status;
        $appliedjobupdate->save();


        if ($request->has('additional_note')) {
            $additional_note  = $request->input('additional_note');
        } else {
            $additional_note  = 'skip_additional_note';
        }


        $candidateDataArr = array(
            'job_id' => $request->input('job_id'),
            'candidate_id' => $request->input('candidate_id'),
            'status' => $status,
            'additional_note' => $additional_note,
            'additional_note' => $additional_note,
        );

        if ($status == 'Phone Interview' || $status == 'Assessment') {
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

            return redirect()->route('admin.appliedJobs.candidatesViewJobStatus', encrypt_data($applied_job_id))->with('error', 'somthing went wrong');
        } else {
            return redirect()->route('admin.appliedJobs.candidatesViewJobStatus', encrypt_data($applied_job_id))->with('success', 'status updated successfully.');
        }
    }

    public function singleUpdate(Request $request)
    {

        // dd($request->all());
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



        // $candidateStatus_id = $request->input('candidateStatus_id');

        // $candidateJobStatusComment = CandidateJobStatusComment::where('id',$candidateStatus_id)->first();

        // $appliedjob= AppliedJob::where(['job_id'=>$candidateJobStatusComment->job_id,'candidate_id'=>$candidateJobStatusComment->candidate_id])->first();


        // if ($candidateJobStatusComment->status == 'Phone Interview' || $candidateJobStatusComment->status == 'Assessment' ) {
        //     $candidateJobStatusComment->links =$request->input('assessment_link');
        // }

        // if ($candidateJobStatusComment->job_status == 'Stand By') {
        //     $candidateJobStatusComment->status = $request->input('status');

        // }


        // if($request->has('edit_status')){
        //     $appliedjob->status = $request->input('edit_status');
        //     $candidateJobStatusComment->status = $request->input('edit_status');
        //      $appliedjob->save();

        // }


        // if($request->has('additional_note')){
        //     $candidateJobStatusComment->additional_note  = $request->input('additional_note');
        // }


        // $data = $candidateJobStatusComment->save();

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

    public function candidatesViewJobStatus(Request $request, $id)
    {
        $backPagePath = $request->path;
        $id = decrypt_data($id);
        $employerdetails = null;
        $userId = Auth::user()->id;
        $roleName = getUserRole($userId);

        if ($roleName == 'Employer') {
            $employerdetails = EmployerDetail::where('user_id', $userId)->first();

            if (!empty($employerdetails) && !empty($employerdetails->calendly_details)) {
                $calendlyDetails = json_decode($employerdetails->calendly_details);
                if (isset($calendlyDetails->collection[0]->user->scheduling_url) && !empty($calendlyDetails->collection[0]->user->scheduling_url)) {
                    $scheduling_url = $calendlyDetails->collection[0]->user->scheduling_url;
                }
            } else {
            }
        }

        // $candidateJobStatusComment = CandidateJobStatusComment::where('id', $id)->first();

        $appliedJobData = AppliedJob::where('id', $id)
            ->with('getJobDetail')
            ->first();

        if ($appliedJobData) {
            if ($appliedJobData->is_bod_candidate_id == 1) {
                $appliedJobData->setRelation('candidate', $appliedJobData->bodSingleCandidate);
            } else {
                $appliedJobData->setRelation('candidate', $appliedJobData->singleCandidate);
            }
        }

        $candidateJobStatusComment = CandidateJobStatusComment::where(['candidate_id' => $appliedJobData->candidate_id, 'job_id' => $appliedJobData->job_id])->get();
        $candidateJobCurrentStatus = CandidateJobStatusComment::where(['candidate_id' => $appliedJobData->candidate_id, 'job_id' => $appliedJobData->job_id, 'is_active_status' => '1'])->first();

        return view('admin.appliedJobs.candidateJobStatus', compact('backPagePath', 'appliedJobData', 'candidateJobStatusComment', 'candidateJobCurrentStatus', 'employerdetails'));
    }

    public function candidateFieldChangeStatus(Request $request)
    {
        $candidateName = $request->input('candidateName');
        $candidateId = $request->input('candidateId');
        $job_id = $request->input('jobId');
        $field_status = $request->input('currentStatus');
        $status = $request->input('status');
        $userId = Auth::user()->id;
        $roleName = getUserRole($userId);

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

        CandidateJobStatusComment::where(['job_id' => $job_id, 'candidate_id' => $candidateId])->update(['is_active_status' => '0']);

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


        $eJob = EmployerJob::find($job_id);

        $employer = User::where('id', $eJob->user_id)->with('EmployerDetail')->first();

        if (in_array($roleName, array('Super Admin', 'Admin'))) {
            $company_name = "Admin";
            $employer_name = "Admin";
        } else {
            $company_name = $employer->EmployerDetail->company_name;
            $employer_name = $employer->first_name;
        }

        if ($status == 'Shortlist' && $field_status == 'Selected') {

            $details = [
                'subject' => 'Update on ' . $eJob->job_title . ' Position:' . $candidateName . ' Shortlisted',
                'Round' => $status,
                'candidateName' => $candidateName,
                'status' => $field_status,
                'candidateId' => $candidateId,
                'job_title' => $eJob->job_title,
                'company_name' => $company_name,
                'employer_name' => $employer_name,
            ];


            $job = (new \App\Jobs\SendQueueEmail($details))
                ->delay(now()->addSeconds(1));


            dispatch($job);
        }

        if ($status == 'Final Selection') {


            $details = [
                'subject' => 'Update on ' . $eJob->job_title . ' Position:' . $candidateName . ' Final Selection',
                'Round' => $status,
                'candidateName' => $candidateName,
                'status' => $field_status,
                'candidateId' => $candidateId,
                'job_title' => $eJob->job_title,
                'company_name' => $company_name,
                'employer_name' => $employer_name,
            ];

            $job = (new \App\Jobs\SendQueueEmail($details))
                ->delay(now()->addSeconds(1));

            dispatch($job);


            // Mail::to($getSuperAdminEmail->email)->send(new CandidateSelectionChangeStatus($mailData));


        }

        //notification code
        $notificationItem = array();
        $job = EmployerJob::where('id', $job_id)->first();
        $recevers =  User::whereHas('roles', function ($query) {
            $query->where('role_id', '=', 1)->orWhere('role_id', '=', 2);
        })->orWhere('id', '=', $candidateActiveJobStatusComment->user_id)->pluck('id')->toArray();
        $sender = User::where('id', $candidateActiveJobStatusComment->job_creator_id)->first();
        $notificationItem['sender_id'] = $sender->id;
        $notificationItem['recever_ids'] = $recevers;
        $notificationItem['title'] = "Candidate (" . $candidateName . ") Status updated in -" . $job->job_title;
        $notificationItem['type'] = "candidate_job_round_status";
        $notificationItem['message'] = "Candidate (" . $candidateName . ") Status updated in Job -" . $job->job_title . "  round-" . $status . " as " . $field_status;

        $job = (new SendQueueNotification($notificationItem))
            ->delay(now()->addSeconds(2));

        dispatch($job);

        //end notification code


        if ($data == true) {
            return response()->json(['status' => true, 'message' => 'status Updated successfully.']);
        } else {
            return response()->json(['status' => true, 'message' => 'status Update query issue.']);
        }
    }

    public function candidateSkipFieldChangeStatus(Request $request)
    {

        $candidateName = $request->input('skip_candidate_name');
        $candidateId = $request->input('skip_candidate_id');
        $job_id = $request->input('skip_job_id');
        $skip_status = $request->input('skip_status');
        $skip_field_status = $request->input('skip_field_status');

        $candidateActiveJobStatusComment = CandidateJobStatusComment::where([
            'job_id' => $job_id,
            'candidate_id' => $candidateId,
            'is_active_status' => '1'
        ])->first();

        $candidateJobStatusComment = CandidateJobStatusComment::where([
            'job_id' => $job_id,
            'candidate_id' => $candidateId,
            'status' => $skip_status
        ])->first();

        CandidateJobStatusComment::where(['job_id' => $job_id, 'candidate_id' => $candidateId])->update(['is_active_status' => '0']);

        $data = CandidateJobStatusComment::updateOrCreate(
            [
                'job_id' => $job_id,
                'candidate_id' => $candidateId,
                'status' => $skip_status
            ],
            [
                'is_active_status' => '1',
                'job_id' => $job_id,
                'candidate_id' => $candidateId,
                'applied_job_id' => $candidateActiveJobStatusComment->applied_job_id,
                'user_id' => $candidateActiveJobStatusComment->user_id,
                'job_creator_id' => $candidateActiveJobStatusComment->job_creator_id,
                'is_bod_candidate_id' => $candidateActiveJobStatusComment->is_bod_candidate_id,
                'status' => $skip_status,
                'field_status' => $skip_field_status,
                'additional_note' => isset($candidateJobStatusComment->additional_note) ? $candidateJobStatusComment->additional_note : null,
                'links' => isset($candidateJobStatusComment->links) ? $candidateJobStatusComment->links : null,
            ]
        );

        if ($data->wasRecentlyCreated || $data->isDirty()) {
            $data->touch();
        }

        if ($skip_status == 'Shortlist') {

            $details = [
                'subject' => 'Candidate Shotlist',
                'Round' => $skip_status,
                'candidateName' => $candidateName,
                'status' => $skip_status,
                'candidateId' => $candidateId,
            ];

            $job = (new \App\Jobs\SendQueueEmail($details))
                ->delay(now()->addSeconds(1));

            dispatch($job);
        }

        if ($skip_status == 'Final Selection') {


            $details = [
                'subject' => 'Candidate Selected',
                'Round' => $skip_status,
                'candidateName' => $candidateName,
                'status' => $skip_status,
                'candidateId' => $candidateId,
            ];

            $job = (new \App\Jobs\SendQueueEmail($details))
                ->delay(now()->addSeconds(1));

            dispatch($job);


            // Mail::to($getSuperAdminEmail->email)->send(new CandidateSelectionChangeStatus($mailData));


        }


        if ($data == true) {

            //notification code
            $notificationItem = array();
            $job = EmployerJob::where('id', $job_id)->first();
            $recevers =  User::whereHas('roles', function ($query) {
                $query->where('role_id', '=', 1)->orWhere('role_id', '=', 2);
            })->orWhere('id', '=', $candidateActiveJobStatusComment->user_id)->pluck('id')->toArray();
            $sender = User::where('id', $candidateActiveJobStatusComment->job_creator_id)->first();
            $notificationItem['sender_id'] = $sender->id;
            $notificationItem['recever_ids'] = $recevers;
            $notificationItem['title'] = "Candidate (" . $candidateName . ") Status updated in -" . $job->job_title;
            $notificationItem['type'] = "candidate_job_round_status";
            $notificationItem['message'] = "Candidate (" . $candidateName . ") Status updated in Job -" . $job->job_title . "  round-" . $skip_status . " as " . $skip_field_status;

            $job = (new SendQueueNotification($notificationItem))
                ->delay(now()->addSeconds(2));

            dispatch($job);

            //end notification code

            return back()->with('success', 'status Updated successfully.');
        } else {
            return back()->with('success', 'status Update query issue.');
        }
    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('job_applied_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = $request->input('id');
        AppliedJob::where('id', $id)->delete();
        return back()->with('success', 'Job Applied Deleted successfully.');
    }

    public function savedCandidate(Request $request)
    {

        // abort_if(Gate::denies('bod_candidate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = $request->input('id');
        $id = decrypt_data($id);

        $appliedjob = AppliedJob::where('id', $id)->first();

        $savedCandidateArr = array(
            'user_id' => auth()->user()->id,
            'candidate_id' => $appliedjob->candidate_id,
            'is_bod_candidate_id' => $appliedjob->is_bod_candidate_id
        );
        $candidate = SavedCandidate::create($savedCandidateArr);


        return back()->with('success', 'Candidate saved successfully.');
    }

    public function savedJobTemplate(Request $request)
    {

        // abort_if(Gate::denies('bod_candidate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = $request->input('id');
        $id = decrypt_data($id);

        $employer_job = EmployerJob::where('id', $id)->first();

        $savedTemplateArr = array(
            'user_id' => auth()->user()->id,
            'job_id' => $employer_job->id
        );
        SavedJobTemplate::create($savedTemplateArr);


        return back()->with('success', 'Job Template saved successfully.');
    }

    public function unSavedJobTemplate(Request $request)
    {

        // abort_if(Gate::denies('bod_candidate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = $request->input('id');
        $job_id = decrypt_data($id);

        $user_id = auth()->user()->id;
        SavedJobTemplate::where(['job_id' => $job_id, 'user_id' => $user_id])->delete();


        return back()->with('success', 'Job template un-saved successfully.');
    }

    public function rangeFilter(Request $request)
    {
        $radius = $request->rangeValue;
        $targetLatitude = $request->latitude;
        $targetLongitude = $request->longitude;
        $userId = auth()->user()->id;

        $roleName = getUserRole($userId);


        if (in_array($roleName, array('Super Admin', 'Admin'))) {
            if ($radius != 0) {
                $query = BodCandidate::select(
                    'name',
                    'email',
                    'location',
                    'status',
                    'candidate_id'
                )
                    ->selectRaw("( 3959 * acos( cos( radians(?) ) *
                        cos( radians( latitude ) ) *
                        cos( radians( longitude ) - radians(?) ) +
                        sin( radians(?) ) *
                        sin( radians( latitude ) ) ) ) AS distance", [$targetLatitude, $targetLongitude, $targetLatitude])
                    ->havingRaw("distance < ?", [$radius])
                    ->orderBy('distance');
            } else {
                $query = BodCandidate::select(
                    'name',
                    'email',
                    'location',
                    'status',
                    'candidate_id'
                );
            }
        } else {


            if ($radius != 0) {
                $query = Candidate::select(
                    'name',
                    'email',
                    'location',
                    'status',
                    'candidate_id'
                )
                    ->selectRaw("( 3959 * acos( cos( radians(?) ) *
                        cos( radians( latitude ) ) *
                        cos( radians( longitude ) - radians(?) ) +
                        sin( radians(?) ) *
                        sin( radians( latitude ) ) ) ) AS distance", [$targetLatitude, $targetLongitude, $targetLatitude])
                    ->havingRaw("distance < ?", [$radius])
                    ->where('user_id', $userId)
                    ->orderBy('distance');
            } else {
                $query = Candidate::select(
                    'name',
                    'email',
                    'location',
                    'status',
                    'candidate_id'
                )->where('user_id', $userId);
            }
        }

        $columns = [
            'id',
            'name',
            'email',
            'location',
            'status',
            'candidate_id'

        ];




        // Build the base query
        // $query = Post::select($columns);

        // Define the searchable columns
        $searchableColumns = [
            'id',
            'name',
            'email',
            'location',
            'status',
            'candidate_id'
        ];

        // Apply search conditions to the query
        foreach ($searchableColumns as $column) {
            if ($request->filled($column)) {
                $query->where($column, 'like', '%' . $request->input($column) . '%');
            }
        }

        // Return the DataTables response
        return DataTables::of($query)
            ->rawColumns([])
            ->make(true);
        // return DataTables::of($query)

        //     ->rawColumns([])
        //     ->make(true);
        // return response()->json(['status' => true, 'data' => $candidates->toarray()]);
    }
}
