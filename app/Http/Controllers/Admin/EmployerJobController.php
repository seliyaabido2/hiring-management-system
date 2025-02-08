<?php

namespace App\Http\Controllers\Admin;

use App\AppliedJob;
use App\BodCandidate;
use App\Candidate;
use App\CandidateJobStatusComment;
use App\Employee;
use App\SavedJobTemplate;

// use App\Employee;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyEmployeeRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Service;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\EmployerJob;
use App\City;
use App\State;
use App\Country;
use App\Jobs\SendQueueNotification;
use App\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class EmployerJobController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request, $jobStatus)
    {
        abort_if(Gate::denies('job_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $EmployerJob = EmployerJob::select('id', 'user_id', 'job_title', 'job_recruiment_duration', 'job_start_date', 'job_type', 'status', 'location')->where('status', 'Active')->OrderBy('created_at', 'desc')->get();

        return view('admin.employerJobs.index', compact('EmployerJob'));
    }

    public function mySavedJobTemplates()
    {

        if (Gate::denies('bod_saved_template_access') && Gate::denies('saved_template_access')) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }
        // abort_if(Gate::denies('bod_saved_template_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userId = Auth::user()->id;
        $roleName = getUserRole($userId);
        $EmployerJob = SavedJobTemplate::where('user_id', $userId)->with('employerJob')->OrderBy('created_at', 'desc')->get();
        // dd($EmployerJob);
        return view('admin.employerJobs.mySavedJobTemplates', compact('EmployerJob'));
    }

    public function alljobs(Request $request)
    {

        $allJobs = EmployerJob::select('id', 'user_id', 'job_title', 'status')->where('user_id', $request->userId)->withCount('AppliedJosCandidates')->get();

        return view('admin.employerJobs.allJobs', compact('allJobs'));
    }

    public function hiredJobsCandidates(Request $request)
    {

        $hiredJobsCandidates = EmployerJob::select('id', 'user_id', 'job_title', 'status')->where('user_id', $request->userId)->withCount('hiredCandidates')->OrderBy('created_at', 'desc')->get();
        return view('admin.employer.hiredJobsCandidates', compact('hiredJobsCandidates'));
    }

    public function hiredCandidates(Request $request)
    {


        $JobId = $request->jobId;
        $userId = $request->userId;
        $roleName = getUserRole($userId);




        $appliedJobCandidates = AppliedJob::with(['assessment_links'])
            ->where('job_id', $JobId)
            ->where('job_creator_id', $userId)
            ->whereHas('SelectedCandidates', function ($query) use ($JobId) {
                $query->where(['status' => 'Final Selection', 'field_status' => 'Selected', 'job_id' => $JobId]);
            })
            ->get()
            ->map(function ($appliedJob) {
                if ($appliedJob->is_bod_candidate_id == 1) {
                    $appliedJob->setRelation('candidate', $appliedJob->bod_candidate);
                } else {
                    $appliedJob->setRelation('candidate', $appliedJob->candidate);
                }
                return $appliedJob;
            });




        $getJobDetail = EmployerJob::find($JobId)->first();


        return view('admin.employer.hiredCandidates', compact('appliedJobCandidates', 'getJobDetail'));

        // $hiredCandidates = EmployerJob::select('id','user_id','job_title','status')->where('id',$request->jobId)->withCount('hiredCandidates')->get();
        // // dd($hiredCandidates);
        // return view('admin.employer.hiredCandidates',compact('hiredCandidates'));

    }

    public function activeJobs()
    {
        abort_if(Gate::denies('job_active'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userId = Auth::user()->id;
        $roleName = getUserRole($userId);


        if (in_array($roleName, array('Super Admin', 'Admin', 'Recruiter'))) {

            $EmployerJob = EmployerJob::select('id', 'user_id', 'job_title', 'job_recruiment_duration', 'job_start_date', 'job_type', 'status', 'location')->where('status', 'Active')->with('savedJobTemplate')->OrderBy('created_at', 'desc')->get();
        } else {

            $EmployerJob = EmployerJob::select('id', 'user_id', 'job_title', 'job_recruiment_duration', 'job_start_date', 'job_type', 'status', 'location')->where(['status' => 'Active', 'user_id' => $userId])->with('savedJobTemplate')->OrderBy('created_at', 'desc')->get();
        }

        return view('admin.employerJobs.activeJobs', compact('EmployerJob'));
    }

    public function DeActiveJobs()
    {
        abort_if(Gate::denies('job_deactive'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userId = Auth::user()->id;
        $roleName = getUserRole($userId);
        if (in_array($roleName, array('Super Admin', 'Admin', 'Recruiter'))) {
            $EmployerJob = EmployerJob::select('id', 'user_id', 'job_title', 'job_recruiment_duration', 'job_start_date', 'job_type', 'status', 'location')->where('status', 'DeActive')->OrderBy('created_at', 'desc')->get();
        } else {
            $EmployerJob = EmployerJob::select('id', 'user_id', 'job_title', 'job_recruiment_duration', 'job_start_date', 'job_type', 'status', 'location')->where('status', 'DeActive')->where('user_id', $userId)->OrderBy('created_at', 'desc')->get();
        }


        return view('admin.employerJobs.DeActiveJobs', compact('EmployerJob'));
    }

    public function closedJobs()
    {
        abort_if(Gate::denies('job_closed'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $userId = Auth::user()->id;
        $roleName = getUserRole($userId);
        if (in_array($roleName, array('Super Admin', 'Admin', 'Recruiter'))) {
            $EmployerJob = EmployerJob::select('id', 'user_id', 'job_title', 'job_recruiment_duration', 'job_start_date', 'job_type', 'status', 'location')->where('status', 'Closed')->OrderBy('created_at', 'desc')->get();
        } else {
            $EmployerJob = EmployerJob::select('id', 'user_id', 'job_title', 'job_recruiment_duration', 'job_start_date', 'job_type', 'status', 'location')->where('status', 'Closed')->where('user_id', $userId)->OrderBy('created_at', 'desc')->get();
        }

        return view('admin.employerJobs.closedJobs', compact('EmployerJob'));
    }

    public function requestJobs(Request $request)
    {
        abort_if(Gate::denies('job_pending'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $currentDate = Carbon::now();
        $currentTimestamp = $currentDate->format('Y-m-d');
        $userId = Auth::user()->id;
        $roleName = getUserRole($userId);
        if (in_array($roleName, array('Super Admin', 'Admin', 'Recruiter'))) {
            $EmployerJob = EmployerJob::select('id', 'user_id', 'job_title', 'job_recruiment_duration', 'job_start_date', 'job_type', 'status', 'location')->where('job_start_date', '>', $currentTimestamp)->OrderBy('created_at', 'desc')->get();
        } else {
            $EmployerJob = EmployerJob::select('id', 'user_id', 'job_title', 'job_recruiment_duration', 'job_start_date', 'job_type', 'status', 'location')->where('job_start_date', '>', $currentTimestamp)->where('user_id', $userId)->OrderBy('created_at', 'desc')->get();
        }
        return view('admin.employerJobs.jobRequest', compact('EmployerJob'));
    }

    public function chageJobRequestStatus($jobId, $status)
    {

        $data = EmployerJob::where('id', $jobId)->update(['status' => $status]);

        return redirect()->back()->with('success', 'status updated successfully.');
    }

    public function myjob(Request $request)
    {
        abort_if(Gate::denies('my_job_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userId = auth()->user()->id;

        $EmployerJob = EmployerJob::select('id', 'user_id', 'job_title', 'job_recruiment_duration', 'job_start_date', 'job_type', 'status', 'location')->where('id', $userId)->OrderBy('created_at', 'desc')->get();

        return view('admin.employerJobs.index', compact('EmployerJob'));
    }

    public function createJob()
    {
        abort_if(Gate::denies('job_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $userId = Auth::user()->id;
        $roleName = getUserRole($userId);
        $savedJobTemplates = SavedJobTemplate::where('user_id', $userId)->with('employerJob')->OrderBy('created_at', 'desc')->get();

        return view('admin.employerJobs.createJob', compact('savedJobTemplates'));
    }

    public function create()
    {
        abort_if(Gate::denies('job_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.employerJobs.create');
    }

    public function createExitJob(Request $request)
    {


        abort_if(Gate::denies('job_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = decrypt_data($request->job);
        $employerjob = EmployerJob::find($id);
        return view('admin.employerJobs.createExitJob', compact('employerjob'));
    }

    public function store(Request $request)
    {

        abort_if(Gate::denies('job_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $UserId = Auth::user()->id;

        $currentDate = Carbon::now();
        $currentTimestamp = $currentDate->format('Y-m-d');

        $job_start_date = date("Y-m-d ", strtotime($request->input('job_start_date')));

        $firstDate = new DateTime($currentTimestamp);
        $secondDate = new DateTime($job_start_date);
        $redirectPath = '';

        if ($firstDate == $secondDate) {

            $status = 'Active';
            $redirectPath = 'admin.employerJobs.activeJobs';
        } else {

            $status = 'Hold';
            $redirectPath = 'admin.employerJobs.requestJobs';
        }

        $inserDataArr = [
            'user_id' => $UserId,
            'job_title' => $request->input('job_title'),
            'job_type' => $request->input('job_type'),
            'job_role' => $request->input('job_role'),
            'location' => $request->input('location'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'number_of_vacancies' => $request->input('number_of_vacancies'),
            'job_shift' => $request->input('job_shift'),
            'job_description' => $request->input('job_description'),
            'total_number_of_working_days' => $request->input('total_number_of_working_days'),
            'any_other_langauge' => $request->input('any_other_langauge'),
            'job_start_date' => date("Y-m-d ", strtotime($request->input('job_start_date'))),
            'job_recruiment_duration' => $request->input('job_recruiment_duration'),
            'experience_sf' => $request->input('experience_sf'),
            'license_candidate_basic_training' => $request->input('license_candidate_basic_training'),
            'minimum_pay_per_hour' => $request->input('minimum_pay_per_hour'),
            'maximum_pay_per_hour' => $request->input('maximum_pay_per_hour'),
            'status' => $status,
        ];


        if ($request->has('license_candidate_banking_finance')) {
            $inserDataArr['license_candidate_banking_finance'] = $request->input('license_candidate_banking_finance');
        }

        if ($request->has('job_benefits')) {
            $inserDataArr['job_benefits'] = $request->input('job_benefits');
        }
        if ($request->has('additional_information')) {
            $inserDataArr['additional_information'] = $request->input('additional_information');
        }

        if ($request->has('license_candidate_banking_finance')) {
            $updateDataArr['license_candidate_banking_finance'] = $request->input('license_candidate_banking_finance');
        }



        if ($request->has('additional_information')) {
            $inserDataArr['additional_information'] = $request->input('additional_information');
        }

        if ($request->input('experience_sf') == 'No') {
            $inserDataArr['license_requirement'] = null;
            $inserDataArr['how_many_years_of_experience'] = null;
        } else {
            $inserDataArr['license_requirement'] = $request->input('license_requirement');
            $inserDataArr['how_many_years_of_experience'] = $request->input('how_many_years_of_experience');
        }

        if ($request->input('any_other_langauge') != 'Other') {
            $inserDataArr['other_any_other_langauge'] = null;
        } else {
            $inserDataArr['other_any_other_langauge'] = $request->input('other_any_other_langauge');
        }


        $data = EmployerJob::create($inserDataArr);

        //notification code
        $notificationItem = array();
        $recruiters =  User::whereHas('roles', function ($query) {
            $query->where('role_id', '!=', 3);
        })->where('id', '!=', $UserId)->pluck('id')->toArray();
        $EmployerName = Auth::user()->first_name;

        $notificationItem['sender_id'] = $UserId;
        $notificationItem['recever_ids'] = $recruiters;
        $notificationItem['title'] = "Posted new job! -" . $request->input('job_title');
        $notificationItem['type'] = "job_create";
        $notificationItem['message'] = $EmployerName . " posted New Job - " . $request->input('job_title') . " - " . $request->input('number_of_vacancies') . " Vacancy";

        $job = (new SendQueueNotification($notificationItem))
            ->delay(now()->addSeconds(2));

        dispatch($job);

        //end notification code

        return redirect()->route($redirectPath)->with('success', 'Job created successfully.');
    }

    public function edit($id)
    {

        abort_if(Gate::denies('job_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = decrypt_data($id);
        $employerjob = EmployerJob::find($id);
        return view('admin.employerJobs.edit', compact('employerjob'));
    }

    public function update(Request $request)
    {
        abort_if(Gate::denies('job_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $currentDate = Carbon::now();
        $currentTimestamp = $currentDate->format('Y-m-d');

        $job_start_date = date("Y-m-d ", strtotime($request->input('job_start_date')));

        $firstDate = new DateTime($currentTimestamp);
        $secondDate = new DateTime($job_start_date);
        $job_recruitment_duration = $request->input('job_recruiment_duration');
        $thirdDate = Carbon::parse($job_start_date)->addDays($job_recruitment_duration);
        // dd($secondDate);
        $redirectPath =  '';
        $status = $request->input('status');

        if ($status != 'Deactive') {

            if (($firstDate >= $secondDate) &&  $thirdDate->greaterThan($firstDate)) {

                $status = 'Active';
                $redirectPath = 'admin.employerJobs.activeJobs';
            } elseif ($thirdDate->lessThan($firstDate)) {
                $status = 'Closed';
                $redirectPath = 'admin.employerJobs.closedJobs';
            } else {

                $status = 'Hold';
                $redirectPath = 'admin.employerJobs.requestJobs';
            }
        }


        if ($request->input('status') == 'Deactive') {
            $redirectPath = 'admin.employerJobs.DeActiveJobs';
        }





        $updateDataArr = [
            'job_title' => $request->input('job_title'),
            'job_type' => $request->input('job_type'),
            'job_role' => $request->input('job_role'),
            'location' => $request->input('location'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'number_of_vacancies' => $request->input('number_of_vacancies'),
            'job_shift' => $request->input('job_shift'),
            'job_description' => $request->input('job_description'),
            'total_number_of_working_days' => $request->input('total_number_of_working_days'),
            'any_other_langauge' => $request->input('any_other_langauge'),
            'job_start_date' => date("Y-m-d ", strtotime($request->input('job_start_date'))),
            'job_recruiment_duration' => $request->input('job_recruiment_duration'),
            'experience_sf' => $request->input('experience_sf'),

            'license_candidate_basic_training' => $request->input('license_candidate_basic_training'),
            'minimum_pay_per_hour' => $request->input('minimum_pay_per_hour'),
            'maximum_pay_per_hour' => $request->input('maximum_pay_per_hour'),
            'status' => $status,
        ];

        if ($request->has('license_candidate_banking_finance')) {
            $updateDataArr['license_candidate_banking_finance'] = $request->input('license_candidate_banking_finance');
        }

        if ($request->has('job_benefits')) {
            $updateDataArr['job_benefits'] = $request->input('job_benefits');
        }

        if ($request->has('additional_information')) {
            $updateDataArr['additional_information'] = $request->input('additional_information');
        }

        if ($request->input('experience_sf') == 'No') {
            $updateDataArr['license_requirement'] = null;
            $updateDataArr['how_many_years_of_experience'] = null;
        } else {
            $updateDataArr['license_requirement'] = $request->input('license_requirement');
            $updateDataArr['how_many_years_of_experience'] = $request->input('how_many_years_of_experience');
        }

        if ($request->input('any_other_langauge') != 'Other') {
            $updateDataArr['other_any_other_langauge'] = null;
        } else {
            $updateDataArr['other_any_other_langauge'] = $request->input('other_any_other_langauge');
        }

        $userId = Auth::user()->id;

        $roleName = getUserRole($userId);
        if (in_array($roleName, array('Super Admin', 'Admin'))) {
            $statusarr = array('status' => $status);
            $updateDataArr =  array_merge($updateDataArr, $statusarr);
        }

        $empolyer_job_id = decrypt_data($request->input('empolyer_job_id'));

        EmployerJob::where('id', $empolyer_job_id)->update($updateDataArr);


        //notification code
        $notificationItem = array();
        $recruiters =  User::whereHas('roles', function ($query) {
            $query->where('role_id', '!=', 3);
        })->where('id', '!=', $userId)->pluck('id')->toArray();
        $EmployerName = Auth::user()->first_name;

        $notificationItem['sender_id'] = $userId;
        $notificationItem['recever_ids'] = $recruiters;
        $notificationItem['title'] = "update job! -" . $request->input('job_title');
        $notificationItem['type'] = "job_update";
        $notificationItem['message'] = $EmployerName . " Update Job - " . $request->input('job_title') . " - " . $request->input('number_of_vacancies') . " Vacancy";

        $job = (new SendQueueNotification($notificationItem))
            ->delay(now()->addSeconds(2));

        dispatch($job);

        //end notification code
        // dd($redirectPath);
        return redirect()->route($redirectPath)->with('success', 'Job updated successfully.');

        // return redirect('admin/employerJobs/status/activeJobs')->with('success', 'Job updated successfully.');
    }

    public function show($id)
    {

        abort_if(Gate::denies('job_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = decrypt_data($id);
        $employerJobs = EmployerJob::find($id);

        return view('admin.employerJobs.show', compact('employerJobs'));
    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('job_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = $request->input('id');
        $id = decrypt_data($id);
        EmployerJob::find($id)->delete();


        return back()->with('success', 'Job deleted successfully');
    }

    public function massDestroy(MassDestroyEmployeeRequest $request)
    {

        abort_if(Gate::denies('job_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        EmployerJob::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function bulkApplyJob()
    {
        if (Gate::allows('job_applied_create') || Gate::allows('bod_job_applied_create')) {

            return view('admin.employerJobs.bulkApplyJob');
        } else {

            throw new AuthorizationException('You are not authorized to apply for this job.');
        }
    }

    public function applyJob($id)
    {
        if (Gate::allows('job_applied_create') || Gate::allows('bod_job_applied_create')) {


            $id = decrypt_data($id);

            $employerJobs = EmployerJob::find($id);

            if ($employerJobs == null) {
                return back();
            }

            $country = Country::all();

            $userId = Auth::user()->id;
            $roleName = getUserRole($userId);


            if (in_array($roleName, array('Super Admin', 'Admin'))) {

                $candidates = BodCandidate::where('status', 'Active')->whereDoesntHave('appliedJobs', function ($query) use ($id) {
                    $query->where('job_id', $id);
                })->get();
            } else {

                $candidates = Candidate::where('user_id', auth()->user()->id)->where('status', 'Active')->whereDoesntHave('appliedJobs', function ($query) use ($id) {
                    $query->where('job_id', $id);
                })->get();
            }

            return view('admin.employerJobs.applyJob', compact('employerJobs', 'country', 'candidates'));
        } else {

            throw new AuthorizationException('You are not authorized to apply for this job.');
        }

        // abort_if(Gate::denies('bod_job_applied_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


    }

    public function candidateDatatabes(Request $request)
    {

        $radius = $request->rangeValue;
        $targetLatitude = $request->latitude;
        $targetLongitude = $request->longitude;
        $userId = auth()->user()->id;

        if (isset($request->jobId) && !empty($request->jobId)) {
            $jobArr[] = decrypt_data($request->jobId);
        }
        if (isset($request->jobIds) && !empty($request->jobIds)) {
            $jobArr = explode(',', $request->jobIds);
        }


        $roleName = getUserRole($userId);

        $columns = [
            'id',
            'name',
            'email',
            'experience_sf',
            'location',
            'status',
            'candidate_id'

        ];

        $userId = Auth::user()->id;
        $roleName = getUserRole($userId);



        if ($radius > 0) {
            if (in_array($roleName, array('Super Admin', 'Admin'))) {
                if ($radius != 0) {
                    $query = BodCandidate::select(
                        $columns
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
                        $columns
                    );
                }
            } else {

                // $item = $query->get()->take(5);

                if ($radius != 0) {
                    $query = Candidate::select(
                        $columns
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
                        $columns
                    )->where('user_id', $userId);
                }
            }
        } else {

            if (in_array($roleName, array('Super Admin', 'Admin'))) {

                $query = BodCandidate::select($columns)->where('status', 'Active');
            } else {

                $query = Candidate::select($columns)->where('user_id', auth()->user()->id)->where('status', 'Active');
            }
        }
        if (!empty($jobArr)) {
            $query->whereDoesntHave('appliedJobs', function ($query) use ($jobArr) {
                $query->whereIn('job_id', $jobArr);
            });
        }




        // Build the base query
        // $query = Post::select($columns);

        // Define the searchable columns
        $searchableColumns = [
            'id',
            'name',
            'email',
            'experience_sf',
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
    }

    public function ActiveJobDatatable(Request $request)
    {
        $radius = $request->rangeValue;
        $targetLatitude = $request->latitude;
        $targetLongitude = $request->longitude;

        $userId = Auth::user()->id;
        $roleName = getUserRole($userId);

        $query = EmployerJob::select(
            'employer_jobs.id',
            'employer_jobs.user_id',
            'employer_jobs.job_title',
            DB::raw("CONCAT(employer_jobs.job_recruiment_duration, ' Days') as job_recruiment_duration"),

            DB::raw("DATE_FORMAT(employer_jobs.job_start_date, '%d-%m-%Y') as job_start_date"),
            'employer_jobs.job_type',
            'employer_jobs.status',
            'employer_jobs.location',
            'users.first_name as first_name'
        )

            ->addSelect(DB::raw("DATE_FORMAT(DATE_ADD(employer_jobs.job_start_date, INTERVAL employer_jobs.job_recruiment_duration DAY), '%d-%m-%Y') as calculated_end_date"))
            ->leftJoin('users', 'employer_jobs.user_id', '=', 'users.id')
            ->where('employer_jobs.status', 'Active')
            ->with('savedJobTemplate')
            ->orderBy('employer_jobs.created_at', 'desc');

        if ($roleName !== 'Super Admin' && $roleName !== 'Admin' && $roleName !== 'Recruiter') {
            $query->where(['employer_jobs.status' => 'Active', 'employer_jobs.user_id' => $userId]);
        }

        if ($radius > 0) {

            $query->selectRaw("( 3959 * acos( cos( radians(?) ) *
                    cos( radians( latitude ) ) *
                    cos( radians( longitude ) - radians(?) ) +
                    sin( radians(?) ) *
                    sin( radians( latitude ) ) ) ) AS distance", [$targetLatitude, $targetLongitude, $targetLatitude])
                ->havingRaw("distance < ?", [$radius])
                ->orderBy('distance');
        }

        // Define the searchable columns
        $searchableColumns = [
            'id',
            'user_id',
            'job_title',
            'job_recruiment_duration',
            'job_start_date',
            'job_type',
            'status',
            'location',
            'first_name', // Add first_name to searchable columns
            'calculated_end_date', // Add calculated_end_date to searchable columns
        ];

        // Apply search conditions to the query
        foreach ($searchableColumns as $column) {
            if ($request->filled($column)) {
                $query->where(function ($query) use ($column, $request) {
                    $query->where('employer_jobs.' . $column, 'like', '%' . $request->input($column) . '%')
                        ->orWhere('users.first_name', 'like', '%' . $request->input($column) . '%')
                        ->orWhere('calculated_end_date', 'like', '%' . $request->input($column) . '%');
                });
            }
        }

        // Return the DataTables response
        return DataTables::of($query)
            ->rawColumns([])
            ->make(true);
    }




    // public function ActiveJobDatatable(Request $request)
    // {
    //     $userId = Auth::user()->id;
    //     $roleName = getUserRole($userId);

    //     $query = EmployerJob::select(
    //         'employer_jobs.id',
    //         'employer_jobs.user_id',
    //         'employer_jobs.job_title',
    //         DB::raw("CONCAT(employer_jobs.job_recruiment_duration, ' Days') as job_recruiment_duration"),

    //         DB::raw("DATE_FORMAT(employer_jobs.job_start_date, '%d-%m-%Y') as job_start_date"),
    //         'employer_jobs.job_type',
    //         'employer_jobs.status',
    //         'employer_jobs.location',
    //         'users.first_name as first_name'
    //     )

    //         ->addSelect(DB::raw("DATE_FORMAT(DATE_ADD(employer_jobs.job_start_date, INTERVAL employer_jobs.job_recruiment_duration DAY), '%d-%m-%Y') as calculated_end_date"))
    //         ->leftJoin('users', 'employer_jobs.user_id', '=', 'users.id')
    //         ->where('employer_jobs.status', 'Active')
    //         ->with('savedJobTemplate')
    //         ->orderBy('employer_jobs.created_at', 'desc');

    //     if ($roleName !== 'Super Admin' && $roleName !== 'Admin' && $roleName !== 'Recruiter') {
    //         $query->where(['employer_jobs.status' => 'Active', 'employer_jobs.user_id' => $userId]);
    //     }

    //     // Define the searchable columns
    //     $searchableColumns = [
    //         'id',
    //         'user_id',
    //         'job_title',
    //         'job_recruiment_duration',
    //         'job_start_date',
    //         'job_type',
    //         'status',
    //         'location',
    //         'first_name', // Add first_name to searchable columns
    //         'calculated_end_date', // Add calculated_end_date to searchable columns
    //     ];

    //     // Apply search conditions to the query
    //     foreach ($searchableColumns as $column) {
    //         if ($request->filled($column)) {
    //             $query->where(function ($query) use ($column, $request) {
    //                 $query->where('employer_jobs.' . $column, 'like', '%' . $request->input($column) . '%')
    //                     ->orWhere('users.first_name', 'like', '%' . $request->input($column) . '%')
    //                     ->orWhere('calculated_end_date', 'like', '%' . $request->input($column) . '%');
    //             });
    //         }
    //     }

    //     // Return the DataTables response
    //     return DataTables::of($query)
    //         ->rawColumns([])
    //         ->make(true);
    // }





    // public function ActiveJobDatatable(Request $request)
    // {
    //     $userId = Auth::user()->id;
    //     $roleName = getUserRole($userId);

    //     $query = EmployerJob::select('employer_jobs.id', 'employer_jobs.user_id', 'employer_jobs.job_title', 'employer_jobs.job_recruiment_duration', 'employer_jobs.job_start_date', 'employer_jobs.job_type', 'employer_jobs.status', 'employer_jobs.location', 'users.first_name as first_name')
    //         ->leftJoin('users', 'employer_jobs.user_id', '=', 'users.id')
    //         ->where('employer_jobs.status', 'Active')
    //         ->with('savedJobTemplate')
    //         ->orderBy('employer_jobs.created_at', 'desc');

    //     if ($roleName !== 'Super Admin' && $roleName !== 'Admin' && $roleName !== 'Recruiter') {
    //         $query->where(['employer_jobs.status' => 'Active', 'employer_jobs.user_id' => $userId]);
    //     }

    //     // Define the searchable columns
    //     $searchableColumns = [
    //         'id',
    //         'user_id',
    //         'job_title',
    //         'job_recruiment_duration',
    //         'job_start_date',
    //         'job_type',
    //         'status',
    //         'location',
    //         'first_name' // Add user_name to searchable columns
    //     ];

    //     // Apply search conditions to the query
    //     foreach ($searchableColumns as $column) {
    //         if ($request->filled($column)) {
    //             $query->where(function ($query) use ($column, $request) {
    //                 $query->where('employer_jobs.' . $column, 'like', '%' . $request->input($column) . '%')
    //                     ->orWhere('users.first_name', 'like', '%' . $request->input($column) . '%');
    //             });
    //         }
    //     }

    //     // Return the DataTables response
    //     return DataTables::of($query)
    //         ->rawColumns([])
    //         ->make(true);
    // }


    public function checkUniqueEmailOrContact(Request $request)
    {


        $email = $request->email;
        $contact_number = $request->contact_number;

        if ($request->ajax()) {

            $userId = Auth::user()->id;
            $roleName = getUserRole($userId);

            if ($roleName == 'Super Admin' || $roleName == 'Admin') {
                $user = BodCandidate::select('id', 'name', 'date_of_birth', 'email', 'contact_no', 'created_at')->where('email', $email)->Orwhere('contact_no', $contact_number)->get();
            } else {
                $user = Candidate::select('id', 'name', 'date_of_birth', 'email', 'contact_no', 'created_at')->where('email', $email)->Orwhere('contact_no', $contact_number)->get();
            }

            if (count($user) > 0) {

                return json_encode([
                    'status' => true,
                    'data' => $user
                ]);
            } else {
                return json_encode([
                    'status' => false
                ]);
            }
        }
    }

    public function postApplyJob(Request $request)
    {


        if (Gate::allows('job_applied_create') || Gate::allows('bod_job_applied_create')) {

            $UserId = auth()->user()->id;
            $roleName = getUserRole($UserId);


            $this->validate($request, [
                'job_id' => 'required',
                'new_candidate_post' => 'required'
            ]);
            // dd($request->all());
            $job_id  = decrypt_data($request->input('job_id'));
            $job_title = EmployerJob::where('id', $job_id)->first()->job_title;

            if ($request->input('new_candidate_post') == 'Yes') {

                $newCandidateValidation = [
                    'candidate_name' => 'required',
                    'email' => 'required',
                    'contact_number' => 'required',
                    'location' => 'required',
                    'experience_sf' => 'required',
                    'license_requirement' => 'required',
                    'any_other_langauge' => 'required'
                ];

                if ($request->input('any_other_langauge') == 'Other') {

                    $arr = array('other_any_other_langauge' => 'required');
                    $newCandidateValidation =  array_merge($newCandidateValidation, $arr);
                }

                if ($roleName == 'Admin' || $roleName == 'Super Admin') {
                    $newCandidateValidation['email'] = 'required';
                } else {
                    $newCandidateValidation['email'] = 'required';
                }

                $this->validate($request, $newCandidateValidation);


                $candidate_id = generateUniqueCandidateId();

                // create slug
                $email = $request->input('email');
                $contact_number = $request->input('contact_number');
                $slugItem = trim($email) . trim($contact_number);

                $candidate_job_slug = createSlug($slugItem);

                // candidate slug check code comment

                $checkCandidateSlugIsExist = checkCandidateSlugIsExist($candidate_job_slug);

                if ($checkCandidateSlugIsExist == false) {

                    return back()->with('error', 'this candidate is Already register and also aplied for this job!!');
                }

                $candidateDataArr = [

                    'user_id' => $UserId,
                    'name' => $request->input('candidate_name'),
                    'email' => $request->input('email'),
                    'contact_no' => $request->input('contact_number'),
                    'job_preference' => $request->input('job_preference'),
                    'job_type' => $request->input('job_type'),
                    'location' => $request->input('location'),
                    'latitude' => $request->input('latitude'),
                    'longitude' => $request->input('longitude'),
                    'experience_sf' => $request->input('experience_sf'),
                    'license_candidate_basic_training' => $request->input('license_candidate_basic_training'),
                    'any_other_langauge' => $request->input('any_other_langauge'),
                    'expected_pay_per_hour' => $request->input('expected_pay_per_hour'),
                    'current_pay_per_hour' => $request->input('current_pay_per_hour'),
                    'resume' => $request->input('resume'),
                    'additional_information' => $request->input('additional_information'),
                    'candidate_job_slug' => $candidate_job_slug,
                ];

                if (empty($request->overwrite_candidate_id)) {
                    $candidateDataArr['candidate_id'] = $candidate_id;
                }

                if ($request->input('date_of_birth') != null) {
                    $candidateDataArr['date_of_birth'] = date('Y-m-d', strtotime($request->input('date_of_birth')));
                }

                if ($request->has('gender')) {
                    $candidateDataArr['gender'] = $request->input('gender');
                }

                if ($request->input('experience_sf') == 'Yes') {
                    $candidateDataArr['license_requirement'] = $request->input('license_requirement');
                    $candidateDataArr['how_many_experience'] = $request->input('how_many_experience');
                    $candidateDataArr['presently_working_in_sf'] = $request->input('presently_working_in_sf');

                    if ($request->input('presently_working_in_sf') == 'No') {
                        $candidateDataArr['last_month_year_in_sf'] = $request->input('last_month_year_in_sf');
                    }
                }

                if ($request->input('any_other_langauge') == 'Other') {

                    $candidateDataArr['other_any_other_langauge'] = $request->input('other_any_other_langauge');
                }

                if ($request->has('license_candidate_banking_finance')) {

                    $candidateDataArr['license_candidate_banking_finance'] = $request->input('license_candidate_banking_finance');
                }

                if ($request->has('reference_check')) {

                    $candidateDataArr['reference_check'] = $request->input('reference_check');
                }

                $resume = $request->file('resume');


                if (!empty($resume)) {

                    $resumeName = time() . '.' . $resume->getClientOriginalExtension();
                    $resume->move(public_path('candidate_resume'), $resumeName);
                    $arrNew = array('resume' => $resumeName);
                    $candidateDataArr =  array_merge($candidateDataArr, $arrNew);
                }



                if (in_array($roleName, array('Super Admin', 'Admin'))) {
                    $is_bod_candidate_id = '1';
                    if (!empty($request->overwrite_candidate_id)) {

                        $updatecandidate = BodCandidate::where('id', $request->overwrite_candidate_id)->update($candidateDataArr);

                        $candidate = BodCandidate::find($request->overwrite_candidate_id);
                    } else {
                        $candidate = BodCandidate::create($candidateDataArr);
                    }
                } else {
                    $is_bod_candidate_id = '0';

                    if (!empty($request->overwrite_candidate_id)) {

                        $updatecandidate = Candidate::where('id', $request->overwrite_candidate_id)->update($candidateDataArr);

                        $candidate = Candidate::find($request->overwrite_candidate_id);
                    } else {
                        $candidate = Candidate::create($candidateDataArr);
                    }
                }

                $job_creator_id = EmployerJob::where('id', $job_id)->first()->user_id;

                $appliedJobsinsertDataArr = [
                    'job_id' => $job_id,
                    'candidate_id' => $candidate->candidate_id,
                    'job_creator_id' => $job_creator_id,
                    'user_id' => $UserId,
                    'is_bod_candidate_id' => $is_bod_candidate_id,
                    'candidate_job_slug' => $candidate_job_slug
                ];


                $AppliedJob = AppliedJob::create($appliedJobsinsertDataArr);

                $candidateDataArr = array(
                    'job_id' => decrypt_data($request->input('job_id')),
                    'candidate_id' => $candidate->candidate_id,
                    'status' => 'None'
                );

                $data = CandidateJobStatusComment::updateOrCreate(
                    [
                        'job_id' => decrypt_data($request->input('job_id')),
                        'candidate_id' => $candidate->candidate_id,
                        'applied_job_id' => $AppliedJob->id,
                        'user_id' => $AppliedJob->user_id,
                        'job_creator_id' => $AppliedJob->job_creator_id,
                        'status' => 'None',
                        'field_status' => 'Selected',
                        'is_bod_candidate_id' => $is_bod_candidate_id,
                        'is_active_status' => '1'
                    ],
                    $candidateDataArr
                );


                if ($candidate == '') {
                    return redirect()->back()->with('error', 'somthing went wrong in candidate record insert table!');
                } else if ($data == '') {
                    return redirect()->back()->with('error', 'somthing went wrong in apply job insert table!');
                } else {

                    //notification code
                    $notificationItem = array();
                    $employers =  User::whereHas('roles', function ($query) {
                        $query->where('role_id', '=', 1)->orWhere('role_id', '=', 2);
                    })->orWhere('id', '=', $AppliedJob->job_creator_id)->pluck('id')->toArray();

                    $recruiter = User::where('id', $UserId)->first();
                    $notificationItem['sender_id'] = $AppliedJob->job_creator_id;
                    $notificationItem['recever_ids'] = $employers;
                    $notificationItem['title'] = "Recruitment Partner has applied in (" . $job_title . ") job";
                    $notificationItem['type'] = "job_applied";
                    $notificationItem['message'] = "Recruitment Partner (" . $recruiter->first_name . ") has applied in (" . $job_title . ") job";

                    $job = (new SendQueueNotification($notificationItem))
                        ->delay(now()->addSeconds(2));

                    dispatch($job);

                    //end notification code

                    return redirect()->back()->with('success', 'Job Applied successfully');
                }
            } else { //when new_candidate is no

                $this->validate($request, [
                    'candidate' => 'required'
                ]);

                $candidateId = $request->input('candidate');
                $candidateId = explode(",", $candidateId);
                $check = checkCandidateIdIsExist($candidateId);

                if ($check == false) {
                    return redirect()->back()->with('error', 'candidate id Not Found!!');
                }

                // candidate slug check code comment

                $checkCandidateArrSlugIsExist = checkCandidateArrSlugIsExist($candidateId, $job_id);
                if ($checkCandidateArrSlugIsExist['status'] != true) {


                    $msg = 'The name of [' . $checkCandidateArrSlugIsExist['msg'] . '] has already applied for this candidate (By Manual)!';

                    $request->session()->flash('checkCandidateArrSlugIsExist', $msg);

                    $request->session()->flash('selectedCanidateData', array_values($candidateId));

                    return back();
                }

                $appliedJobsinsertDataArr = array();
                $currentTimestamp = Carbon::now();

                $job_id  = decrypt_data($request->input('job_id'));
                $job_creator_id = EmployerJob::where('id', $job_id)->first()->user_id;


                if (in_array($roleName, array('Super Admin', 'Admin'))) {
                    $is_bod_candidate_id = '1';
                } else {
                    $is_bod_candidate_id = '0';
                }
                foreach ($candidateId as $cid) {

                    $candidateData = getCandidateByCandidateId($cid);

                    $appliedJobsinsertDataArr = [
                        'job_id' => decrypt_data($request->input('job_id')),
                        'candidate_id' => $cid,
                        'user_id' => $UserId,
                        'job_creator_id' => $job_creator_id,
                        'is_bod_candidate_id' => $is_bod_candidate_id,
                        'candidate_job_slug' => $candidateData->candidate_job_slug,
                        'created_at' => $currentTimestamp,
                        'updated_at' => $currentTimestamp,
                    ];


                    $AppliedJob = AppliedJob::create($appliedJobsinsertDataArr);

                    $CandidateJobStatusComment = [
                        'job_id' => decrypt_data($request->input('job_id')),
                        'candidate_id' => $cid,
                        'applied_job_id' => $AppliedJob->id,
                        'user_id' => $AppliedJob->user_id,
                        'job_creator_id' => $AppliedJob->job_creator_id,
                        'status' => 'None',
                        'field_status' => 'Selected',
                        'is_active_status' => '1',
                        'is_bod_candidate_id' => $is_bod_candidate_id,
                    ];

                    $data1 = CandidateJobStatusComment::create($CandidateJobStatusComment);


                    if ($data1 == '') {

                        return redirect()->back()->with('error', 'somthing went wrong!');
                    }

                    //notification code
                    $notificationItem = array();
                    $employers =  User::whereHas('roles', function ($query) {
                        $query->where('role_id', '=', 1)->orWhere('role_id', '=', 2);
                    })->orWhere('id', '=', $AppliedJob->job_creator_id)->pluck('id')->toArray();
                    $recruiter = User::where('id', $UserId)->first();
                    $notificationItem['sender_id'] = $AppliedJob->job_creator_id;
                    $notificationItem['recever_ids'] = $employers;
                    $notificationItem['title'] = "Recruitment Partner has applied in (" . $job_title . ") job";
                    $notificationItem['type'] = "job_applied";
                    $notificationItem['message'] = "Recruitment Partner (" . $recruiter->first_name . ") has applied in (" . $job_title . ") job";

                    $job = (new SendQueueNotification($notificationItem))
                        ->delay(now()->addSeconds(2));

                    dispatch($job);

                    //end notification code

                }

                return redirect()->back()->with('success', 'Job Applied successfully');
            }
        } else {

            throw new AuthorizationException('You are not authorized to apply for this job.');
        }
    }

    public function bulkPostApplyJob(Request $request)
    {


        if (Gate::allows('job_applied_create') || Gate::allows('bod_job_applied_create')) {

            $UserId = auth()->user()->id;
            $roleName = getUserRole($UserId);
            $checkedJobIdsArr = explode(',', $request->checkedJobIdsArr);

            $multipleJobs = EmployerJob::whereIn('id', $checkedJobIdsArr)->get();

            $candidateId = $request->input('candidate');
            $candidateId = explode(",", $request->checkedCandidateArr);
            $multiplejobIds = $multipleJobs->pluck('id')->toArray();

            $checkCandidateArrSlugIsExist = $this->checkCandidateArrSlugIsExist($candidateId, $multiplejobIds);

            if ($checkCandidateArrSlugIsExist['status'] != true) {

                $msg = '<b>Following data already applied for the job:<b><br>';
                // dd($checkCandidateArrSlugIsExist['data']);
                foreach ($checkCandidateArrSlugIsExist['data'] as $key => $errorItem) {

                    $msg .= '<b>' . $key . ' : ' . implode(',', $errorItem) . '</b><br>';
                }

                return response()->json(['status' => false, 'message' => $msg, 'checkedcandidateIdData' => $candidateId]);
            }

            // candidate slug check code comment
            foreach ($multipleJobs as $jobdata) {

                $appliedJobsinsertDataArr = array();
                $currentTimestamp = Carbon::now();

                $job_id = $jobdata->id;
                $job_creator_id = EmployerJob::where('id', $job_id)->first()->user_id;


                if (in_array($roleName, array('Super Admin', 'Admin'))) {
                    $is_bod_candidate_id = '1';
                } else {
                    $is_bod_candidate_id = '0';
                }



                foreach ($candidateId as $cid) {

                    $candidateData = getCandidateByCandidateId($cid);

                    $appliedJobsinsertDataArr = [
                        'job_id' => $job_id,
                        'candidate_id' => $cid,
                        'user_id' => $UserId,
                        'job_creator_id' => $job_creator_id,
                        'is_bod_candidate_id' => $is_bod_candidate_id,
                        'candidate_job_slug' => $candidateData->candidate_job_slug,
                        'created_at' => $currentTimestamp,
                        'updated_at' => $currentTimestamp,
                    ];


                    $AppliedJob = AppliedJob::create($appliedJobsinsertDataArr);

                    $CandidateJobStatusComment = [
                        'job_id' => $job_id,
                        'candidate_id' => $cid,
                        'applied_job_id' => $AppliedJob->id,
                        'user_id' => $AppliedJob->user_id,
                        'job_creator_id' => $AppliedJob->job_creator_id,
                        'status' => 'None',
                        'field_status' => 'Selected',
                        'is_active_status' => '1',
                        'is_bod_candidate_id' => $is_bod_candidate_id,
                    ];

                    $data1 = CandidateJobStatusComment::create($CandidateJobStatusComment);


                    if ($data1 == '') {

                        return redirect()->back()->with('error', 'somthing went wrong!');
                    }
                    $job_title = $jobdata->job_title;
                    //notification code
                    $notificationItem = array();
                    $employers =  User::whereHas('roles', function ($query) {
                        $query->where('role_id', '=', 1)->orWhere('role_id', '=', 2);
                    })->orWhere('id', '=', $AppliedJob->job_creator_id)->pluck('id')->toArray();
                    $recruiter = User::where('id', $UserId)->first();
                    $notificationItem['sender_id'] = $AppliedJob->job_creator_id;
                    $notificationItem['recever_ids'] = $employers;
                    $notificationItem['title'] = "Recruitment Partner (" . $recruiter->first_name . ") has applied in (" . $job_title . ") job";
                    $notificationItem['type'] = "job_applied";
                    $notificationItem['message'] = "Recruitment Partner (" . $recruiter->first_name . ") has applied in (" . $job_title . ") job";

                    $job = (new SendQueueNotification($notificationItem))
                        ->delay(now()->addSeconds(2));

                    dispatch($job);

                    //end notification code

                }
            }

            return response()->json(['status' => true]);
        } else {

            throw new AuthorizationException('You are not authorized to apply for this job.');
        }
    }

    function checkCandidateArrSlugIsExist($candidateId, $multiplejobIds)
    {
        $userId = auth()->user()->id;
        $RoleName = getUserRole($userId);

        if ($RoleName == 'Admin' || $RoleName == 'Super Admin') {
            $candidateModel = new BodCandidate();
        } else {
            $candidateModel = new Candidate();
        }

        $candidate_email_Arr = $candidateModel->whereIn('candidate_id', $candidateId)
            ->get()
            ->pluck('email')
            ->toArray();

        $candidate_contact_Arr = $candidateModel->whereIn('candidate_id', $candidateId)
            ->get()
            ->pluck('contact_no')
            ->toArray();

        // $appliedJobCandidates = AppliedJob::whereIn('job_id', $multiplejobIds)
        //     ->with('employerJobs')->get()
        //     ->map(function ($appliedJob) {
        //         return $appliedJob->is_bod_candidate_id == 1 ? $appliedJob->bod_candidate : $appliedJob->candidate;
        //     });
        $appliedJobCandidates = AppliedJob::whereIn('job_id', $multiplejobIds)
            ->with('employerJob') // Include the employerJobs relation
            ->get()
            ->map(function ($appliedJob) {
                if ($appliedJob->is_bod_candidate_id == 1) {
                    $candidate = $appliedJob->bod_candidate;
                } else {
                    $candidate = $appliedJob->candidate;
                }

                // Include employerJobs data in the result
                $appliedJob->setRelation('employerJobs', $appliedJob->employerJobs);

                // Merge candidate and appliedJob data
                $result = $appliedJob->toArray();
                $result['candidate'] = $candidate->toArray();

                return $result;
            });


        $foundDataArr = [];

        foreach ($appliedJobCandidates as $appliedJob) {

            $candidatearr = $appliedJob['candidate'];

            $candidateEmail = $candidatearr['email'];
            $candidateContact = $candidatearr['contact_no'];

            if (in_array($candidateEmail, $candidate_email_Arr) || in_array($candidateContact, $candidate_contact_Arr)) {

                $jobTitle = $appliedJob['employer_job']['job_title'];

                $foundDataArr[$jobTitle][] = $candidatearr['name'];
            }
        }

        $responce = [];
        $responce['status'] = empty($foundDataArr) ? true : false;
        $responce['data'] = $foundDataArr;

        return $responce;
    }


    // function checkCandidateArrSlugIsExist($candidateId, $multiplejobIds)
    // {

    //     $userId = auth()->user()->id;
    //     $RoleName = getUserRole($userId);

    //     if ($RoleName == 'Admin' || $RoleName == 'Super Admin') {

    //         // $candidate_job_slug = BodCandidate::whereIn('candidate_id', $candidateId)->get()->pluck('candidate_job_slug')->toArray();
    //         $candidate_email_Arr = BodCandidate::whereIn('candidate_id', $candidateId)
    //             ->get()
    //             ->pluck(['email'])
    //             ->toArray();

    //         $candidate_contact_Arr = BodCandidate::whereIn('candidate_id', $candidateId)
    //             ->get()
    //             ->pluck(['contact_no'])
    //             ->toArray();
    //     } else {
    //         // $candidate_job_slug = Candidate::whereIn('candidate_id', $candidateId)->get()->pluck('candidate_job_slug')->toArray();
    //         $candidate_email_Arr = Candidate::whereIn('candidate_id', $candidateId)
    //             ->get()
    //             ->pluck(['email'])
    //             ->toArray();

    //         $candidate_contact_Arr = Candidate::whereIn('candidate_id', $candidateId)
    //             ->get()
    //             ->pluck(['contact_no'])
    //             ->toArray();
    //     }



    //     $appliedJobCandidates = AppliedJob::whereIn('job_id', $multiplejobIds)
    //         ->get()
    //         ->map(function ($appliedJob) {
    //             if ($appliedJob->is_bod_candidate_id == 1) {
    //                 $appliedJob->setRelation('candidate', $appliedJob->bod_candidate);
    //             } else {
    //                 $appliedJob->setRelation('candidate', $appliedJob->candidate);
    //             }
    //             return $appliedJob;
    //         });




    //     $emailsInAppliedJobs = $appliedJobCandidates->pluck('candidate.email')->merge($appliedJobCandidates->pluck('bodcandidate.email'));

    //     $contactsInAppliedJobs = $appliedJobCandidates->pluck('candidate.contact_no')->merge($appliedJobCandidates->pluck('bodcandidate.contact_no'));


    //     // Checking if any of the emails exist in the $emails array
    //     $foundEmails = $emailsInAppliedJobs->intersect($candidate_email_Arr);
    //     $foundContacts = $contactsInAppliedJobs->intersect($candidate_contact_Arr);
    //     $foundDataArr = [];

    //     // Check if any emails were found
    //     if ($foundEmails->isNotEmpty()) {
    //         // Emails found in the $emails array
    //         foreach ($foundEmails as $email) {
    //             $foundDataArr['email'][] = $email;
    //         }
    //     }

    //     if ($foundContacts->isNotEmpty()) {
    //         // Emails found in the $emails array
    //         foreach ($foundContacts as $contact) {
    //             $foundDataArr['contact'][] = $contact;
    //         }
    //     }


    //     $dublicateArr = [];

    //     if (count($foundDataArr) > 0) {

    //         $foundDataArr['email'] = $foundDataArr['email'] ?? [];
    //         $foundDataArr['contact'] = $foundDataArr['contact'] ?? [];

    //         if ($RoleName == 'Admin' || $RoleName == 'Super Admin') {


    //             $dublicateArr = BodCandidate::whereIn('email', $foundDataArr['email'])
    //                 ->OrwhereIn('contact_no', $foundDataArr['contact'])
    //                 ->get()
    //                 ->pluck(['name'])
    //                 ->toArray();
    //         } else {

    //             $dublicateArr = Candidate::whereIn('email', $foundDataArr['email'])
    //                 ->OrwhereIn('contact_no', $foundDataArr['contact'])
    //                 ->get()
    //                 ->pluck(['name'])
    //                 ->toArray();
    //         }
    //     }

    //     if (empty($dublicateArr)) {

    //         $responce = [];
    //         $responce['status'] = true;
    //     } else {
    //         $uniqueArray = array_unique($dublicateArr);
    //         $msg = implode(',', $uniqueArray);
    //         $responce = [];
    //         $responce['status'] = false;
    //         $responce['msg'] = $msg;
    //     }

    //     return $responce;
    // }
}
