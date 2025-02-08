<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\AppliedJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Candidate;
use App\City;
use App\Country;
use App\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class CandidateController extends Controller
{
    public function index(Request $request)
    {
        //abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try{
            $UserId = $request->user()->token()->user_id;

            $candidates = Candidate::all();

            return response()->json(['status' => true, 'data' => $candidates ]);

        }catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th]);
        }

    }

    public function store(Request $request)
    {
        try
        {

            $UserId = $request->user()->token()->user_id;

            $newCandidateValidation = [
                'candidate_name' => 'required',
                'date_of_birth' => 'required',
                'gender' => 'required',
                'email' => ['required','email',Rule::unique('candidates')->whereNull('deleted_at'),],
                'contact_number' => 'required',
                'address' => 'required',
                'pin_code' => 'required',
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

            $validation = Validator::make(

                $request->all(), $newCandidateValidation
            );

            if ($validation->fails()) {
                return response()->json(['status' => false, 'message' => $validation->errors()->first()]);
            }

            $candidate_id = generateUniqueCandidateId();

            $candidateDataArr = [
                'candidate_id' =>  $candidate_id,
                'user_id' => $UserId,
                'name' => $request->input('candidate_name'),
                'date_of_birth' => date('Y-m-d',strtotime($request->input('date_of_birth'))),
                'gender' => $request->input('gender'),
                'email' => $request->input('email'),
                'contact_no' => $request->input('contact_number'),
                'address' => $request->input('address'),
                'country_id' => $request->input('country_id'),
                'state_id' => $request->input('state_id'),
                'city_id' => $request->input('city_id'),
                // 'pincode' => $request->input('pin_code'),
                'experience_sf' => $request->input('experience_sf'),
                'experience_without_sf' => $request->input('experience_without_sf'),
                'license_candidate_basic_training' => $request->input('license_candidate_basic_training'),
                'license_candidate_no_experience' => $request->input('license_candidate_no_experience'),
                'license_candidate_banking_finance' => $request->input('license_candidate_banking_finance'),
                'language_preference' => $request->input('language_preference'),
                'license_requirement' => $request->input('license_requirement'),
                'resume' => $request->input('resume'),
                'additional_information' => $request->input('additional_information'),
            ];

            if($request->input('license_requirement') == 'other'){
                $arrNew = array('other_license_requirement'=>$request->input('other_license_requirement'));
                $candidateDataArr =  array_merge($candidateDataArr, $arrNew);
            }

            $resume = $request->file('resume');

            if(!empty($resume)){

                $resumeName = time() . '.' . $resume->getClientOriginalExtension();
                $resume->move(public_path('candidate_resume'), $resumeName);
                $arrNew = array('resume'=>$resumeName);
                $candidateDataArr =  array_merge($candidateDataArr, $arrNew);

            }

            $candidate = Candidate::create($candidateDataArr);


            if($candidate != ''){
                return response()->json(['status' => true, 'message' => 'candidate created successfully.']);
            }else{
                return response()->json(['status' => false, 'message' => 'somthing went wrong in candidate record insert table!']);
            }

        }catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th]);
        }

    }

    public function update(Request $request)
    {

        try
        {
            // get user id
            $UserId = $request->user()->token()->user_id;

            // Candidate Id Validation
            $validation = Validator::make(

                $request->all(), [
                    'id' => 'required',
                ]
            );

            if ($validation->fails()) {
                return response()->json(['status' => false, 'message' => $validation->errors()->first()]);
            }

            // get candidate Id
            $id = $request->input('id');

            // check candidate is exist or not
            $checkIsExist = Candidate::where('id',$id)->count();

            if($checkIsExist == 0){
                return response()->json(['status' => false, 'message' => 'candidate Not Found Please Enter Valid id.' ]);
            }

            // Candidate data fields Validation
            $newCandidateValidation = [
                'candidate_name' => 'required',
                'date_of_birth' => 'required',
                'gender' => 'required',
                'email' => ['required','email',Rule::unique('candidates')->ignore($id)->whereNull('deleted_at'),],
                'contact_number' => 'required',
                'address' => 'required',
                'pin_code' => 'required',
                'experience_sf' => 'required',
                'experience_without_sf' => 'required',
                'license_candidate_basic_training' => 'required',
                'license_candidate_no_experience' => 'required',
                'license_candidate_banking_finance' => 'required',
                'language_preference' => 'required',
                'license_requirement' => 'required',
                'status'  => 'required',
            ];

            if($request->input('license_requirement') == 'other'){

                $arr = array('other_license_requirement'=> 'required');
                $newCandidateValidation =  array_merge($newCandidateValidation, $arr);
            }


            $newValidation = Validator::make(

                $request->all(), $newCandidateValidation
            );

            if ($newValidation->fails()) {
                return response()->json(['status' => false, 'message' => $newValidation->errors()->first()]);
            }

            // create Array for insert Candidate data

            $candidateDataArr = [
                'name' => $request->input('candidate_name'),
                'date_of_birth' => date('Y-m-d',strtotime($request->input('date_of_birth'))),
                'gender' => $request->input('gender'),
                'email' => $request->input('email'),
                'contact_no' => $request->input('contact_number'),
                'address' => $request->input('address'),
                'country_id' => $request->input('country_id'),
                'state_id' => $request->input('state_id'),
                'city_id' => $request->input('city_id'),
                // 'pincode' => $request->input('pin_code'),
                'experience_sf' => $request->input('experience_sf'),
                'experience_without_sf' => $request->input('experience_without_sf'),
                'license_candidate_basic_training' => $request->input('license_candidate_basic_training'),
                'license_candidate_no_experience' => $request->input('license_candidate_no_experience'),
                'license_candidate_banking_finance' => $request->input('license_candidate_banking_finance'),
                'language_preference' => $request->input('language_preference'),
                'license_requirement' => $request->input('license_requirement'),
                'additional_information' => $request->input('additional_information'),
                'status' => $request->input('status'),

            ];

            if($request->input('license_requirement') == 'other'){
                $arrNew = array('other_license_requirement'=>$request->input('other_license_requirement'));
                $candidateDataArr =  array_merge($candidateDataArr, $arrNew);
            }

            $resume = $request->file('resume');

            if(!empty($resume)){

                $resumeName = time() . '.' . $resume->getClientOriginalExtension();
                $resume->move(public_path('candidate_resume'), $resumeName);
                $arrNew = array('resume'=>$resumeName);
                $candidateDataArr =  array_merge($candidateDataArr, $arrNew);
            }

            $status = Candidate::where('id', $id)->update($candidateDataArr);

            if($status == true){
                return response()->json(['status' => true, 'message' => 'candidate updated successfully.' ]);
            }else{
                return response()->json(['status' => false, 'message' => 'candidate updated Query Error.' ]);
            }
        }
        catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th]);
        }
    }


    public function destroy(Request $request)
    {
        $UserId = $request->user()->token()->user_id;

        $candidate_id = $request->input('candidate_id');

         // check candidate is exist or not
         $checkIsExist = Candidate::where('candidate_id',$candidate_id)->count();

         if($checkIsExist == 0){
             return response()->json(['status' => false, 'message' => 'candidate Not Found Please Enter Valid candidate id.' ]);
         }

        AppliedJob::where('candidate_id',$candidate_id)->delete();
        $status = Candidate::where('candidate_id',$candidate_id)->delete();

        if($status == true){
            return response()->json(['status' => true, 'message' => 'candidate Deleted successfully.' ]);
        }else{
            return response()->json(['status' => false, 'message' => 'candidate Delete Query Error.' ]);
        }

    }


}
