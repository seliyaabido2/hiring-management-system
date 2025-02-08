<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Role;
use App\User;
use App\State;
use App\Country;
use App\Contract;
use App\AdminDetail;
use App\EmployerDetail;
use App\RecruiterDetail;
use App\AssignedContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreateSendCredential;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\MassDestroyUserRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class RecruiterPartnerController extends Controller
{
    public function checkEmailUniqueRecruiter(Request $request)
    {

        if ($request->ajax()) {

            if ($request->input('form') == 'create') {

                $email = $request->input('email');
                $user = User::where('email', $email)->first();
                if (!$user) {
                    return response()->json(true); // Email is unique
                } else {
                    return response()->json(false); // Email already exists
                }
            }

            if ($request->input('form') == 'update') {

                $userId = $request->input('userId');

                $email = $request->input('email');

                $user = User::where('email', $email)->where('id', '!=', $userId)->first();

                if (!$user) {
                    return response()->json(true); // Email is unique
                } else {
                    return response()->json(false); // Email already exists
                }
            }
        }
    }

    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $recruiterRoleID = 4;

        // $recruiterUser = User::with('RecruiterDetail')
        //     ->whereHas('roles', function ($query) use ($recruiterRoleID) {
        //         $query->where('role_id', $recruiterRoleID);
        //     })->OrderBy('id', 'desc')->get();

        // return view('admin.recruiter.index', compact('recruiterUser'));

        return view('admin.recruiter.index');

    }

    public function getRecruiterPartnerDatatable(Request $request)
    {
        $recruiterRoleID = 4;
    
        $query = User::select('users.*')
            ->with('RecruiterDetail')
            ->whereHas('roles', function ($query) use ($recruiterRoleID) {
                $query->where('role_id', $recruiterRoleID);
            })
            ->orderBy('users.id', 'desc');
    
        $searchableColumns = [
            'users.id',
            'users.first_name',
            'recruiter_details.authorized_name',
            'recruiter_details.company_name',
            'users.email',
            'recruiter_details.phone_no',
            'recruiter_details.location',
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
                    $viewButton = '<a href="' . route('admin.RecruiterPartner.show', encrypt_data($user->id)) . '" class="btn btn-xs btn-primary">' . trans('global.view') . '</a>';
                }
    
                // Edit button
                if (Gate::allows('user_edit')) {
                    $editButton = '<a href="' . route('admin.RecruiterPartner.edit', encrypt_data($user->id)) . '" class="btn btn-xs btn-info">' . trans('global.edit') . '</a>';
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
        $contracts = Contract::where('related_to', 'Recruiter')->get();


        return view('admin.recruiter.create', compact('country', 'contracts'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $userCreate = array(
            'first_name' => $request->input('first_name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'image' => 'default-user.jpg'
        );

        $user = User::create($userCreate);
        $role = 4;

        if ($role == 4) {

            $recruterCreate  = array(
                'user_id' => $user->id,
                'authorized_name' => $request->input('authorized_name'),
                'company_name' => $request->input('company_name'),
                'phone_no' => $request->input('phone_no'),
                'location' => $request->input('location'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
            );


            $recruterCreateInsert = RecruiterDetail::create($recruterCreate);

            if ($request->hasFile('contract_upload')) {

                $contract_upload = $request->file('contract_upload');

                if (!empty($contract_upload)) {

                    $imageName = time() . '.' . $contract_upload->getClientOriginalExtension();
                    $contract_upload->move(public_path('contract_upload'), $imageName);
                }
            }

            $AssignedContract = array(
                'contract_id' => $request->input('contract_type'),
                'user_id' => $user->id,
                'contract_upload' => $imageName,
                'start_date' => date('Y-m-d', strtotime($request->input('start_date'))),
                // 'end_date' => date('Y-m-d', strtotime($request->input('end_date')))
            );

            if ($request->has('recurring_contracts')) {

                $AssignedContract['recurring_contracts'] = '1';
            }

            if ($request->input('end_date')) {
                $AssignedContract['end_date'] = date('Y-m-d', strtotime($request->input('end_date')));
            } else {
                $AssignedContract['end_date'] = null;
            }
            $AssignedContract = AssignedContract::create($AssignedContract);
        }

        $user->roles()->sync($role);

        $userCreate['subject'] = "New Recruiter created";
        $mailData = $userCreate;

        // User::where('email', $request->email)->update(['recover_password_link' => 1]);
        Mail::to($request->email)->send(new UserCreateSendCredential($mailData));


        return redirect()->route('admin.RecruiterPartner.index')->with('success', 'Recruiter Register successfully.');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = decrypt_data($id);
        $recruiterDetail = User::with('RecruiterDetail')
            ->with('AssignedOneContractDetail.ContractDetail')
            ->where('id', $id)
            ->first();

        $country = Country::all();
        $state = State::where('id', $recruiterDetail->RecruiterDetail->state)->get();
        $city = City::where('id', $recruiterDetail->RecruiterDetail->city)->get();
        $contracts = Contract::where('related_to', 'Recruiter')->get();


        return view('admin.recruiter.edit', compact('recruiterDetail', 'country', 'state', 'city', 'contracts'));
    }

    public function update(Request $request, $id)
    {

        $recruiterDetail = User::with('RecruiterDetail')
            ->with('AssignedContractDetail.ContractDetail')
            ->where('id', $id)
            ->first();


        $recruiterDetail->first_name = $request->input('first_name');
        $recruiterDetail->RecruiterDetail->authorized_name = $request->input('authorized_name');
        $recruiterDetail->RecruiterDetail->phone_no = $request->input('phone_no');
        $recruiterDetail->email = $request->input('email');
        $recruiterDetail->RecruiterDetail->company_name = $request->input('company_name');
        $recruiterDetail->RecruiterDetail->location = $request->input('location');
        $recruiterDetail->RecruiterDetail->latitude = $request->input('latitude');
        $recruiterDetail->RecruiterDetail->longitude = $request->input('longitude');
        $recruiterDetail->AssignedOneContractDetail->contract_id = $request->input('contract_type');
        $recruiterDetail->AssignedOneContractDetail->start_date = date('Y-m-d', strtotime($request->input('start_date')));
        if ($request->input('end_date') != null) {
            $recruiterDetail->AssignedOneContractDetail->end_date = date('Y-m-d', strtotime($request->input('end_date')));
        } else {
            $recruiterDetail->AssignedOneContractDetail->end_date = null;
        }

        $recruiterDetail->status = $request->input('recruiterStatus');

        if ($request->hasFile('contract_upload')) {

            $contract_upload = $request->file('contract_upload');

            if (!empty($contract_upload)) {

                $imageName = time() . '.' . $contract_upload->getClientOriginalExtension();
                $contract_upload->move(public_path('contract_upload'), $imageName);
                $recruiterDetail->AssignedOneContractDetail->contract_upload = $imageName;
            }
        }

        if ($request->has('recurring_contracts')) {
            $recruiterDetail->AssignedOneContractDetail->recurring_contracts = '1';
        } else {
            $recruiterDetail->AssignedOneContractDetail->recurring_contracts = '0';
        }


        $recruiterDetail->save();
        $recruiterDetail->RecruiterDetail->save();
        $recruiterDetail->AssignedOneContractDetail->save();
        $recruiterDetail->AssignedOneContractDetail->ContractDetail->save();

        return redirect()->route('admin.RecruiterPartner.index')->with('success', 'Recruiter Update successfully.');;
    }

    public function show($id)
    {

        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = decrypt_data($id);

        $recruiterDetail = User::with('RecruiterDetail')
            ->with('AssignedOneContractDetail.ContractDetail')
            ->withCount('RecruiterCandidates')
            ->withCount(['candidateJobStatusComments' => function ($query) use ($id) {
                $query->where('status', "Final Selection")
                    ->where('field_status', "Selected");
            }])
            ->where('id', $id)
            ->first();

        // dd($recruiterDetail);


        return view('admin.recruiter.show', compact('recruiterDetail'));
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
