<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\AppliedJob;
use App\Candidate;
use App\EmployerJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AppliedJobsController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $UserId = $request->user()->token()->user_id;


            $appliedJobs = DB::table('applied_jobs')
            ->join('employer_jobs', 'applied_jobs.job_id', '=', 'employer_jobs.id')
            ->join('candidates', 'applied_jobs.candidate_id', '=', 'candidates.candidate_id')
            ->select('applied_jobs.job_id','employer_jobs.job_title','employer_jobs.job_type','employer_jobs.job_start_date','employer_jobs.status','employer_jobs.job_recruiment_duration', DB::raw('COUNT(applied_jobs.candidate_id) as total_candidates'))
            ->whereNull('candidates.deleted_at')
            ->groupBy('applied_jobs.job_id')
            ->groupBy('employer_jobs.job_title')
            ->groupBy('employer_jobs.job_type')
            ->groupBy('employer_jobs.job_start_date')
            ->groupBy('employer_jobs.status')
            ->groupBy('employer_jobs.job_recruiment_duration')
            ->get();


            return response()->json(['status' => true, 'data' => $appliedJobs ]);

        }catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th]);
        }


    }

    public function show(Request $request) {
        try{
            $UserId = $request->user()->token()->user_id;


            $validation = Validator::make(

                $request->all(), [
                    'job_id' => 'required',
                ]
            );

            if ($validation->fails()) {
                return response()->json(['status' => false, 'message' => $validation->errors()->first()]);
            }

            $appliedJobCandidates = AppliedJob::with('candidate')->where('job_id',$request->input('job_id'))->get();

            return response()->json(['status' => true, 'data' => $appliedJobCandidates ]);

        }catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th]);
        }

    }
}
