<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\AdminDetail;
use App\EmployerDetail;
use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\RecruiterDetail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Illuminate\Support\Facades\Password;



class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {

            $authUser = Auth::user();
            $userid = $authUser->id;

            $token = $authUser->createToken('API Token')->accessToken;

            $upadteToken = User::find($userid);
            $upadteToken->access_token = $token;
            $upadteToken->save();

            $RoleName = getUserRole($userid);

            $GetAuthUserDetail = $this->GetAuthUserDetail($RoleName, $userid);

            return response()->json([
                                     'status' => 'success',
                                    //  'user' => $user,
                                     'data' => $GetAuthUserDetail,
                                     'token' => $token,
                                    ], 200);
        } else {
            return response()->json(['status' => 'error','message' => 'Invalid User Name Or Password!'], 401);
        }
    }

    public function getProfile(Request $request){

        $UserId = $request->user()->token()->user_id;

        $RoleName = getUserRole($UserId);

        $GetAuthUserDetail = $this->GetAuthUserDetail($RoleName, $UserId);

        return response()->json([
            'status' => 'success',
            'data' => $GetAuthUserDetail
           ], 200);
    }

    public function profileUpdate(Request $request){

        try
        {
            $validation = Validator::make(
                $request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email',
                ]
            );

            if ($validation->fails()) {
                return response()->json(['status' => false, 'message' => $validation->errors()->first()]);
            }

            $UserId = $request->user()->token()->user_id;

            $RoleName = getUserRole($UserId);


            if($RoleName == 'Admin'){

                $validation = Validator::make(
                    $request->all(), [
                        'company_name' => 'required',
                        'company_type' => 'required',
                    ]
                );

                if ($validation->fails()) {
                    return response()->json(['status' => false, 'message' => $validation->errors()->first()]);
                }

                $adminUpdate = AdminDetail::where('user_id',$UserId)->first();

                $adminUpdate->company_name = $request->input('company_name');
                $adminUpdate->company_type = $request->input('company_type');

                $adminUpdate->save();

            }

            if($RoleName == 'Employer'){

                $validation = Validator::make(
                    $request->all(), [
                        'company_name' => 'required',
                        'phone_no' => 'required',
                    ]
                );

                if ($validation->fails()) {
                    return response()->json(['status' => false, 'message' => $validation->errors()->first()]);
                }

                $EmployerUpdate = EmployerDetail::where('user_id',$UserId)->first();


                $EmployerUpdate->company_name = $request->input('company_name');
                $EmployerUpdate->phone_no = $request->input('phone_no');

                $EmployerUpdate->save();

            }

            if($RoleName == 'Recruiter'){

                $validation = Validator::make(
                    $request->all(), [
                        'image' => 'required',
                        'company_name' => 'required',
                        'phone_no' => 'required',
                       
                    ]
                );

                if ($validation->fails()) {
                    return response()->json(['status' => false, 'message' => $validation->errors()->first()]);
                }

                $image = $request->file('image');
                $recruterUpdate = RecruiterDetail::where('user_id',$UserId)->first();

                if(!empty($image)){

                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('user_image'), $imageName);
                    $recruterUpdate->image = $imageName;

                }

                $recruterUpdate->company_name = $request->input('company_name');
                $recruterUpdate->phone_no = $request->input('phone_no');
                $recruterUpdate->city = $request->input('city_id');
                $recruterUpdate->state = $request->input('state_id');
                $recruterUpdate->country = $request->input('country_id');

                $recruterUpdate->save();

            }

            $userUpdate = User::where('id',$UserId)->first();
            $userUpdate->first_name = $request->input('first_name');
            $userUpdate->last_name = $request->input('last_name');
            $userUpdate->email = $request->input('email');

            $userUpdate->save();

            $GetAuthUserDetail = $this->GetAuthUserDetail($RoleName, $UserId);

            return response()->json(['status' => true, 'data' => $GetAuthUserDetail]);

            // return response($data, 200);


        }
        catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th]);
        }

    }

    public function GetAuthUserDetail($RoleName, $UserId){

        $userData = User::where('id', $UserId)->with('roles');

            if ($RoleName == 'Admin') {
                $userData->with('AdminDetail');
            }

            if ($RoleName == 'Employer') {
                $userData->with('EmployerDetail');
            }

            if ($RoleName == 'Recruiter') {
                $userData->with('RecruiterDetail');
            }

            $userDetail = $userData->first();

            if ($RoleName == 'Super Admin') {
                $userDetail = [];
            }

            return $userDetail;

    }

    public function passwordChange(Request $request){

        $validation = Validator::make(
            $request->all(), [
                'current_password' => 'required',
                'password' => 'required|string|min:8',
                'confirm_password' => 'required|string|min:8',
            ]
        );

        if ($validation->fails()) {
            return response()->json(['status' => false, 'message' => $validation->errors()->first()]);
        }

        $user = auth()->user();

        if (!\Hash::check($request->input('current_password'), $user->password)) {

            return response()->json(['status' => false, 'message' => 'Incorrect current password']);
        }
        else if($request->input('password') != $request->input('confirm_password')){

            return response()->json(['status' => false, 'message' => 'confirm password do Not match']);

        }else{

            $user->password = \Hash::make($request->input('password'));

            $user->save();

            return response()->json(['status' => true, 'message' => 'Password Changed Sucessfully']);

        }


    }

    public function forgetPasswordMailsend(Request $request) {

        $validation = Validator::make(
            $request->all(), [
                'email' => 'required|email',
            ]
        );

        if ($validation->fails()) {
            return response()->json(['status' => false, 'message' => $validation->errors()->first()]);
        }

        $email = $request->input('email');

        $checkEmail = User::where('email', $email)->first();

        if (isset($checkEmail) && $checkEmail !== '') {

            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );

            if ($response === Password::RESET_LINK_SENT) {
                return response()->json(['status' => true,'message' => 'Password reset link sent','link' => Password::RESET_LINK_SENT]);
            }

        }
        else{

            return response()->json(['status' => false, 'message' => 'Email does not exits']);
        }
    }

    protected function broker()
    {
        return Password::broker();
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response['status'] = true;
        $response['message'] = 'You have been successfully logged out!';
        // $response['data'] = new stdClass();

        return response($response, 200);
    }
}
