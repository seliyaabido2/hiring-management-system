@extends('layouts.admin')
@section('content')
@php
    $user_id = auth()->user()->id;
    $roleName =  getUserRole($user_id);
@endphp
<div class="card">
    <div class="card-header">
        {{ trans('cruds.recruiterReports.title_singular') }} {{ trans('global.list') }}
    </div>


    <div class="card-body    filter-div">
        <form id ="report-filter">
             @csrf
            <div class="form-row">


                <div class="form-group col-md-4  {{ $errors->has('from_date') ? 'has-error' : '' }}">
                    <label for="from_date">{{ trans('cruds.recruiterReports.fields.from_date') }}*</label>
                <div class='input-group date' id='from_date'>

                            <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                            </span>
                        <input type='text'placeholder="From date" class="form-control job-data" name="from_date" />
                    @if($errors->has('from_date'))
                        <em class="invalid-feedback">
                            {{ $errors->first('from_date') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.recruiterReports.fields.from_date_helper') }}
                    </p>
                    </div>
                </div>

                <div class="form-group col-md-4  {{ $errors->has('to_date') ? 'has-error' : '' }}">
                    <label for="to_date">{{ trans('cruds.recruiterReports.fields.to_date') }}*</label>
                <div class='input-group date' id='to_date'>

                            <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                            </span>
                        <input type='text'placeholder="To date" class="form-control job-data" name="to_date" />
                    @if($errors->has('to_date'))
                        <em class="invalid-feedback">
                            {{ $errors->first('to_date') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.recruiterReports.fields.to_date_helper') }}
                    </p>
                    </div>
                </div>

                @if (in_array($roleName, ['Super Admin', 'Admin']))
                <div class="form-group col-md-4">
                    <label for="job_id">Recruiter</label>
                    <select class="form-control  select2 job-data" id="recruiter_id" name="recruiter_id"  >
                        <option value="">All</option>
                        @foreach ($recruiterUsers as $recruiter)
                        <option value="{{ $recruiter->id }}">{{ $recruiter->first_name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                 <div class="form-group col-md-4">
                    <label for="job_id">Candidates</label>
                    <select class="form-control  select2 job-data" id="candidate_id" name="candidate_id"  >
                        <option value="">All</option>
                        @foreach ($candidates as $candidate)
                        <option value="{{ $candidate->candidate_id }}">{{ $candidate->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label for="job_id">Job Title</label>
                    <select class="form-control  select2 " id="job_id" name="job_id"  >
                        <option value="">All</option>
                        @foreach ($jobs as $job)
                        <option value="{{ $job->id }}">{{ $job->job_title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="job_round">Job Round Name</label>
                    <select class="form-control select2" id="job_round_name" name="job_round_name">
                        <option value="">All</option>
                        <option value="None">None</option>
                        <option value="Shortlist">Shortlist</option>
                        <option value="Assessment">Assessment</option>
                        <option value="Phone Interview">Phone Interview</option>
                        <option value="In person Interview">In person Interview</option>
                        <option value="Background Check">Background Check</option>
                        <option value="Assessment">Assessment</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="job_round_status">Job Round Status</label>
                    <select class="form-control select2bs4" id="job_round_status" name="job_round_status">
                        <option value="">All</option>
                        <option value="None">None</option>
                        <option value="Selected">Selected</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Stand By">Stand By</option>
                        <option value="No Response">No Response</option>
                        <option value="Skip">Skip</option>
                    </select>
                </div>

                <div class="form-group col-md-4 mt-4">

                    <button type="submit" class="btn btn-primary">Generate Report </button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Role datatable-custom">
                <thead>
                    <tr>

                        <th>
                            S.No.
                        </th>

                        <th>
                            From Date
                        </th>
                        <th>
                            To Date
                        </th>
                        @if (in_array($roleName, ['Super Admin', 'Admin']))
                        <th>
                            Recruiter name
                        </th>
                        @endif
                        <th>
                            Candidate name
                        </th>

                        <th>
                            Job Title
                        </th>
                        <th>
                            Job Status
                        </th>
                        <th>
                            Job Round Name
                        </th>
                        <th>
                            Job Round Status
                        </th>
                        <th>
                            Created Date
                        </th>
                        <th>
                            Downloads&nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($getAllData as $key => $data)

                    <tr>
                        <td>{{$key + 1}}</td>

                        <td>{{$data->from_date}}</td>
                        <td>{{$data->to_date}}</td>
                        @if (in_array($roleName, ['Super Admin', 'Admin']))
                        <td>{{$data->recruiter_name}}</td>
                        @endif
                        <td>{{$data->candidate_name}}</td>

                        <td>{{$data->job_title}}</td>
                        <td>{{$data->job_status}}</td>
                        <td>{{$data->round_name}}</td>
                        <td>{{$data->round_status}}</td>
                        <td>{{ $data->created_at_formatted  }}</td>
                        <td><a href="{{ url('/').$data->link }}" download>
                            <i class="fa fa-download" aria-hidden="true"></i>
                         </a></td>
                    </tr>
                    @endforeach


                </tbody>
            </table>
        </div>


    </div>
</div>
@endsection
@push('js')
<!-- jquery-validation -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>

@include('admin.customJs.recruiterReports.index')
@endpush

