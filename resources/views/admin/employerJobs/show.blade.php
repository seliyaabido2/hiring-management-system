@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
       <b>{{ $employerJobs->job_title }} </b>
    </div>
    <div class="card-body requirements">
        <p>Requirement of a {{ $employerJobs->job_title }} with a experience of a {{$employerJobs->job_requirement_experience}}.</p>
        <p>{{  $employerJobs->location }} |  Posted:{{ getConvertedDate($employerJobs->created_at) }} | Requirement: {{$employerJobs->number_of_vacancies}}</p>
    </div>
    <div class="card-header">
       <center class="fw-bold"> Job Details </center>
    </div>

    <div class="card-body">
        <div class="mb-2 job-details">
            <h5 class="border_bottom">Basic Job Details</h5>
           <li><b>Job Type: </b> {{$employerJobs->job_type}}</li>
           <li><b>Job Role: </b> {{$employerJobs->job_role}}</li>
             <li><b>Job Address: </b> {{$employerJobs->location}}</li>
           <li> <b>Job Description: </b> {{$employerJobs->job_description}}</li>
            
            <li><b>Job Benefits: </b> {{$employerJobs->job_benefits}}</li>
          
            <li><b>Number of Vacancies: </b> {{$employerJobs->number_of_vacancies}}</li>

            <li><b>Job Work From Office/Home:</b> {{$employerJobs->job_shift}}</li>

            <li><b>Total Number of Working Days: </b> {{$employerJobs->total_number_of_working_days}} Days</li>

            <li><b>Any other language:</b>
                <?php echo ($employerJobs->any_other_langauge === 'Other') ? $employerJobs->other_any_other_langauge : $employerJobs->any_other_langauge; ?>
            </li>

            <li><b>Job Launch Date: </b>{{$employerJobs->job_start_date}}</li>
            <li><b>Job Requirement Duration: </b>{{$employerJobs->job_recruiment_duration}} Days</li>

            <br>
          
            <h5 class="border_bottom">Experience Requirement Details</h5>
            <li>Previous Insurance Experience with State Farm: <b>{{$employerJobs->experience_sf}}</b></li>

            @if($employerJobs->experience_sf == 'Yes')
                <li>License requirement: <b>{{$employerJobs->license_requirement}}</b></li>
                <li>How Many Years of Experience: <b>{{$employerJobs->how_many_years_of_experience}}</b></li>
            @endif

            <li>Licensed Candidate with completed Basic Training: <b>{{$employerJobs->license_candidate_basic_training}}</b></li>
            <li>License candidate without insurance Experience but have experience in Banking/Finance: <b>{{$employerJobs->license_candidate_banking_finance}}</b></li>
            <li>Minimum Pay Per Hour: <b>${{$employerJobs->minimum_pay_per_hour}}</b></li>
            <li>Maximum Pay Per Hour: <b>${{$employerJobs->maximum_pay_per_hour}}</b></li>
            
            <br>
            <b class="border_bottom">Additional Information:</b>
            <li>{{$employerJobs->additional_information}}</li>
            <br>


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
