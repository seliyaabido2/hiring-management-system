<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Role;
use App\User;
use App\AdminDetail;
use App\AssignedContract;
use App\BodCandidate;
use App\Candidate;
use App\City;
use App\Contract;
use App\Country;
use App\EmployerDetail;
use App\Mail\UserCreateSendCredential;
use App\RecruiterDetail;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;

class SubAdminController extends Controller
{

    public function checkEmailUniqueCandidate(Request $request)  {

        if ($request->ajax()) {

            $userId =Auth::user()->id;
            $roleName = getUserRole($userId);


            if( $request->input('form') == 'create'){
                $email = $request->input('email');
                if($roleName == 'Super Admin' || $roleName == 'Admin'){
                    $user = BodCandidate::where('email', $email)->first();
                }else{

                    $user = Candidate::where('email', $email)->first();

                }

                if (!$user) {
                    return response()->json(true); // Email is unique
                }else{
                    return response()->json(false); // Email already exists
                }
            }

            if( $request->input('form') == 'update'){

                $userId = $request->input('userId');

                $email = $request->input('email');
                if($roleName == 'Super Admin' || $roleName == 'Admin'){
                    $user = BodCandidate::where('email', $email)->where('id','!=',$userId)->first();
                }else{
                    $user = Candidate::where('email', $email)->where('id','!=',$userId)->first();

                }

                if (!$user) {
                    return response()->json(true); // Email is unique
                }else{
                    return response()->json(false); // Email already exists
                }
            }




        }

    }

    public function checkEmailUniqueAdmin(Request $request)  {

         if ($request->ajax()) {

            if( $request->input('form') == 'create'){

                $email = $request->input('email');
                $user = User::where('email', $email)->first();
                if (!$user) {
                    return response()->json(true); // Email is unique
                }else{
                    return response()->json(false); // Email already exists
                }
            }

            if( $request->input('form') == 'update'){

                $userId = $request->input('userId');

                $email = $request->input('email');

                $user = User::where('email', $email)->where('id','!=',$userId)->first();

                if (!$user) {
                    return response()->json(true); // Email is unique
                }else{
                    return response()->json(false); // Email already exists
                }
            }




        }
    }

    public function index()
    {

        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $adminRoleID = 2;

        // $adminUser = User::with('AdminDetail')
        //                 ->whereHas('roles', function ($query) use ($adminRoleID) {
        //                     $query->where('role_id', $adminRoleID);
        //                 })->OrderBy('id', 'desc')->get();


        return view('admin.subAdmin.index');
    }

    public function getSubAdminDatatable(Request $request)
    {
        $adminRoleID = 2;
    
        $query = User::select('users.*')
            ->with('AdminDetail')
            ->whereHas('roles', function ($query) use ($adminRoleID) {
                $query->where('role_id', $adminRoleID);
            })
            ->orderBy('users.id', 'desc');
    
        $searchableColumns = [
            'users.id',
            'users.first_name',
            'users.email',
            'admin_details.phone_no',
            'admin_details.designation',
            'admin_details.location',
        ];
    
        foreach ($searchableColumns as $column) {
            if ($request->filled($column)) {
                // Use proper table aliases
                $query->where($column, 'like', '%' . $request->input($column) . '%');
            }
        }
    
        return DataTables::of($query)
            ->addColumn('actions', function ($user) {
                $viewButton = '';
                $editButton = '';
    
                // View button
                if (Gate::allows('user_show')) {
                    $viewButton = '<a href="' . route('admin.SubAdmin.show', encrypt_data($user->id)) . '" class="btn btn-xs btn-primary">' . trans('global.view') . '</a>';
                }
    
                // Edit button
                if (Gate::allows('user_edit')) {
                    $editButton = '<a href="' . route('admin.SubAdmin.edit', encrypt_data($user->id)) . '" class="btn btn-xs btn-info">' . trans('global.edit') . '</a>';
                }
    
                // Concatenate all buttons
                $actions = $viewButton . $editButton;
    
                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $country = Country::all();

        return view('admin.subAdmin.create', compact('country'));
    }

    public function store(Request $request)
    {

        $userCreate = array(
                        'first_name' => $request->input('first_name'),
                        'email' => $request->input('email'),
                        'password' => $request->input('password'),
                        'image' => 'default-user.jpg'
                     );

        $user = User::create($userCreate);
        $role = 2;

        if($role == 2){

            $adminCreate  = array(
                'user_id' => $user->id,
                'company_name' => $request->input('company_name'),
                'company_type' => $request->input('company_type'),
                'location' => $request->input('location'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'phone_no' => $request->input('phone_no'),
                'designation' => $request->input('designation'),
            );


            $adminCreate = AdminDetail::create($adminCreate);

        }

        $user->roles()->sync($role);

        $userCreate['subject']="New Admin created";
        $mailData = $userCreate;

        // User::where('email', $request->email)->update(['recover_password_link' => 1]);
        Mail::to($request->email)
            ->send(new UserCreateSendCredential($mailData));

        return redirect()->route('admin.SubAdmin.index')->with('success', 'Admin Register successfully.');

    }

    public function edit($id)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = decrypt_data($id);
        $adminDetail = User::with('AdminDetail')
                                ->where('id',$id)
                                ->first();

        $country = Country::all();
        $state = State::where('id',$adminDetail->AdminDetail->state)->get();
        $city = City::where('id',$adminDetail->AdminDetail->city)->get();


        return view('admin.subAdmin.edit', compact('adminDetail','country','state','city',));
    }

    public function update(Request $request,$id)
    {


        $adminDetail = User::with('AdminDetail')
                                ->where('id',$id)
                                ->first();


        $adminDetail->first_name = $request->input('first_name');
        $adminDetail->email = $request->input('email');

        $adminDetail->AdminDetail->location = $request->input('location');
        $adminDetail->AdminDetail->latitude = $request->input('latitude');
        $adminDetail->AdminDetail->longitude = $request->input('longitude');
        $adminDetail->AdminDetail->phone_no = $request->input('phone_no');
        $adminDetail->AdminDetail->designation = $request->input('designation');
        $adminDetail->status = $request->input('status');


        if(!empty($request->input('password'))){
            $adminDetail->password = \Hash::make($request->input('password'));

        }

        $adminDetail->save();
        $adminDetail->AdminDetail->save();

        return redirect()->route('admin.SubAdmin.index')->with('success', 'Admin Update successfully.');;
    }

    public function show($id)
    {

        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = decrypt_data($id);
        $adminDetail = User::with('AdminDetail')
                                ->where('id',$id)
                                ->first();

        return view('admin.subAdmin.show', compact('adminDetail'));
    }

    // public function destroy(Request $request)
    // {
    //     // abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     $id = $request->input('id');
    //     User::find($id)->delete();

    //     return back()->with('success', 'user deleted successfully.');;

    // }

    // public function massDestroy(MassDestroyUserRequest $request)
    // {
    //     User::whereIn('id', request('ids'))->delete();

    //     return response(null, Response::HTTP_NO_CONTENT);
    // }


}
