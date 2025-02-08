<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\AppliedJob;
use App\Candidate;
use App\CandidateJobStatusComment;
use App\Employee;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyEmployeeRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Service;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\EmployerJob;
use Carbon\Carbon;
use Illuminate\Http\Request;




class EmployerJobController extends Controller
{

    public function applyJob(Request $request) {

        try
        {

            $UserId = $request->user()->token()->user_id;

            $validation = Validator::make(

                $request->all(), [
                    'job_id' => 'required',
                    'create_candidate' => 'required',
                ]
            );

            if ($validation->fails()) {
                return response()->json(['status' => false, 'message' => $validation->errors()->first()]);
            }

            if($request->input('create_candidate') == 'true'){

                $newCandidateValidation = [
                        'name' => 'required',
                        'date_of_birth' => 'required',
                        'gender' => 'required',
                        'email' => 'required',
                        'contact_no' => 'required',
                        'location' => 'required',
                        // 'pincode' => 'required',
                        'experience_sf' => 'required',
                        'experience_without_sf' => 'required',
                        'license_candidate_basic_training' => 'required',
                        'license_candidate_no_experience' => 'required',
                        'license_candidate_banking_finance' => 'required',
                        'language_preference' => 'required',
                        'license_requirement' => 'required',
                        'resume' => 'required',
                ];

                if($request->input('license_requirement') == 'other'){

                    $arr = array('other_license_requirement'=> 'required');
                    $newCandidateValidation =  array_merge($newCandidateValidation, $arr);
                }

                $CandidateValidation = Validator::make(

                    $request->all(),
                    $newCandidateValidation
                );

                if($CandidateValidation->fails()){

                    return response()->json(['status' => false, 'message' => $CandidateValidation->errors()->first()]);
                }


                $candidate_id = generateUniqueCandidateId();


                $candidateDataArr = [
                    'candidate_id' =>  $candidate_id,
                    'user_id' => $UserId,
                    'name' => $request->input('name'),
                    'date_of_birth' => $request->input('date_of_birth'),
                    'gender' => $request->input('gender'),
                    'email' => $request->input('email'),
                    'contact_no' => $request->input('contact_no'),
                    'address' => $request->input('address'),
                    'country_id' => $request->input('country_id'),
                    'state_id' => $request->input('state_id'),
                    'city_id' => $request->input('city_id'),
                    // 'pincode' => $request->input('pincode'),
                    'experience_sf' => $request->input('experience_sf'),
                    'experience_without_sf' => $request->input('experience_without_sf'),
                    'license_candidate_basic_training' => $request->input('license_candidate_basic_training'),
                    'license_candidate_no_experience' => $request->input('license_candidate_no_experience'),
                    'license_candidate_banking_finance' => $request->input('license_candidate_banking_finance'),
                    'language_preference' => $request->input('language_preference'),
                    'license_requirement' => $request->input('license_requirement'),
                    'resume' => $request->input('resume'),
                ];

                if($request->input('license_requirement') == 'other'){
                    $arrNew = array('other_license_requirement'=>$request->input('other_license_requirement'));
                    $candidateDataArr =  array_merge($candidateDataArr, $arrNew);
                }


                $candidate = Candidate::create($candidateDataArr);

                $job_id  = $request->input('job_id');
                $job_creator_id = EmployerJob::where('id',$job_id)->first()->user_id;

                $appliedJobsinsertDataArr = [
                    'job_id' => $request->input('job_id'),
                    'candidate_id' => $candidate->candidate_id,
                    'job_creator_id' => $job_creator_id,
                ];


                $AppliedJob = AppliedJob::create($appliedJobsinsertDataArr);


                $candidateDataArr = array(
                    'job_id' => $request->input('job_id'),
                    'candidate_id' => $candidate->candidate_id,
                    'status' => 'None'
                );
                
                $data = CandidateJobStatusComment::updateOrCreate(
                    [
                        'job_id' => $request->input('job_id'),
                        'candidate_id' => $candidate->candidate_id,
                        'applied_job_id' => $AppliedJob->id,
                        'status' => 'None',
                        'field_status' => 'Selected',
                        'is_active_status' => '1'
                    ],
                    $candidateDataArr
                );



                if($candidate == ''){
                    return response()->json(['status' => false, 'message' => 'somthing went wrong in candidate record insert table!']);

                }
                else if($data == ''){
                    return response()->json(['status' => false, 'message' => 'somthing went wrong in apply job insert table!']);

                }else{

                    return response()->json(['status' => true, 'message' => 'Job Applied successfully']);

                }

            }
            else{

                $candidateIdValidation = Validator::make(

                    $request->all(), [
                        'candidate_id' => 'required',
                    ],
                );

                if ($candidateIdValidation->fails()) {
                    return response()->json(['status' => false, 'message' => $candidateIdValidation->errors()->first()]);
                }

                $isAlreadyAppelied = $this->candidateIsAlreadyAppeliedJob($request->input('candidate_id'),$request->input('job_id'));

                if($isAlreadyAppelied['status'] == true){

                      return response()->json(['status' => false, 'message' => 'some candidate id is Already applied in this job',
                        'data' => $isAlreadyAppelied['item'] ]);
                }

                $candidateId = $request->input('candidate_id');
                $candidateId = explode(',',$candidateId);

                $check = checkCandidateIdIsExist($candidateId);

                if($check != true){

                    return response()->json(['status' => false, 'message' => 'candidate id Not Found!']);
                }

                $appliedJobsinsertDataArr = array();
                $currentTimestamp = Carbon::now();

                $job_id = $request->input('job_id');

                $job_creator_id = EmployerJob::where('id',$job_id)->first()->user_id;

                foreach($candidateId as $cid){

                    // $appliedJobsinsertDataArr[] = [
                    //     'job_id' => $request->input('job_id'),
                    //     'candidate_id' => $cid,
                    //     'created_at' => $currentTimestamp,
                    //     'updated_at' => $currentTimestamp,
                    // ];


                    $appliedJobsinsertDataArr = [
                        'job_id' => $request->input('job_id'),
                        'candidate_id' => $cid,
                        'user_id' => $UserId,
                        'job_creator_id' => $job_creator_id,
                        'created_at' => $currentTimestamp,
                        'updated_at' => $currentTimestamp,
                    ];


                    $AppliedJob = AppliedJob::create($appliedJobsinsertDataArr);

                    $CandidateJobStatusComment = [
                        'job_id' => $request->input('job_id'),
                        'candidate_id' => $cid,
                        'applied_job_id' => $AppliedJob->id,
                        'status' => 'None',
                        'field_status' => 'Selected',
                        'is_active_status'=>'1'
                    ];

                    $data1 = CandidateJobStatusComment::create($CandidateJobStatusComment);

                    if($data1 == ''){

                        return response()->json(['status' => false, 'message' => 'somthing went wrong!']);            
    
                    }
                }
            
                return response()->json(['status' => true, 'message' => 'Job Applied successfully']);

            }


        }catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th]);
        }

    }

    function candidateIsAlreadyAppeliedJob($candidateId,$jobId) {
    
        $candidateId = explode(',',$candidateId);
        $check = AppliedJob::whereIn('candidate_id',$candidateId)->where('job_id',$jobId)->pluck('candidate_id');
        $data=[];
        if(count($check) > 0){
            $data['status']=true;
            $data['item'] = $check;
            return $data;
        }else{
            $data['status']= false;
            return $data;
        }
    }

    public function create(Request $request){

        try
        {
            $UserId = $request->user()->token()->user_id;

            $validation = Validator::make(

                $request->all(), [

                    'job_title' => 'required',
                    'job_description' => 'required',
                    'job_address' => 'required',
                    'experience_sf' => 'required',
                    'experience_without_sf' => 'required',
                    'license_candidate_basic_training' => 'required',
                    'license_candidate_no_experience' => 'required',
                    'license_candidate_banking_finance' => 'required',
                    'job_role' => 'required',
                    'total_number_of_working_days' => 'required',
                    'job_type' => 'required',
                    'working_days_per_week' => 'required',
                    'working_day' => 'required',
                    'salary_type' => 'required',
                    'pay_per_hour' => 'required',
                    'pay_day' => 'required',
                    'bonus_commission' => 'required',
                    'job_benefits' => 'required',
                    'language_preference' => 'required',
                    'job_field_role' => 'required',
                    'parking_free' => 'required',
                    'parking_fee' => 'required',
                    'license_requirement' => 'required',
                    'other_license_requirement' => 'required',
                    'job_qualification' => 'required',
                    'other_job_qualification' => 'required',
                    'job_start_date' => 'required',
                    'job_recruiment_duration' => 'required',
                    'additional_information' => 'required',
                    // 'number_of_days' =>  'required',
                    'number_of_vacancies' => 'required',
                ]
            );

            if ($validation->fails()) {
                return response()->json(['status' => false, 'message' => $validation->errors()->first()]);
            }

            $inserDataArr = [
                    'user_id' => $UserId,
                    'job_title' => $request->input('job_title'),
                    'job_description' => $request->input('job_description'),
                    'job_address' => $request->input('job_address'),
                    'experience_sf' => $request->input('experience_sf'),
                    'experience_without_sf' => $request->input('experience_without_sf'),
                    'license_candidate_basic_training' => $request->input('license_candidate_basic_training'),
                    'license_candidate_no_experience' => $request->input('license_candidate_no_experience'),
                    'license_candidate_banking_finance' => $request->input('license_candidate_banking_finance'),
                    'job_role' => $request->input('job_role'),
                    'total_number_of_working_days' => $request->input('total_number_of_working_days'),
                    'job_type' => $request->input('job_type'),
                    'working_days_per_week' => $request->input('working_days_per_week'),
                    'working_day' => $request->input('working_day'),
                    'salary_type' => $request->input('salary_type'),
                    'pay_per_hour' => $request->input('pay_per_hour'),
                    'pay_day' => $request->input('pay_day'),
                    'bonus_commission' => $request->input('bonus_commission'),
                    'job_benefits' => $request->input('job_benefits'),
                    'language_preference' => $request->input('language_preference'),
                    'job_field_role' => $request->input('job_field_role'),
                    'parking_free' => $request->input('parking_free'),
                    'parking_fee' => $request->input('parking_fee'),
                    'status' =>'Hold',
                    'license_requirement' => $request->input('license_requirement'),
                    'other_license_requirement' => $request->input('other_license_requirement'),
                    'job_qualification' => $request->input('job_qualification'),
                    'other_job_qualification' => $request->input('other_job_qualification'),
                    'job_start_date' => date("Y-m-d", strtotime($request->input('job_start_date'))),
                    'job_recruiment_duration' => $request->input('job_recruiment_duration'),
                    'additional_information' => $request->input('additional_information'),
                    // 'number_of_days' => $request->input('number_of_days'),
                    'number_of_vacancies' => $request->input('number_of_vacancies'),
            ];

            $data = EmployerJob::create($inserDataArr);

            if($data != ''){
                return response()->json(['status' => true, 'message' => 'New Job Added successfully']);
            }else{
                return response()->json(['status' => false, 'message' => 'somthing went wrong!']);
            }
        }
        catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th]);
        }


    }

    public function myJob(Request $request){

        try
        {

            $UserId = $request->user()->token()->user_id;
            $data = EmployerJob::where('user_id',$UserId)->get();

            return response()->json(['status' => true, 'data' => $data ]);

        }
        catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th]);
        }
    }

    public function jobList(Request $request){

        try
        {
            $UserId = $request->user()->token()->user_id;

            $data = EmployerJob::all();

            return response()->json(['status' => true, 'data' => $data ]);

        }
        catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th]);
        }
    }

    public function update(Request $request){

        try
        {
            $UserId = $request->user()->token()->user_id;

            $validation = Validator::make(

                $request->all(), [

                    'job_title' => 'required',
                    'job_description' => 'required',
                    'job_address' => 'required',
                    'experience_sf' => 'required',
                    'experience_without_sf' => 'required',
                    'license_candidate_basic_training' => 'required',
                    'license_candidate_no_experience' => 'required',
                    'license_candidate_banking_finance' => 'required',
                    'job_role' => 'required',
                    'total_number_of_working_days' => 'required',
                    'job_type' => 'required',
                    'working_days_per_week' => 'required',
                    'working_day' => 'required',
                    'salary_type' => 'required',
                    'pay_per_hour' => 'required',
                    'pay_day' => 'required',
                    'bonus_commission' => 'required',
                    'job_benefits' => 'required',
                    'language_preference' => 'required',
                    'job_field_role' => 'required',
                    'parking_free' => 'required',
                    'parking_fee' => 'required',
                    'license_requirement' => 'required',
                    'other_license_requirement' => 'required',
                    'job_start_date' => 'required',
                    'job_recruiment_duration' => 'required',
                    'additional_information' => 'required',
                    // 'number_of_days' =>  'required',
                    'number_of_vacancies' => 'required',
                    'status' => 'required',
                ]
            );

            if ($validation->fails()) {
                return response()->json(['status' => false, 'message' => $validation->errors()->first()]);
            }

            $updateDataArr = [
                    // 'user_id' => $UserId,
                    'job_title' => $request->input('job_title'),
                    'job_description' => $request->input('job_description'),
                    'job_address' => $request->input('job_address'),
                    'experience_sf' => $request->input('experience_sf'),
                    'experience_without_sf' => $request->input('experience_without_sf'),
                    'license_candidate_basic_training' => $request->input('license_candidate_basic_training'),
                    'license_candidate_no_experience' => $request->input('license_candidate_no_experience'),
                    'license_candidate_banking_finance' => $request->input('license_candidate_banking_finance'),
                    'job_role' => $request->input('job_role'),
                    'total_number_of_working_days' => $request->input('total_number_of_working_days'),
                    'job_type' => $request->input('job_type'),
                    'working_days_per_week' => $request->input('working_days_per_week'),
                    'working_day' => $request->input('working_day'),
                    'salary_type' => $request->input('salary_type'),
                    'pay_per_hour' => $request->input('pay_per_hour'),
                    'pay_day' => $request->input('pay_day'),
                    'bonus_commission' => $request->input('bonus_commission'),
                    'job_benefits' => $request->input('job_benefits'),
                    'language_preference' => $request->input('language_preference'),
                    'job_field_role' => $request->input('job_field_role'),
                    'parking_free' => $request->input('parking_free'),
                    'parking_fee' => $request->input('parking_fee'),
                    'license_requirement' => $request->input('license_requirement'),
                    'other_license_requirement' => $request->input('other_license_requirement'),
                    'job_start_date' => date("Y-m-d", strtotime($request->input('job_start_date'))),
                    'job_recruiment_duration' => $request->input('job_recruiment_duration'),
                    'additional_information' => $request->input('additional_information'),
                    // 'number_of_days' => $request->input('number_of_days'),
                    'number_of_vacancies' => $request->input('number_of_vacancies'),
                    'status' =>  $request->input('status'),
            ];

            $data = EmployerJob::where('id', $request->input('id'))->update($updateDataArr);

            if($data != ''){
                return response()->json(['status' => true, 'message' => 'Job updated successfully']);
            }else{
                return response()->json(['status' => false, 'message' => 'somthing went wrong!']);
            }
        }
        catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th]);
        }


    }

    public function delete(Request $request){

        try
        {
            $UserId = $request->user()->token()->user_id;

            if(!empty($UserId)){

                $validation = Validator::make(
                    $request->all(), [
                        'id' => 'required',
                    ]
                );

                if ($validation->fails()) {
                    return response()->json(['status' => false, 'message' => $validation->errors()->first(), 'data' => (object) []]);
                }

               $data =  EmployerJob::where('id',$request->input('id'))->count();

                if($data != 0){

                    EmployerJob::find($request->input('id'))->delete();

                    return response()->json(['status' => true, 'message' => 'Job deleted successfully']);
                }else{

                    return response()->json(['status' => false, 'message' => 'Job Id not found!']);
                }

            }
        }
        catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th]);
        }
    }


}
