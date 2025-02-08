@extends('layouts.admin')
@section('content')
@can('user_create')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route("admin.RecruiterPartner.create") }}">
            {{ trans('global.add') }} Recruiter {{ trans('cruds.user.title_singular') }}
        </a>
    </div>
</div>
@endcan
<div class="card">
    <div class="card-header">
        Recruiter {{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="recruiterTbl" class=" table table-bordered table-striped table-hover datatable">
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
@include('admin.customJs.recruiter.index')


@endsection
