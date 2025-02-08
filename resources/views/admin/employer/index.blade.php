@extends('layouts.admin')
@section('content')
@can('user_create')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route("admin.employer.create") }}">
            {{ trans('global.add') }} Employer {{ trans('cruds.user.title_singular') }}
        </a>
        <a class="btn btn-info" target="_blank" href="https://calendly.com/app/admin/user_groups/244733/users">
            Add calendly group
        </a>
    </div>
</div>
@endcan
@php
    $user_id = auth()->user()->id;
    $roleName =  getUserRole($user_id);
@endphp
<div class="card">
    <div class="card-header">
        Employer {{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="employerTbl" class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.employer.fields.client_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.employer.fields.authorized_person_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.company_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.employer.fields.job_posted') }}
                        </th>
                        <th>
                            {{ trans('cruds.employer.fields.hired_candidates') }}
                        </th>
                        <th>
                            {{ trans('cruds.employer.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.employer.fields.phone_number') }}
                        </th>
                       <th>
                        Location
                       </th>
                        <th>
                            {{ trans('cruds.employer.fields.status') }}
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>


    </div>
</div>

@endsection


@section('scripts')
@parent
@include('admin.customJs.employer.index')


@endsection
