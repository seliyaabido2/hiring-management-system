@extends('layouts.admin')
@section('content')
@section('styles')
<style>
.centered {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: 50px;
}

.tooltip-inner {
  max-width: 500px !important;
}
</style>

@endsection
<div class="card">

    <div class="card-header">
        {{ 'Hired' }} {{ trans('cruds.candidates.title_singular') }} in {{ $getJobDetail->job_title }}
    </div>


     <div class="card-body">
         <p>Requirement of a {{ $getJobDetail->job_title }} with a experience of a {{$getJobDetail->job_requirement_experience}}.</p>
         <p>{{  $getJobDetail->location }} |  Posted: {{ getConvertedDate($getJobDetail->created_at) }} |  Total Placement Required: {{$getJobDetail->number_of_vacancies}}</p>
     </div>
    <div class="card-header">

        {{ 'Hired' }} {{ trans('cruds.candidates.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.appliedJobs.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.appliedJobs.fields.recruiter') }} {{ 'partner' }} {{ trans('cruds.appliedJobs.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.appliedJobs.fields.name') }}
                        </th>

                        <th style="width: 35%;">
                            {{ trans('cruds.appliedJobs.fields.status') }}
                        </th>

                        <th>
                            Action&nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($appliedJobCandidates as $key => $candidate)

                    <tr data-entry-id="{{ $appliedJobCandidates[$key]->candidate->id }}">
                        <td>
                            {{ ($key + 1) ?? '' }}
                        </td>
                        <td>
                            {{ $candidate->candidate->user_id ?? '' }}
                        </td>
                        <td>
                            {{ $candidate->candidate->name ?? '' }}
                        </td>
                        @php
                            $roleName =getUserRole(auth()->user()->id);

                            $disableItem ="";
                            if($roleName == 'Recruiter'){
                                $disableItem ="disabled";

                            }

                        @endphp
                        <td>
                            {{ $candidate->SelectedCandidates[0]->status  ?? ''  }}
                        </td>

                        <td>


                            @can('candidate_show')
                            <a class="btn btn-xs btn-primary" href="{{ route('admin.candidate.show', encrypt_data($appliedJobCandidates[$key]->candidate->id)) }}">
                                {{ trans('global.view') }}
                            </a>
                            @endcan


                            @can('job_edit')
                            <a class="btn btn-xs btn-info" href="{{ route('admin.appliedJobs.edit',encrypt_data($appliedJobCandidates[$key]->candidate->id)) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                            @endcan

                            @can('job_applied_delete')
                            <button class="btn btn-xs btn-danger delete-applied-btn" data-id="{{$appliedJobCandidates[$key]->id}}">
                                {{ trans('global.delete') }}
                            </button>
                            @endcan

                            @php

                                $link =getAssessmentLink($candidate->candidate_id,$candidate->status);


                            @endphp
                             @if(!empty($link))

                                <a class="btn btn-xs btn-primary" target="_blank" href="{{ $link }}">
                                    {{ trans('global.booking_url') }}
                                </a>
                            @endif

                            @can('candidate_show')
                            <a class="btn btn-xs btn-success" href="{{ route('admin.appliedJobs.candidatesViewJobStatus', encrypt_data($candidate->id)) }}">
                                {{ trans('global.status_view') }}
                            </a>
                            @endcan

                            @if (!empty($candidate->savedCandidate))

                            <a class="btn btn-xs btn-warning" href="{{ route('admin.candidate.unSavedCandidate', ['id' =>encrypt_data($candidate->candidate_id) ]) }}">
                                un-save
                            </a>
                            @else
                            <a class="btn btn-xs btn-success" href="{{ route('admin.appliedJobs.savedCandidate', ['id' =>encrypt_data($candidate->id) ]) }}">
                                Save candidate
                            </a>
                            @endif


                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>

<!-- Add a modal dialog for the confirmation -->

<!-- START- PHONE INTERVIEW MODEL -->
<div class="modal fade" id="PhoneIntereviewModel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add booking url</h5>
                <button type="button" class="close cancel-btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="PhoneIntereviewModelForm" action="" method="POST">
            <div class="modal-body">
                <div class="row">

                        <input type="hidden" id="candidatejobstatus" name="candidatejobstatus" >
                        <div class="form-group {{ $errors->has('assessment_link') ? 'has-error' : '' }}">
                            <label for="assessment_link">{{ trans('cruds.appliedJobs.fields.assessment_link') }}* <a href="#" data-placement="right" data-bs-toggle="tooltip" title='1. Go to your Calendly account and navigate to the "Event Types" section. You can access it through the following link: Calendly Event Types. &#10;

                                2. Find the event you want to share and click on the "Share" button associated with that event.

                                3. In the sharing options, locate the "Share a link" tab and ensure that the "Make link single-use" option is checked.

                                4. Copy the generated link provided in the "Share a link" tab. This link is now configured as a single-use link, meaning it can only be accessed once by the recipient.'><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
                            <input type="text" id="assessment_link" name="assessment_link" class="form-control" placeholder="Booking url">
                            @if($errors->has('assessment_link'))
                            <em class="invalid-feedback">
                                {{ $errors->first('assessment_link') }}
                            </em>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.appliedJobs.fields.assessment_link_helper') }}
                            </p>
                        </div>

                </div>
            </div>
            <div class="modal-footer">
                <!-- Perform the delete action when confirmed -->

                    @csrf
                    @method('POST')
                    <button type="submit" class="btn btn-danger ">Save</button>

                <!-- Close the modal dialog -->
                <button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- END- PHONE INTERVIEW MODEL -->

@endsection
@section('scripts')
@parent

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>

@include('admin.customJs.appliedJobs.AppliedCandidates')

@endsection
