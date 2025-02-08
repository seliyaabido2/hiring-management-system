<?php

namespace App\Http\Controllers\Admin;

use App\BodCandidate;
use App\User;
use App\RoleUser;
use App\EmployerJob;
use App\Candidate;
use App\CandidateJobStatusComment;
use App\SavedCandidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class HomeController
{
    public function index()
    {
        $adminRoleID = 2;
        $adminUserCount = User::whereHas('roles', function ($query) use ($adminRoleID) {
            $query->where('role_id', $adminRoleID);
        })->count();
        $employerRoleID = 3;
        $employerUserCount = User::whereHas('roles', function ($query) use ($employerRoleID) {
            $query->where('role_id', $employerRoleID);
        })->count();
        $recruiterRoleID = 4;
        $recruiterUserCount = User::whereHas('roles', function ($query) use ($recruiterRoleID) {
            $query->where('role_id', $recruiterRoleID);
        })->count();

        $userId =Auth::user()->id;
        $roleName = getUserRole($userId);

        $closedJobCnt = EmployerJob::where('status', 'closed')->get()->count();
        $BodCandidateCount = '';


        if($roleName == 'Super Admin' || $roleName == 'Admin'){
            $BodCandidateCount = BodCandidate::get()->count();
        }

        if($roleName == 'Employer'){
            $closedJobCnt = EmployerJob::where('status', 'closed')->where('user_id',$userId)->get()->count();
        }

        $jobPostRequestCnt = EmployerJob::get()->count();
        if($roleName == 'Employer'){

            $jobPostRequestCnt = EmployerJob::where('user_id',$userId)->get()->count();
        }
        $activeJobPostCnt = EmployerJob::where('status', 'Active')->get()->count();
        $saveCandidateCnt = 0;
        $inProcessCandidateCnt = 0;


        if($roleName == 'Employer' ){

            $activeJobPostCnt = EmployerJob::where('status', 'Active')->where('user_id',$userId)->get()->count();

            $saveCandidateCnt = SavedCandidate::where('user_id',$userId)->get()->count();
            $inProcessCandidateCnt = CandidateJobStatusComment::
            select('candidate_id')
            ->where('status', '!=', 'Final Selection')
            ->where('user_id', '=', $userId)
            ->groupBy('candidate_id')
            ->get()->count();


        }
        $deactiveJobPostCnt = EmployerJob::where('status', 'Deactive')->get()->count();
        if($roleName == 'Employer'){

            $deactiveJobPostCnt = EmployerJob::where('status', 'Deactive')->where('user_id',$userId)->get()->count();
        }

        $vacanciesCnt = EmployerJob::select('number_of_vacancies')->where('status','Active')->sum('number_of_vacancies');

        if($roleName == 'Recruiter' || $roleName == 'Employer' ){

            $vacanciesCnt = EmployerJob::select('number_of_vacancies')->where('status','Active')->where('user_id',$userId)->sum('number_of_vacancies');
        }

        $candidateCnt = CandidateJobStatusComment::where(['status' => 'Final Selection','field_status' => 'Selected'])->get()->count();
        $totelcandidateCnt = 0;
        $candidatehiredCnt = 0;
        if($roleName == 'Recruiter'){
            $candidateCnt=0;

            $candidatehiredCnt = CandidateJobStatusComment::where(['status' => 'Final Selection','field_status' => 'Selected'])->where('user_id',$userId)->get()->count();
            // dd($userId);
            $totelcandidateCnt = Candidate::where('user_id',$userId)->get()->count();
            

            $inProcessCandidateCnt = CandidateJobStatusComment::
            select('candidate_id')
            ->where('status', '!=', 'Final Selection')
            ->where('user_id', '=', $userId)
            ->groupBy('candidate_id')
            ->get()->count();
        }
        

        return view('home', compact('candidatehiredCnt','totelcandidateCnt','saveCandidateCnt','inProcessCandidateCnt','adminUserCount', 'employerUserCount', 'recruiterUserCount', 'closedJobCnt', 'jobPostRequestCnt', 'activeJobPostCnt', 'deactiveJobPostCnt','vacanciesCnt','candidateCnt','BodCandidateCount'));
    }

}
