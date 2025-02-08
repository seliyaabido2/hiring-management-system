@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="mb-6">
        <select class="select2 CandidateJobStatus "  data-id="{{encrypt_data($candidate->id)}}" name="status" {{ $disableItem }}>

            <option value="None" {{ $candidate->status == 'None' ? 'selected' : '' }}>None</option>
            <option value="Shortlist" {{ $candidate->status == 'Shortlist' ? 'selected' : '' }}>Shortlist</option>

            <option value="Assessment" {{ $candidate->status == 'Assessment' ? 'selected' : '' }}>Assessment</option>

            <option value="Phone Interview" {{ $candidate->status == 'Phone Interview' ? 'selected' : '' }}>Phone Interview</option>

            <option value="In person Interview" {{ $candidate->status == 'In person Interview' ? 'selected' : '' }}>In person Interview</option>

            <option value="Background Check" {{ $candidate->status == 'Background Check' ? 'selected' : '' }}>Background Check</option>

            <option value="Selected" {{ $candidate->status == 'Selected' ? 'selected' : '' }}>Selected</option>

            <option value="Rejected" {{ $candidate->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>

            <option value="Stand By" {{ $candidate->status == 'Stand By' ? 'selected' : '' }}>Stand By</option>

            <option value="Candidate not responding (No Response)" {{ $candidate->status == 'Candidate not responding (No Response)' ? 'selected' : '' }}>Candidate not responding (No Response)</option>

        </select>
    </div>
    <div class="card-header">
       <b>{{ $employerJobs->job_title }} </b>
    </div>
    <div class="card-body">
        <p>Requirement of a {{ $employerJobs->job_title }} with a experience of a {{$employerJobs->job_requirement_experience}}.</p>
        <p>{{  $employerJobs->location }} |  Posted: 10 Days Ago |  Total Placement Required: {{$employerJobs->number_of_vacancies}}</p>
    </div>
    <div class="card-header">
       <center class="fw-bold"> Job Details </center>
    </div>

    <div class="card-body">
        <div class="mb-2">
            <b>1. Job Attributes:</b>
            <li>Previous Insurance Experience with State Farm: <b>{{$employerJobs->experience_sf}}</b></li>
            <li>Previous Insurance Experience without State Farm: <b>{{$employerJobs->experience_without_sf}}</b></li>
            <li>Licensed Candidate with completed basic State Farm Training: <b>{{$employerJobs->license_candidate_basic_training}}</b></li>
            <li>Licensed Candidate without insurance experience but have previous banking/finance experience: <b>{{$employerJobs->license_candidate_banking_finance}}</b></li>
            <li>Licensed Candidate without insurance experience: <b>{{$employerJobs->license_candidate_no_experience}}</b></li>

            <br>
            <b>2. Job Role:</b>
            <li>{{$employerJobs->job_type}}</li>
            <li>{{$employerJobs->working_day}} Working Days</li>

            <br>
            <b>3. Job Type:</b>
            <li>{{$employerJobs->job_shift}}</li>
            <li class="bg-red">Remaining value</li>
            <li>Working Days Per Week: <b>{{$employerJobs->working_days_per_week}} Days</b></li>

            <br>
            <b>4. Pay Structure:</b>
            <li>Salary Type: <b>{{$employerJobs->salary_type}}</b></li>
            <li>Minimum Pay Per Hour: <b>${{$employerJobs->minimum_pay_per_hour}}</b></li>
            <li>Maximum Pay Per Hour: <b>${{$employerJobs->maximum_pay_per_hour}}</b></li>
            
            <li>Pay Day: <b>{{$employerJobs->pay_day}}</b></li>
            <li>Bonus/Commission: <b>{{$employerJobs->bonus_commission}}</b></li>

            <br>
            <p><b>5. Language Preference:</b> {{$employerJobs->any_other_langauge}}</p>
            <p><b>6. Parking Free: </b>{{$employerJobs->parking_free}}</p>
            <p><b>7. Field Role: </b>{{$employerJobs->job_role}}</p>
            <p><b>8. License Requirement: </b>{{$employerJobs->license_requirement}}</p>
            <p><b>9. Job Start Date: </b>{{$employerJobs->job_start_date}}</p>
            <p><b>10. Number of Placements Required: </b>{{$employerJobs->number_of_vacancies}}</p>
            <p><b>11. Job Requirement Duration: </b>{{$employerJobs->job_recruiment_duration}}</p>
            <p class="bg-red"><b>12. Assessment Link:</b>   </p>
            <p><b>13. Additional Information: </b>{{$employerJobs->additional_information}}</p>

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
