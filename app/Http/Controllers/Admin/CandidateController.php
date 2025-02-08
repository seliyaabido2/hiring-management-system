<?php

namespace App\Http\Controllers\Admin;

use App\AppliedJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Candidate;
use App\SavedCandidate;
use App\CandidateJobStatusComment;
use App\City;
use App\Country;
use App\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class CandidateController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('candidate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return view('admin.candidates.index');
    }

    public function getCandidateDatatable(Request $request)
    {

        $user_id = auth()->user()->id;
        $roleName =  getUserRole($user_id);

       

        if ($roleName == 'Recruiter') {
            $query = Candidate::select(['*',DB::raw('DATE_FORMAT(updated_at, "%d-%m-%Y %H:%i:%s") as formatted_updated_at')])->where('user_id', $user_id)->with('savedCandidate')->orderBy('created_at', 'desc');
        } else {
            $query = Candidate::select(['*',DB::raw('DATE_FORMAT(updated_at, "%d-%m-%Y %H:%i:%s") as formatted_updated_at')])->with('savedCandidate')->orderBy('created_at', 'desc');
        }

        $searchableColumns = [
            'id',
            'name',
            'email',
            'resume',
            'status',
            'updated_at', 
        ];


        // Apply search conditions to the query
        foreach ($searchableColumns as $column) {
            if ($request->filled($column)) {
                // Use the original column name for the search condition
                $query->where($column, 'like', '%' . $request->input($column) . '%');
            }
        }


        return DataTables::of($query)
        ->addColumn('actions', function ($candidate) {
            $viewButton = '';
            $editButton = '';
            $saveUnsaveButton = '';
            $deleteButton = '';

            // View button
            if (Gate::allows('candidate_show')) {
                $viewButton = '<a href="' . route('admin.candidate.show', encrypt_data($candidate->id)) . '" class="btn btn-xs btn-primary mt-11">' . trans('global.view') . '</a>';
            }

            // Edit button
            if (Gate::allows('candidate_edit')) {
                $editButton = '<a href="' . route('admin.candidate.edit', encrypt_data($candidate->id)) . '" class="btn btn-xs btn-info mt-11">' . trans('global.edit') . '</a>';
            }

            // Save/Unsave button

            if ($candidate->savedCandidate) {
                $saveUnsaveButton = '<a href="' . route('admin.candidate.unSavedCandidate', ['id' => encrypt_data($candidate->candidate_id)]) . '" class="btn btn-xs btn-warning mt-11">un-save</a>';
            } else {
                $saveUnsaveButton = '<a href="' . route('admin.candidate.savedCandidate', ['id' => encrypt_data($candidate->candidate_id)]) . '" class="btn btn-xs btn-success mt-11">Save candidate</a>';
            }

            // Delete button
            if (Gate::allows('candidate_delete')) {
                $deleteButton = '<button class="btn btn-xs btn-danger delete-btn mt-11" data-id="' . $candidate->candidate_id . '">' . trans('global.delete') . '</button>';
            }

            // Concatenate all buttons
            $actions = $viewButton . $editButton . $saveUnsaveButton . $deleteButton;

            return $actions;
        })
        ->rawColumns(['actions'])
        ->make(true);


    }

    public function savedCandidates()
    {
        abort_if(Gate::denies('saved_candidate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_id = auth()->user()->id;
        $roleName =  getUserRole($user_id);

        if ($roleName == 'Recruiter' || $roleName == 'Employer') {
            $candidates = SavedCandidate::where('user_id', $user_id)
                ->get()
                ->map(function ($savedCandidate) {
                    if ($savedCandidate->is_bod_candidate_id == 1) {
                        $savedCandidate->setRelation('candidate', $savedCandidate->bod_candidate);
                    } else {
                        $savedCandidate->setRelation('candidate', $savedCandidate->candidate);
                    }
                    return $savedCandidate;
                });
        } else {

            $candidates = SavedCandidate::get()
                ->map(function ($savedCandidate) {
                    if ($savedCandidate->is_bod_candidate_id == 1) {
                        $savedCandidate->setRelation('candidate', $savedCandidate->bod_candidate);
                    } else {
                        $savedCandidate->setRelation('candidate', $savedCandidate->candidate);
                    }
                    return $savedCandidate;
                });
        }

        return view('admin.candidates.savedCandidate', compact('candidates'));
    }

    public function create()
    {
        abort_if(Gate::denies('candidate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $country = Country::all();
        return view('admin.candidates.create', compact('country'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('candidate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $msg = '';
        $UserId = Auth::user()->id;

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

        $this->validate($request, $newCandidateValidation);

        $candidate_id = generateUniqueCandidateId();

        //slug create

        $contact_number = $request->input('contact_number');
        $email = $request->input('email');
        $slugItem = trim($email) . trim($contact_number);
        $candidate_job_slug = createSlug($slugItem);

        //end slug
        $candidateDataArr = [

            'user_id' => $UserId,
            'name' => $request->input('candidate_name'),
            'gender' => $request->input('gender'),
            'email' => $request->input('email'),
            'contact_no' => $request->input('contact_number'),
            'job_preference' => $request->input('job_preference'),
            'job_type' => $request->input('job_type'),
            'location' => $request->input('location'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'experience_sf' => $request->input('experience_sf'),
            'license_requirement' => $request->input('license_requirement'),
            'license_candidate_basic_training' => $request->input('license_candidate_basic_training'),
            'any_other_langauge' => $request->input('any_other_langauge'),
            'expected_pay_per_hour' => $request->input('expected_pay_per_hour'),
            'current_pay_per_hour' => $request->input('current_pay_per_hour'),
            // 'resume' => $request->input('resume'),
            'additional_information' => $request->input('additional_information'),
            'candidate_job_slug' => $candidate_job_slug,
            'resume' => $request->input('resume_path'),
        ];

        if (empty($request->overwrite_candidate_id)) {
            $candidate_id = generateUniqueCandidateId();
            $candidateDataArr['candidate_id'] = $candidate_id;
        }


        if ($request->input('date_of_birth') != null) {
            $candidateDataArr['date_of_birth'] = date('Y-m-d', strtotime($request->input('date_of_birth')));
        }

        if ($request->has('gender')) {
            $candidateDataArr['gender'] = $request->input('gender');
        }

        if ($request->input('any_other_langauge') == 'Other') {

            $candidateDataArr['other_any_other_langauge'] = $request->input('other_any_other_langauge');
        }

        if ($request->input('experience_sf') == 'Yes') {

            $candidateDataArr['how_many_experience'] = $request->input('how_many_experience');
            $candidateDataArr['presently_working_in_sf'] = $request->input('presently_working_in_sf');


            if ($request->input('presently_working_in_sf') == 'No') {
                $candidateDataArr['last_month_year_in_sf'] = $request->input('last_month_year_in_sf');
            }
        }
        if ($request->has('reference_check')) {

            $candidateDataArr['reference_check'] = $request->input('reference_check');
        }


        if ($request->has('license_candidate_banking_finance')) {

            $candidateDataArr['license_candidate_banking_finance'] = $request->input('license_candidate_banking_finance');
        }

        // $resume = $request->file('resume');

        // if (!empty($resume)) {

        //     $resumeName = time() . '.' . $resume->getClientOriginalExtension();
        //     $resume->move(public_path('candidate_resume'), $resumeName);
        //     $arrNew = array('resume' => $resumeName);
        //     $candidateDataArr =  array_merge($candidateDataArr, $arrNew);
        // }

        if (!empty($request->overwrite_candidate_id)) {
            $updatecandidate = Candidate::where('id', $request->overwrite_candidate_id)->update($candidateDataArr);

            $candidate = Candidate::find($request->overwrite_candidate_id);
            $msg = 'Candidate updated successfully';
        } else {
            $candidate = Candidate::create($candidateDataArr);
            $msg = 'candidate created successfully';
        }


        if ($candidate == '') {
            return redirect()->back()->with('error', 'somthing went wrong in candidate record insert table!');
        } else {
            return redirect()->route('admin.candidate.index')->with('success', $msg);
        }
    }

    public function edit($id)
    {

        abort_if(Gate::denies('candidate_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = decrypt_data($id);
        $country = Country::all();
        $candidate = Candidate::find($id);
        $state = State::where('id', $candidate->state_id)->first();
        $city = City::where('id', $candidate->city_id)->first();

        return view('admin.candidates.edit', compact('candidate', 'country', 'state', 'city'));
    }

    public function update(Request $request)
    {
        abort_if(Gate::denies('candidate_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = $request->input('id');

        $newCandidateValidation = [
            'candidate_name' => 'required',
            'email' => 'required',
            'contact_number' => 'required',
            'location' => 'required',
            'experience_sf' => 'required',
            'license_requirement' => 'required',
            'any_other_langauge' => 'required',
            'status' => 'required',
        ];



        if ($request->input('any_other_langauge') == 'Other') {

            $arr = array('other_any_other_langauge' => 'required');
            $newCandidateValidation =  array_merge($newCandidateValidation, $arr);
        }

        // if ($request->input('experience_sf') == 'Yes') {

        //     $arr = array('how_many_experience' => 'required','presently_working_in_sf' => 'required');
        //     $newCandidateValidation =  array_merge($newCandidateValidation, $arr);
        // }

        $this->validate($request, $newCandidateValidation);

        //slug create
        $email = $request->input('email');
        $contact_number = $request->input('contact_number');
        $slugItem = trim($email) . trim($contact_number);


        $candidate_job_slug = createSlug($slugItem);

        //end slug

        $candidateDataArr = [
            'name' => $request->input('candidate_name'),
            'email' => $request->input('email'),
            'contact_no' => $request->input('contact_number'),
            'job_preference' => $request->input('job_preference'),
            'job_type' => $request->input('job_type'),
            'location' => $request->input('location'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'experience_sf' => $request->input('experience_sf'),
            'license_requirement' => $request->input('license_requirement'),
            'license_candidate_basic_training' => $request->input('license_candidate_basic_training'),
            'any_other_langauge' => $request->input('any_other_langauge'),
            'expected_pay_per_hour' => $request->input('expected_pay_per_hour'),
            'current_pay_per_hour' => $request->input('current_pay_per_hour'),
            'additional_information' => $request->input('additional_information'),
            'status' => $request->input('status'),
            'candidate_job_slug' => $candidate_job_slug,
            'resume' => $request->input('resume_path'),
        ];

        if ($request->input('any_other_langauge') == 'Other') {

            $candidateDataArr['other_any_other_langauge'] = $request->input('other_any_other_langauge');
        } else {
            $candidateDataArr['other_any_other_langauge'] = null;
        }

        if ($request->input('experience_sf') == 'Yes') {

            $candidateDataArr['how_many_experience'] = $request->input('how_many_experience');
            $candidateDataArr['presently_working_in_sf'] = $request->input('presently_working_in_sf');

            if ($request->input('presently_working_in_sf') == 'No') {

                $candidateDataArr['last_month_year_in_sf']  = $request->input('last_month_year_in_sf');
            } else {
                $candidateDataArr['last_month_year_in_sf'] = null;
            }
        } else {
            $candidateDataArr['how_many_experience'] = null;
            $candidateDataArr['presently_working_in_sf'] = null;
        }

        if ($request->input('date_of_birth') != null) {
            $candidateDataArr['date_of_birth'] = date('Y-m-d', strtotime($request->input('date_of_birth')));
        } else {
            $candidateDataArr['date_of_birth'] = null;
        }

        if ($request->has('gender')) {
            $candidateDataArr['gender'] = $request->input('gender');
        } else {
            $candidateDataArr['gender'] = null;
        }

        if ($request->has('license_candidate_   banking_finance')) {

            $candidateDataArr['license_candidate_banking_finance'] = $request->input('license_candidate_banking_finance');
        } else {
            $candidateDataArr['license_candidate_banking_finance'] = null;
        }

        if ($request->has('reference_check')) {

            $candidateDataArr['reference_check'] = $request->input('reference_check');
        } else {
            $candidateDataArr['reference_check'] = null;
        }

        // $resume = $request->file('resume');

        // if (!empty($resume)) {

        //     $resumeName = time() . '.' . $resume->getClientOriginalExtension();
        //     $resume->move(public_path('candidate_resume'), $resumeName);
        //     $arrNew = array('resume' => $resumeName);
        //     $candidateDataArr =  array_merge($candidateDataArr, $arrNew);
        // }
        // dd($candidateDataArr);
        Candidate::where('id', $id)->update($candidateDataArr);

        return redirect()->route('admin.candidate.index')->with('success', 'candidate updated successfully.');
    }

    public function show($id)
    {
        abort_if(Gate::denies('candidate_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = decrypt_data($id);
        // dd($id);
        $candidate = Candidate::find($id);

        return view('admin.candidates.show', compact('candidate'));
    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('candidate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = $request->input('id');
        AppliedJob::where('candidate_id', $id)->delete();
        Candidate::where('candidate_id', $id)->delete();

        return back()->with('success', 'Candidate deleted successfully.');
    }


    public function CandidateSelected()
    {

        $selectedCandidate = CandidateJobStatusComment::with([
            'getJobDetail:id,job_title',
            'candidate:id,candidate_id,name',
        ])
            ->where(['status' => 'Final Selection', 'field_status' => 'Selected'])
            ->select('id', 'job_id', 'candidate_id')
            ->get();

        return view('admin.candidates.candidateSelected', compact('selectedCandidate'));
    }
    // public function massDestroy(MassDestroyRoleRequest $request)
    // {
    //     Role::whereIn('id', request('ids'))->delete();

    //     return response(null, Response::HTTP_NO_CONTENT);
    // }

    public function savedCandidate(Request $request)
    {
        // abort_if(Gate::denies('bod_candidate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = $request->input('id');
        $id = decrypt_data($id);

        $savedCandidateArr = array(
            'user_id' => auth()->user()->id,
            'candidate_id' => $id,
            'is_bod_candidate_id' => 0
        );
        $candidate = SavedCandidate::create($savedCandidateArr);


        return back()->with('success', 'Candidate saved successfully.');
    }

    public function unSavedCandidate(Request $request)
    {

        // abort_if(Gate::denies('bod_candidate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = $request->input('id');
        $candidate_id = decrypt_data($id);

        $user_id = auth()->user()->id;
        SavedCandidate::where(['candidate_id' => $candidate_id, 'user_id' => $user_id])->delete();


        return back()->with('success', 'Candidate un-saved successfully.');
    }

    public function massDestroy(Request $request)
    {

        $ids = explode(',', request('ids'));

        AppliedJob::whereIn('candidate_id', $ids)->delete();
        Candidate::whereIn('candidate_id', $ids)->delete();

        return redirect()->route('admin.candidate.index')->with('success', 'Candidate deleted successfully.');
    }
}
