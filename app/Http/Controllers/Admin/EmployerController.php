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
use App\Http\Requests\MassDestroyUserRequest;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;


class EmployerController extends Controller
{
    public function checkEmailUniqueEmployer(Request $request)
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

        return view('admin.employer.index');
    }

    // public function getEmployerDatatable(Request $request)
    // {


    //     $employerRoleID = 3;
    //     $user_id = auth()->user()->id;
    //     $roleName =  getUserRole($user_id);


    //     $query = User::leftJoin('employer_jobs as e', 'users.id', '=', 'e.user_id')
    //         ->leftJoin('candidate_job_status_comments as c', function ($join) {
    //             $join->on('c.job_id', '=', 'e.id')
    //                 ->where(['c.status' => 'Final Selection', 'c.field_status' => 'Selected']);
    //         })
    //         ->select('users.*', DB::raw('COALESCE(COUNT(c.status), 0) as total_hired_candidates_count'))
    //         ->with('EmployerDetail')
    //         ->withCount('jobPosts as job_posts_count') // Count of job posts
    //         ->with(['AssignedContractDetail' => function ($query) {
    //             $query->with('ContractDetail');
    //         }])
    //         ->whereHas('roles', function ($query) use ($employerRoleID) {
    //             $query->where('role_id', $employerRoleID);
    //         })
    //         ->groupBy('users.id', 'users.first_name')
    //         ->OrderBy('users.id', 'desc');


    //     $searchableColumns = [
    //         'id',
    //         'first_name',
    //         'employer_detail.authorized_name',
    //         'employer_detail.company_name',
    //         'job_posts_count',
    //         'total_hired_candidates_count',
    //         'email',
    //         'employer_detail.phone_no',
    //         'employer_detail.location',

    //     ];

    //     foreach ($searchableColumns as $column) {
    //         if ($request->filled($column)) {
    //             // Use the original column name for the search condition
    //            $query->where($column, 'like', '%' . $request->input($column) . '%');
    //         }
    //     }

    //     // dd($query->get());


    //      return DataTables::of($query)
    //         ->addColumn('job_posts_count', function ($user) {
    //             return $user->job_posts_count != 0
    //                 ? '<a href="' . route('admin.employerJobs.alljobs', ['userId' => $user->id]) . '" target="_blank">' . $user->job_posts_count . '</a>'
    //                 : $user->job_posts_count;
    //         })
    //         ->addColumn('total_hired_candidates_count', function ($user) {
    //             return empty($user->total_hired_candidates_count)
    //                 ? '0'
    //                 : '<a href="' . route('admin.employerJobs.hiredJobsCandidates', ['userId' => $user->id]) . '" target="_blank">' . $user->total_hired_candidates_count . '</a>';
    //         })
    //         ->addColumn('actions', function ($user) use ($roleName) {
    //             $viewButton = '';
    //             $editButton = '';
    //             $addCalendlyButton = '';

    //             // View button
    //             if (Gate::allows('user_show')) {
    //                 $viewButton = '<a href="' . route('admin.employer.show', encrypt_data($user->id)) . '" class="btn btn-xs btn-primary">' . trans('global.view') . '</a>';
    //             }

    //             // Edit button
    //             if (Gate::allows('user_edit')) {
    //                 $editButton = '<a href="' . route('admin.employer.edit', encrypt_data($user->id)) . '" class="btn btn-xs btn-info">' . trans('global.edit') . '</a>';
    //             }

    //             // Add Calendly button
    //             $calendly_invitation = json_decode($user->EmployerDetail->calendly_invitation ?? null);

    //             if (
    //                 empty($user->EmployerDetail->calendly_invitation) ||
    //                 empty($user->EmployerDetail->calendly_details) ||
    //                 (!empty($user->EmployerDetail->calendly_invitation) && ($calendly_invitation->status == 'pending'))
    //             ) {
    //                 if (in_array($roleName, ['Super Admin', 'Admin', 'Employer']) && Gate::allows('user_edit')) {
    //                     $addCalendlyButton = '<button class="btn btn-xs btn-success add-calendly-btn" data-id="' . $user->id . '">Add to calendly</button>';
    //                 }
    //             }

    //             // Concatenate all buttons
    //             $actions = $viewButton . $editButton . $addCalendlyButton;

    //             return $actions;
    //         })
    //         ->rawColumns(['actions','job_posts_count','total_hired_candidates_count'])
    //         ->make(true);
    // }

    public function getEmployerDatatable(Request $request)
    {
        $employerRoleID = 3;
        $user_id = auth()->user()->id;
        $roleName = getUserRole($user_id);

            $query = User::leftJoin('employer_jobs as e', 'users.id', '=', 'e.user_id')
            ->leftJoin('candidate_job_status_comments as c', function ($join) {
                $join->on('c.job_id', '=', 'e.id')
                    ->where(['c.status' => 'Final Selection', 'c.field_status' => 'Selected']);
            })
            ->select('users.*', DB::raw('COALESCE(COUNT(c.status), 0) as total_hired_candidates_count'))
            ->with('EmployerDetail')
            ->withCount('jobPosts as job_posts_count') // Count of job posts
            ->with(['AssignedContractDetail' => function ($query) {
                $query->with('ContractDetail');
            }])
            ->whereHas('roles', function ($query) use ($employerRoleID) {
                $query->where('role_id', $employerRoleID);
            })
            ->groupBy('users.id', 'users.first_name')
            ->OrderBy('users.id', 'desc');

        $searchableColumns = [
            'id',
            'first_name',
            'EmployerDetail.authorized_name',
            'EmployerDetail.company_name',
            'job_posts_count',
            'total_hired_candidates_count',
            'email',
            'EmployerDetail.phone_no', // Modify the column name to search by EmployerDetail.phone_no
            'EmployerDetail.location',
        ];



        foreach ($searchableColumns as $column) {

            if ($request->filled($column)) {

                    $query->where($column, 'like', '%' . $request->input($column) . '%');

            }
        }
        return DataTables::of($query)
                ->addColumn('job_posts_count', function ($user) {
                    return $user->job_posts_count != 0
                        ? '<a href="' . route('admin.employerJobs.alljobs', ['userId' => $user->id]) . '" target="_blank">' . $user->job_posts_count . '</a>'
                        : $user->job_posts_count;
                })
                ->addColumn('total_hired_candidates_count', function ($user) {
                    return empty($user->total_hired_candidates_count)
                        ? '0'
                        : '<a href="' . route('admin.employerJobs.hiredJobsCandidates', ['userId' => $user->id]) . '" target="_blank">' . $user->total_hired_candidates_count . '</a>';
                })
                ->addColumn('actions', function ($user) use ($roleName) {
                    $viewButton = '';
                    $editButton = '';
                    $addCalendlyButton = '';

                    // View button
                    if (Gate::allows('user_show')) {
                        $viewButton = '<a href="' . route('admin.employer.show', encrypt_data($user->id)) . '" class="btn btn-xs btn-primary">' . trans('global.view') . '</a>';
                    }

                    // Edit button
                    if (Gate::allows('user_edit')) {
                        $editButton = '<a href="' . route('admin.employer.edit', encrypt_data($user->id)) . '" class="btn btn-xs btn-info">' . trans('global.edit') . '</a>';
                    }

                    // Add Calendly button
                    $calendly_invitation = json_decode($user->EmployerDetail->calendly_invitation ?? null);

                    if (
                        empty($user->EmployerDetail->calendly_invitation) ||
                        empty($user->EmployerDetail->calendly_details) ||
                        (!empty($user->EmployerDetail->calendly_invitation) && ($calendly_invitation->status == 'pending'))
                    ) {
                        if (in_array($roleName, ['Super Admin', 'Admin', 'Employer']) && Gate::allows('user_edit')) {
                            $addCalendlyButton = '<button class="btn btn-xs btn-success add-calendly-btn" data-id="' . $user->id . '">Add to calendly</button>';
                        }
                    }

                    // Concatenate all buttons
                    $actions = $viewButton . $editButton . $addCalendlyButton;

                    return $actions;
                })
                ->rawColumns(['actions','job_posts_count','total_hired_candidates_count'])
                ->make(true);
    }




    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $country = Country::all();
        $contracts = Contract::where('related_to', 'Employer')->get();


        return view('admin.employer.create', compact('country', 'contracts'));
    }

    public function store(Request $request)
    {
        //  dd($request->all());

        $userCreate = array(
            'first_name' => $request->input('first_name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'image' => 'default-user.jpg'
        );


        $user = User::create($userCreate);
        $userId = $user->id;

        $EmployeeCreate  = array(
            'user_id' => $userId,
            'authorized_name' => $request->input('authorized_name'),
            'company_name' => $request->input('company_name'),
            'phone_no' => $request->input('phone_no'),
            'location' => $request->input('location'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
        );

        $CreateEmployer = EmployerDetail::create($EmployeeCreate);


        $recurringContracts = [];
        $totalCheckboxes = count($request->contract_type);
        for ($i = 1; $i <= $totalCheckboxes; $i++) {
            $recurringContracts[] = false;
        }

        if ($request->has('recurring_contracts') && is_array($request->recurring_contracts)) {
            foreach ($request->recurring_contracts as $index) {
                $recurringContracts[$index] = true;
            }
        }

        $recurring_contracts = ['recurring_contracts' => $recurringContracts];


        for ($i = 1; $i <= count($request->contract_type); $i++) {
            $key = $i - 1;

            $AssignedContract = [];
            $AssignedContract['contract_id'] =  $request->contract_type[$i];
            $AssignedContract['user_id'] =  $userId;
            $AssignedContract['start_date'] = date('Y-m-d', strtotime($request->start_date[$i]));

            if ($request->end_date[$i]) {
                $AssignedContract['end_date'] = date('Y-m-d', strtotime($request->end_date[$i]));
            } else {
                $AssignedContract['end_date'] = null;
            }

            if ($request->hasFile('contract_upload')) {

                $contract_upload = $request->file('contract_upload')[$i];

                if (!empty($contract_upload)) {

                    $imageName = uniqid() . time() . '.' . $contract_upload->getClientOriginalExtension();
                    $contract_upload->move(public_path('contract_upload'), $imageName);
                    $AssignedContract['contract_upload'] =  $imageName;
                }
            }
            if ($request->contract_type[$i] == '1') {

                if (isset($request->checklist_upload[$i])) {

                    $checklist_upload = $request->file('checklist_upload')[$i];
                    if (!empty($checklist_upload)) {

                        $checklist_upload_name  = uniqid() . time() . '.' . $checklist_upload->getClientOriginalExtension();
                        $checklist_upload->move(public_path('checklist_upload'), $checklist_upload_name);
                    }


                    $AssignedContract['checklist_upload'] =  $checklist_upload_name;
                }
            }

            if ($recurring_contracts['recurring_contracts'][$key] == true) {

                $AssignedContract['recurring_contracts'] = '1';
            } else {
                $AssignedContract['recurring_contracts'] = '0';
            }


            $insert = AssignedContract::create($AssignedContract);
        }


        $user->roles()->sync(3);
        $userCreate['subject'] = "New employer created";
        $mailData = $userCreate;

        // User::where('email', $request->email)->update(['recover_password_link' => 1]);
        Mail::to($request->email)->send(new UserCreateSendCredential($mailData));


        return redirect()->route('admin.employer.index')->with('success', 'Employer Register successfully.');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = decrypt_data($id);

        $employerDetail = User::with(['EmployerDetail', 'AssignedContractDetail.ContractDetail'])->where('id', $id)->first();


        $country = Country::all();
        $state = State::where('id', $employerDetail->EmployerDetail->state_id)->get();
        $city = City::where('id', $employerDetail->EmployerDetail->city_id)->get();
        $contracts = Contract::where('related_to', 'Employer')->get();

        // dd($employerDetail);

        return view('admin.employer.edit', compact('employerDetail', 'country', 'state', 'city', 'contracts'));
    }

    public function update(Request $request, $id)
    {
        $getAllAssignContracts = AssignedContract::where('user_id', $id)->get()->pluck('id')->toArray();

        $deleteAllIds = array_diff($getAllAssignContracts, $request->assign_contract_id);
        AssignedContract::whereIn('id', $deleteAllIds)->delete();

        $recurringContracts = [];
        $totalCheckboxes = count($request->contract_type);

        for ($i = 1; $i <= $totalCheckboxes; $i++) {
            $recurringContracts[$i] = false;
        }


        if ($request->has('recurring_contracts') && is_array($request->recurring_contracts)) {
            foreach ($request->recurring_contracts as $index) {

                $recurringContracts[$index] = true;
            }
        }

        $recurring_contracts = ['recurring_contracts' => $recurringContracts];

        $data = [];

        $j =  0;
        $u = 0;

        for ($i = 1; $i <= count($request->contract_type); $i++) {

            $key = $i;
            // dd($key);
            if ($request->assign_contract_id[$key] == 0) {

                $data['insert_arr'][$j]['user_id'] = $id;
                $data['insert_arr'][$j]['contract_id'] = $request->contract_type[$key];
                $data['insert_arr'][$j]['start_date'] = date('Y-m-d', strtotime($request->start_date[$key]));

                if ($request->end_date[$key]) {
                    $data['insert_arr'][$j]['end_date'] = date('Y-m-d', strtotime($request->end_date[$key]));
                } else {
                    $data['insert_arr'][$j]['end_date'] = null;
                }

                if (isset($request->file('contract_upload')[$key])) {

                    $contract_upload = $request->file('contract_upload')[$key];

                    if (!empty($contract_upload)) {

                        $imageName = uniqid() . time() . '.' . $contract_upload->getClientOriginalExtension();
                        $contract_upload->move(public_path('contract_upload'), $imageName);
                        $data['insert_arr'][$j]['contract_upload'] = $imageName;
                    }
                }

                if ($request->input('contract_type')[$key] == 1) {
                    if (isset($request->checklist_upload[$key])) {

                        $checklist_upload = $request->file('checklist_upload')[$key];
                        if (!empty($checklist_upload)) {

                            $checklist_upload_name = uniqid() . time() . '.' . $checklist_upload->getClientOriginalExtension();
                            $checklist_upload->move(public_path('checklist_upload'), $checklist_upload_name);
                            $data['insert_arr'][$j]['checklist_upload'] = $checklist_upload_name;
                        }
                    }
                } else {

                    $data['insert_arr'][$j]['checklist_upload'] = null;
                }

                if ($recurring_contracts['recurring_contracts'][$i] == true) {

                    $data['insert_arr'][$j]['recurring_contracts'] = '1';
                } else {
                    $data['insert_arr'][$j]['recurring_contracts'] = '0';
                }
                $data['insert_arr'][$j]['created_at'] = date('Y-m-d H:i:s');
                $data['insert_arr'][$j]['updated_at'] = date('Y-m-d H:i:s');


                $j++;
            } else {

                $data['update_arr']['contract_id'] = $request->contract_type[$key];
                $data['update_arr']['start_date'] = date('Y-m-d', strtotime($request->start_date[$key]));

                if ($request->end_date[$key]) {
                    $data['update_arr']['end_date'] = date('Y-m-d', strtotime($request->end_date[$key]));
                } else {
                    $data['update_arr']['end_date'] = null;
                }

                if (isset($request->file('contract_upload')[$key])) {

                    $contract_upload = $request->file('contract_upload')[$key];

                    if (!empty($contract_upload)) {

                        $imageName = uniqid() . time() . '.' . $contract_upload->getClientOriginalExtension();
                        $contract_upload->move(public_path('contract_upload'), $imageName);
                        $data['update_arr']['contract_upload'] = $imageName;
                    }
                }

                if ($request->input('contract_type')[$key] == 1) {

                    if (isset($request->checklist_upload[$key])) {

                        $checklist_upload = $request->file('checklist_upload')[$key];
                        if (!empty($checklist_upload)) {

                            $checklist_upload_name = uniqid() . time() . '.' . $checklist_upload->getClientOriginalExtension();
                            $checklist_upload->move(public_path('checklist_upload'), $checklist_upload_name);
                            $data['update_arr']['checklist_upload'] = $checklist_upload_name;
                        }
                    }
                } else {

                    $data['update_arr']['checklist_upload'] = null;
                }

                if ($recurring_contracts['recurring_contracts'][$i] == true) {

                    $data['update_arr']['recurring_contracts'] = '1';
                } else {
                    $data['update_arr']['recurring_contracts'] = '0';
                }

                AssignedContract::where('id', $request->assign_contract_id[$key])->update($data['update_arr']);

                $u++;
            }
        }


        if (isset($data['insert_arr']) && ($data['insert_arr'] != 0)) {

            AssignedContract::insert($data['insert_arr']);
        }

        $employerDetail = User::with('EmployerDetail')
            ->where('id', $id)
            ->first();


        $employerDetail->first_name = $request->input('first_name');
        $employerDetail->EmployerDetail->authorized_name = $request->input('authorized_name');
        $employerDetail->EmployerDetail->phone_no = $request->input('phone_no');
        $employerDetail->email = $request->input('email');
        $employerDetail->EmployerDetail->company_name = $request->input('company_name');
        $employerDetail->EmployerDetail->location = $request->input('location');
        $employerDetail->EmployerDetail->latitude = $request->input('latitude');
        $employerDetail->EmployerDetail->longitude = $request->input('longitude');

        $employerDetail->status = $request->input('employerStatus');


        $employerDetail->save();
        $employerDetail->EmployerDetail->save();


        return redirect()->route('admin.employer.index')->with('success', 'Employer Update successfully.');;
    }

    public function show($id)
    {

        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = decrypt_data($id);


        $employerDetail = User::with(['EmployerDetail', 'AssignedContractDetail.ContractDetail'])->where('id', $id)->first();


        return view('admin.employer.show', compact('employerDetail'));
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
}
