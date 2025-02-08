@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">

            {{ trans('cruds.appliedJobs.fields.recent_top_20_record') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="recentUupdatesStatusTbl" class=" table table-bordered table-striped table-hover datatable">
                    <thead>
                        <tr>

                            <th>
                                Company Id
                                {{-- {{ trans('cruds.user.fields.company_name') }} --}}
                            </th>
                            <th>
                                {{ trans('cruds.employerJobs.fields.job_title') }}
                            </th>
                            <th>
                                {{ trans('cruds.employerJobs.fields.candidate_name') }}
                            </th>
                            <th>
                                {{ trans('cruds.employerJobs.fields.job_round') }}
                            </th>
                            <th>
                                {{ trans('cruds.employerJobs.fields.job_status') }}
                            </th>
                            <th>
                                {{ trans('cruds.appliedJobs.fields.recent_notes') }}
                            </th>
                            <th>
                                {{ trans('cruds.appliedJobs.fields.resume') }}
                            </th>

                            <th>Actions&nbsp;</th>
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

    @include('admin.customJs.appliedJobs.recentStatusUpdates')
@endsection
