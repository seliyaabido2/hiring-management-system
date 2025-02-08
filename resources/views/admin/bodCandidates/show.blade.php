@extends('layouts.admin')
@section('content')

<div class="card">

    <div class="card-header">
       <center class="fw-bold">Candidate Details </center>
    </div>

    <div class="card-body">
        <div class="mb-2">
            <h5 class="border_bottom">Basic Candidate Details</h5>
            <li>Candidate Id: <b>{{$candidate->candidate_id}}</b></li>
            <li>Name: <b>{{$candidate->name}}</b></li>
            <li>Email: <b>{{$candidate->email}}</b></li>
            <li>Contact Number: <b>{{$candidate->contact_no}}</b></li>
            @if ($candidate->date_of_birth !== null)
                <li>Date Of Birth: <b>{{ getConvertedDate($candidate->date_of_birth) }}</b></li>
            @else
            <li>Date Of Birth: - </li>
            @endif

            @if ($candidate->gender !== null)
            <li>Gender: <b>{{ $candidate->gender }}</b></li>
            @else
            <li>Gender: - </li>
            @endif
            <li>Location: <b>{{$candidate->location}}</b></li>


            <br>
            <h5 class="border_bottom">Job Preference & Experience Details</h5>
            <li>Job Preference: <b>{{$candidate->job_preference}}</b></li>
            <li>Job Type: <b>{{$candidate->job_type}}</b></li>
            <li>Previous Insurance Experience with State Farm: <b>{{$candidate->experience_sf}}</b></li>
            @if($candidate->experience_sf == 'Yes')
            <li>How Many Experience: <b>{{$candidate->how_many_experience}}</b></li>
           
            <li>Presently Working in State Farm: <b>{{$candidate->presently_working_in_sf}}</b></li>
            @if($candidate->presently_working_in_sf == 'No')
            <li>{{ trans('cruds.employerJobs.fields.last_month_year_in_sf') }}: <b>
                
                {{ $candidate->last_month_year_in_sf !== null ? $candidate->last_month_year_in_sf : '' }}
            </b></li>

            @endif
            @else @endif
            <li>License Requirement: <b>{{$candidate->license_requirement}}</b></li>
            <li>Licensed Candidate with basic Training: <b>{{$candidate->license_candidate_basic_training}}</b></li>

            <li>License candidate without insurance Experience but have experience in Banking/Finance: <b>{{$candidate->license_candidate_banking_finance}}</b></li>

            <li>Any other language:
                <b><?php echo ($candidate->any_other_langauge === 'Other') ? $candidate->other_any_other_langauge : $candidate->any_other_langauge; ?></b>
            </li>

            <li>Expected pay per hour: <b>{{ $candidate->expected_pay_per_hour ? '$' . $candidate->expected_pay_per_hour : '' }}</b></li>

            <li>Currrent pay per hour: <b>{{ $candidate->current_pay_per_hour ? '$' . $candidate->current_pay_per_hour : '' }}</b></li>

            @if($candidate->additional_information != NULL)
            <li>Additional Information: <b>{{$candidate->additional_information}}</b></li>
            @endif
            @if($candidate->reference_check != NULL)
            <li>Reference Check: <b>{{$candidate->reference_check}}</b></li>
            @endif

            <li>CV: <b><a href="{{ asset('candidate_resume').'/'.$candidate->resume }}" download>
                Download
            </a></b></li>
            <li>status: <b>{{$candidate->status}}</b></li>
            <li>Date Of Register: <b>{{ getConvertedDate(date('d-m-Y', strtotime($candidate->created_at))) }}</b></li>

            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        <nav class="mb-3">
            <div class="nav nav-tabs">

            </div>
        </nav>
        <div class="tab-content">

        </div>
    </div>
</div>
@endsection
