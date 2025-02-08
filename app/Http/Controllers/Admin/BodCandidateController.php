<?php

namespace App\Http\Controllers\Admin;

use App\AppliedJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BodCandidate;
use App\City;
use App\Country;
use App\Jobs\BulkBODCandidateUpload;
use App\SheetStatus;
use App\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class BodCandidateController extends Controller
{

    public function RedirectCandidateView($id){

        $id = encrypt_data($id);
        $userId =Auth::user()->id;
        $roleName = getUserRole($userId);

        if ($roleName == 'Super Admin' || $roleName == 'Admin') {
            return redirect()->route('admin.bodCandidate.show', $id);
        } else {
            return redirect()->route('admin.candidate.show', $id);
        }

    }

    public function index()
    {
        abort_if(Gate::denies('bod_candidate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.bodCandidates.index');
    }

    public function getBodCandidateDatatable(Request $request)
    {
        $query = BodCandidate::OrderBy('created_at','desc');

        $query = BodCandidate::select([
            'id',
            'name',
            'email',
            'resume',
            'status',
            'updated_at',
            'candidate_id',
            'is_saved',
            DB::raw('DATE_FORMAT(updated_at, "%d-%m-%Y %H:%i:%s") as formatted_updated_at')
            ])
            ->orderBy('created_at', 'desc');

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
            if (Gate::allows('bod_candidate_show')) {
                $viewButton = '<a href="' . route('admin.bodCandidate.show', encrypt_data($candidate->id)) . '" class="btn btn-xs btn-primary mt-11">' . trans('global.view') . '</a>';
            }

            // Edit button
            if (Gate::allows('bod_candidate_edit')) {
                $editButton = '<a href="' . route('admin.bodCandidate.edit', encrypt_data($candidate->id)) . '" class="btn btn-xs btn-info mt-11">' . trans('global.edit') . '</a>';
            }

            // Save/Unsave button


            if ($candidate->is_saved == 1) {
                $saveUnsaveButton = '<a href="' . route('admin.bodCandidate.unSavedCandidate', ['id' => encrypt_data($candidate->id)]) . '" class="btn btn-xs btn-warning mt-11">un-save</a>';
            } else {
                $saveUnsaveButton = '<a href="' . route('admin.bodCandidate.savedCandidate', ['id' => encrypt_data($candidate->id)]) . '" class="btn btn-xs btn-success mt-11">Save candidate</a>';
            }

            // Delete button
            if (Gate::allows('bod_candidate_delete')) {
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
        abort_if(Gate::denies('bod_candidate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bodCandidates = BodCandidate::where('is_saved',1)->OrderBy('created_at','DESC')->get();

        return view('admin.bodCandidates.savedCandidate', compact('bodCandidates'));
    }

    public function create()
    {
        abort_if(Gate::denies('bod_candidate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $country = Country::all();
        return view('admin.bodCandidates.create', compact('country'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('bod_candidate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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

         //slug create

         $email =$request->input('email');
         $contact_number =$request->input('contact_number');
         $slugItem = trim($email).trim($contact_number);

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
            'candidate_job_slug'=>$candidate_job_slug,
            'resume'=>$request->input('resume_path'),
        ];

        if(empty($request->overwrite_candidate_id)){
            $candidate_id = generateUniqueCandidateId();
            $candidateDataArr['candidate_id'] = $candidate_id;
        }
        if($request->input('date_of_birth') != null){
            $candidateDataArr['date_of_birth'] = date('Y-m-d', strtotime($request->input('date_of_birth')));
        }

        if($request->has('gender')){
            $candidateDataArr['gender'] = $request->input('gender');
        }

        if ($request->input('any_other_langauge') == 'Other') {

            $candidateDataArr['other_any_other_langauge'] = $request->input('other_any_other_langauge');
        }

        if ($request->input('experience_sf') == 'Yes') {

            $candidateDataArr['how_many_experience'] = $request->input('how_many_experience');
            $candidateDataArr['presently_working_in_sf'] = $request->input('presently_working_in_sf');


            if ($request->input('presently_working_in_sf') == 'No') {

                $candidateDataArr['last_month_year_in_sf'] =$request->input('last_month_year_in_sf');

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

        if(!empty($request->overwrite_candidate_id)){
            $updatecandidate = BodCandidate::where('id',$request->overwrite_candidate_id)->update($candidateDataArr);
            $candidate = BodCandidate::find($request->overwrite_candidate_id);
            $msg = 'Candidate updated successfully';
        }else{
            $candidate = BodCandidate::create($candidateDataArr);
            $msg = 'candidate created successfully';

        }

        if ($candidate == '') {
            return redirect()->back()->with('error', 'somthing went wrong in candidate record insert!');
        } else {
            return redirect()->route('admin.bodCandidate.index')->with('success', $msg);
        }
    }

    public function edit($id)
    {

        $candidateResume = request('candidate_resume');
        $candidate_resume = $candidateResume;
        // dd($candidate_resume);
        abort_if(Gate::denies('bod_candidate_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = decrypt_data($id);
        $country = Country::all();
        $candidate = BodCandidate::find($id);
        $state = State::where('id',$candidate->state_id)->first();
        $city = City::where('id',$candidate->city_id)->first();

        return view('admin.bodCandidates.edit', compact('candidate', 'country', 'state', 'city', 'candidate_resume'));
    }

    public function update(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); die;
        abort_if(Gate::denies('bod_candidate_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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


        $this->validate($request, $newCandidateValidation);
         //slug create

         $email =$request->input('email');
         $contact_number =$request->input('contact_number');
         $slugItem = trim($email).trim($contact_number);
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
            'candidate_job_slug'=>$candidate_job_slug,
            'resume'=>$request->input('resume_path'),
        ];
        if ($request->input('any_other_langauge') == 'Other') {

            $candidateDataArr['other_any_other_langauge'] = $request->input('other_any_other_langauge');
        }else{
            $candidateDataArr['other_any_other_langauge'] = null;

        }

        if ($request->input('experience_sf') == 'Yes') {

            $candidateDataArr['how_many_experience'] = $request->input('how_many_experience');
            $candidateDataArr['presently_working_in_sf'] = $request->input('presently_working_in_sf');

           if ($request->input('presently_working_in_sf') == 'No') {
            $candidateDataArr['last_month_year_in_sf'] =$request->input('last_month_year_in_sf');
           }else{
            $candidateDataArr['last_month_year_in_sf'] = null;
           }
        }else{
            $candidateDataArr['how_many_experience'] = null;
            $candidateDataArr['presently_working_in_sf'] = null;

        }

        if($request->input('date_of_birth') != null){
            $candidateDataArr['date_of_birth'] = date('Y-m-d', strtotime($request->input('date_of_birth')));
        }else{
            $candidateDataArr['date_of_birth'] = null;
        }

        if($request->has('gender')){
            $candidateDataArr['gender'] = $request->input('gender');
        }else{
            $candidateDataArr['gender'] = null;
        }


        if ($request->has('license_candidate_banking_finance')) {

            $candidateDataArr['license_candidate_banking_finance'] = $request->input('license_candidate_banking_finance');
        }else{
            $candidateDataArr['license_candidate_banking_finance'] = null;
        }

        if ($request->has('reference_check')) {

            $candidateDataArr['reference_check'] = $request->input('reference_check');
        }else{
            $candidateDataArr['reference_check'] =null;
        }


        // $resume = $request->file('resume');

        // if (!empty($resume)) {

        //     $resumeName = time() . '.' . $resume->getClientOriginalExtension();
        //     $resume->move(public_path('candidate_resume'), $resumeName);
        //     $arrNew = array('resume' => $resumeName);
        //     $candidateDataArr =  array_merge($candidateDataArr, $arrNew);
        // }

        BodCandidate::where('id', $id)->update($candidateDataArr);

        return redirect()->route('admin.bodCandidate.index')->with('success', 'candidate updated successfully.');
    }

    public function show($id)
    {
        abort_if(Gate::denies('bod_candidate_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = decrypt_data($id);
        // dd($id);
        $candidate = BodCandidate::find($id);

        return view('admin.bodCandidates.show', compact('candidate'));
    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('bod_candidate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = $request->input('id');
        AppliedJob::where('candidate_id', $id)->delete();
        BodCandidate::where('candidate_id', $id)->delete();

        return back()->with('success', 'Candidate deleted successfully.');
    }
    public function savedCandidate(Request $request)
    {
        abort_if(Gate::denies('bod_saved_candidate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id =$request->input('id');
        $id = decrypt_data($id);

        BodCandidate::where('id', $id)->update(['is_saved'=>1]);

        return back()->with('success', 'Candidate Saved successfully.');
    }

    public function unSavedCandidate(Request $request)
    {
        abort_if(Gate::denies('bod_saved_candidate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id =$request->input('id');
        $id = decrypt_data($id);

        BodCandidate::where('id', $id)->update(['is_saved'=>'0']);

        return back()->with('success', 'Candidate un-saved successfully.');
    }

    // public function massDestroy(MassDestroyRoleRequest $request)
    // {
    //     Role::whereIn('id', request('ids'))->delete();

    //     return response(null, Response::HTTP_NO_CONTENT);
    // }


    public function bodBulkCandidate(Request $request)
    {
        // abort_if(Gate::denies('bod_candidate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $SheetStatus = SheetStatus::orderBy('id', 'desc')->get();
        // dd($SheetStatus);
        return view('admin.bodCandidates.bodBulkCandidate',compact('SheetStatus'));
    }

    public function storeBODBulkCandidate(Request $request)
    {
        $file = $request->file('bodbulkcan');
        // dd($file->getClientOriginalName());

        $spreadsheet = IOFactory::load($file);

        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestDataRow();
        $highestColumn = $worksheet->getHighestDataColumn();
        $sheetName = $file->getClientOriginalName();

        $userId = Auth::user()->id;

        $roleName = getUserRole($userId);


        // Extract necessary data from the worksheet
        $data = [
            'auth_login' => $userId,
            'highestRow' => $highestRow,
            'highestColumn' => $highestColumn,
            'sheetName' => $sheetName,
            'cellValues' => $worksheet->toArray(), // or any other relevant data
        ];


        // Dispatch the job with the extracted data

        // BulkBODCandidateUpload::dispatch($data);
        BulkBODCandidateUpload::dispatch($data)->onConnection('database')->onQueue('default');

        // $job = (new \App\Jobs\BulkBODCandidateUpload($data))
        //         ->delay(now()->addSeconds(1));

        //     dispatch($job);



        if ($roleName == 'Super Admin' || $roleName == 'Admin') {
            return redirect()->route('admin.bodCandidate.index')->with('success', 'candidate updated successfully.');
        } else {
            return redirect()->route('admin.candidate.index')->with('success', 'candidate updated successfully.');
        }

        // return redirect()->route('admin.bodCandidate.index')->with('success', 'candidate updated successfully.');

    }
}
