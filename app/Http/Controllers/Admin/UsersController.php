<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Role;
use App\User;
use App\AdminDetail;
use App\AssignedContract;
use App\City;
use App\Employee;
use App\EmployeeDetail;
use App\Country;
use App\EmployerDetail;
use App\RecruiterDetail;
use App\State;
use App\Mail\ForgotPasswordMail;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function getState(Request $request)
    {
        $id = $request->id;
        $state =  State::where(['country_id' => $id])->get();
        echo '<option value="">Select State</option>';
        foreach($state as $stateValue)
        {
            echo '<option value="'.$stateValue->id.'">'.$stateValue->name.'</option>';
        }
    }

    public function getCity(Request $request)
    {
        $id = $request->id;
        $city =  City::where(['state_id' => $id])->get();
        echo '<option value="">Select City</option>';
        foreach($city as $cityValue)
        {
            echo '<option value="'.$cityValue->id.'">'.$cityValue->name.'</option>';
        }
    }

    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::where('id','!=',1)->get();
        
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');
        $country = Country::all();


        return view('admin.users.create', compact('roles','country'));
    }

    public function store(Request $request)
    {
        $userCreate = array(
                        'first_name' => $request->input('first_name'),
                        'last_name' => $request->input('last_name'),
                        'email' => $request->input('email'),
                        'password' => $request->input('password')
                     );



        $user = User::create($userCreate);
        $role = $request->input('role');


        if($role == 2){
            $image = $request->file('image');
           

            if($image != null){
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('user_image'), $imageName);
            }else{
                $imageName = 'NULL';
            }
            


            $adminCreate  = array(
                                'user_id' => $user->id,
                                'company_name' => $request->input('company_name'),
                                'company_type' => $request->input('company_type'),
                                'image' => $imageName,
                            );

            $adminCreate = AdminDetail::create($adminCreate);


        }

        if($role == 3){

            $image = $request->file('image');
            if($image != null){
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('user_image'), $imageName);
            }else{
                $imageName = 'NULL';
            }

            $EmployeeCreate  = array(
                'user_id' => $user->id,
                'company_name' => $request->input('emp_company_name'),
                'phone_no' => $request->input('phone_no'),
                'image' => $imageName,
            );

            $EmployeeCreate = EmployerDetail::create($EmployeeCreate);

        }

        if($role == 4){

            $image = $request->file('image');
            if($image != null){
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('user_image'), $imageName);
            }else{
                $imageName = 'NULL';
            }

            $recruterCreate  = array(
                'user_id' => $user->id,
                'company_name' => $request->input('req_company_name'),
                'phone_no' => $request->input('req_phone_no'),
                'image' => $imageName,

            );

            $recruterCreate = RecruiterDetail::create($recruterCreate);

        }


        $user->roles()->sync($role);

        return redirect()->route('admin.users.index')->with('success', 'user Register successfully.');

    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        $user->load('roles');
        $userData = User::where('id', $user->id)->with('roles');

        $country = '';
        $state = '';
        $city = '';

        $RoleName = getUserRole($user->id);

        if ($RoleName == 'Admin') {
            $userData->with('AdminDetail');
        }

        if ($RoleName == 'Employer') {
            $userData->with('EmployerDetail');
        }

        if ($RoleName == 'Recruiter') {
            $userData->with('RecruiterDetail');

            $userDetail = $userData->first();

            $country = Country::all();
            $state = State::where('id',$userDetail->RecruiterDetail->state)->get();
            $city = City::where('id',$userDetail->RecruiterDetail->city)->get();
        }

        $userDetail = $userData->first();

        return view('admin.users.edit', compact('roles', 'user','userDetail','country','state','city'));
    }

    public function update(Request $request, User $user)
    {

        $id = $request->input('user_id');
        $roleId = $request->input('RoleId');

        $userUpdate = User::where('id',$id)->first();
        $userUpdate->first_name = $request->input('first_name');
        $userUpdate->last_name = $request->input('last_name');
        $userUpdate->email = $request->input('email');

        $userUpdate->save();

        $RoleName = $request->input('RoleName');
        $image = $request->file('image');            

        if($RoleName == 'Admin'){


            $adminUpdate = AdminDetail::where('user_id',$id)->first();

            if(!empty($image)){
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('user_image'), $imageName);

                $adminUpdate->image = $imageName;
            }

            $adminUpdate->company_name = $request->input('company_name');
            $adminUpdate->company_type = $request->input('company_type');

            $adminUpdate->save();

        }

        if($RoleName == 'Employer'){

            $EmployerUpdate = EmployerDetail::where('user_id',$id)->first();

            if(!empty($image)){
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('user_image'), $imageName);

                $EmployerUpdate->image = $imageName;
            }

            $EmployerUpdate->company_name = $request->input('emp_company_name');
            $EmployerUpdate->phone_no = $request->input('phone_no');

            $EmployerUpdate->save();

        }

        if($RoleName == 'Recruiter'){

            $image = $request->file('image');
            $recruterUpdate = RecruiterDetail::where('user_id',$id)->first();

            if(!empty($image)){

                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('user_image'), $imageName);
                $recruterUpdate->image = $imageName;

            }

            $id = $request->input('user_id');
            $recruterUpdate->company_name = $request->input('req_company_name');
            $recruterUpdate->phone_no = $request->input('req_phone_no');
            // $recruterUpdate->city = $request->input('city_id');
            // $recruterUpdate->state = $request->input('state_id');
            // $recruterUpdate->country = $request->input('country_id');

            $recruterUpdate->save();

        }

        $user->roles()->sync($request->input('roles', [$roleId]));

        return redirect()->route('admin.users.index')->with('success', 'user Update successfully.');;
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');

        $userData = User::where('id', $user->id)->with('roles');
        $RoleName = getUserRole($user->id);

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
        return view('admin.users.show', compact('user','userDetail'));
    }

    public function destroy(Request $request)
    {
        // abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = $request->input('id');
        User::find($id)->delete();

        return back()->with('success', 'user deleted successfully.');;

    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function showChangePasswordForm()
    {
        $userid =Auth::user()->id;
        $user = User::where('id',$userid)->first();
        return view('admin.users.changePassword', compact('user'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            // 'password' => 'required|string|min:8|confirmed',
        ]);


        $user = auth()->user();


        if (!\Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Incorrect current password']);
        }

        $user->password = \Hash::make($request->input('password'));
        $user->save();

        return redirect()->route('admin.users.password.change')->with('success', 'Password Changed successfully.');
    }

    public function forgetPassword(Request $request)
    {
        try {

            $checkEmail = User::where('email', $request->email)->first();

            if (isset($checkEmail) && $checkEmail !== '') {

                $enEmail = Crypt::encryptString($request->email);
                $url = URL::to('/admin/reset-password/'.$enEmail);


                $mailData = [
                    'name' => $checkEmail->first_name.' '.$checkEmail->last_name,
                    'url' => $url,
                ];

                // User::where('email', $request->email)->update(['recover_password_link' => 1]);
                Mail::to($request->email)->send(new ForgotPasswordMail($mailData));

                return redirect()->route('admin.home')->with('success', 'Reset Link send your email address sucessfully');

                // return ['status' => true, 'message' => 'Reset Link send your email address sucessfully', 'data' => new stdClass()];
            } else {

                return redirect()->back()->with('error', 'Email does not exits');

                // return ['status' => false, 'message' => 'Email does not exits', 'data' => new stdClass()];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Something went wrong');

            // return ['status' => false, 'message' => 'Something went wrong', 'data' => new stdClass()];
        }
    }

    public function profileEdit()
    {
        $userid = Auth::user()->id;
        $user = Auth::user();
        $roles = Role::all()->pluck('title', 'id');

        $user->load('roles');
        $userData = User::where('id', $userid)->with('roles');

        $country = '';
        $state = '';
        $city = '';
        $model = '';
       

        $RoleName = getUserRole($userid);

        if ($RoleName == 'Admin') {
            $userData->with('AdminDetail');
            $model = 'AdminDetail';
        }

        if ($RoleName == 'Employer') {
            $userData->with('EmployerDetail');
            $userData->with('AssignedContractDetail');
            $model = 'EmployerDetail';
            
        }

        if ($RoleName == 'Recruiter') {
            $country = Country::all();
            $userData->with('RecruiterDetail');
            $userData->with('AssignedContractDetail');
            $model = 'RecruiterDetail';
            
            $RecruiterDetail = $userData->first();
            $state = State::where('id',$RecruiterDetail->RecruiterDetail->state)->get();
            $city = City::where('id',$RecruiterDetail->RecruiterDetail->city)->get();
        }



        $userDetail = $userData->first();

        return view('admin.users.profileUpdate', compact('roles','model', 'user','userDetail','country','state','city'));

    }


    public function profileUpdate(Request $request)
    {
        $id = Auth::user()->id;
        // $roleId = $request->input('RoleId');
        $RoleName = getUserRole($id);

        $userUpdate = User::where('id',$id)->first();
        $userUpdate->first_name = $request->input('first_name');
        if($request->has('last_name')){
            $userUpdate->last_name = $request->input('last_name');
        }
        $userUpdate->email = $request->input('email');
        $image = $request->file('image');
        if(!empty($image)){
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('user_image'), $imageName);
            $userUpdate->image = $imageName;

        }
       

        $userUpdate->save();

      

        if($RoleName == 'Admin'){

            $adminUpdate = AdminDetail::where('user_id',$id)->first();

            $adminUpdate->location = $request->input('location');
            $adminUpdate->latitude = $request->input('latitude');
            $adminUpdate->longitude = $request->input('longitude');

            $adminUpdate->company_name = $request->input('company_name');
            $adminUpdate->company_type = $request->input('company_type');

            $adminUpdate->save();

        }

        if($RoleName == 'Employer'){

            $EmployerUpdate = EmployerDetail::where('user_id',$id)->first();

            $EmployerUpdate->location = $request->input('location');
            $EmployerUpdate->latitude = $request->input('latitude');
            $EmployerUpdate->longitude = $request->input('longitude');

            $EmployerUpdate->company_name = $request->input('emp_company_name');
            $EmployerUpdate->phone_no = $request->input('phone_no');

            $EmployerUpdate->save();

        }

        if($RoleName == 'Recruiter'){

            $recruterUpdate = RecruiterDetail::where('user_id',$id)->first();

            $recruterUpdate->location = $request->input('location');
            $recruterUpdate->latitude = $request->input('latitude');
            $recruterUpdate->longitude = $request->input('longitude');

            $recruterUpdate->company_name = $request->input('req_company_name');
            $recruterUpdate->phone_no = $request->input('req_phone_no');
           
            $recruterUpdate->save();

        }

        // $user->roles()->sync($request->input('roles', [$roleId]));

        return redirect()->to('admin')->with('success', 'profile Update successfully.');;
    }

    public function viewContract(){
            
        $userId = Auth::user()->id;        
        if(Auth::user()->roles->first()->title == 'Employer'){

            $contract = User::with(['AssignedContractDetail.ContractDetail'])->where('id', $userId)->first();


        }else{

            $contract = User::with('AssignedOneContractDetail.ContractDetail')
            ->where('id', $userId)->first();

        }
       
        //  dd($contract);
       
        return view('admin.users.view_contract', compact('contract'));
       

    }


    public function GoogleAutocomplete() {
        return view('admin.users.googleAutocomplete');

    }

}
