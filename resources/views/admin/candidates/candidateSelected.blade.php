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

        {{ 'selected' .' '. trans('cruds.candidates.title_singular') }} {{ trans('global.list') }}
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
                            {{ trans('cruds.candidates.fields.job_title') }}
                        </th>
                        <th>
                            {{trans('cruds.candidates.title') .' '. trans('cruds.candidates.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.candidates.fields.name') }}
                        </th>

                    </tr>
                </thead>
                <tbody>

                    @foreach($selectedCandidate as $key => $candidate)

                    <tr data-entry-id="{{ $candidate->id }}">
                        <td>
                            {{ ($key + 1) ?? '' }}
                        </td>
                        <td>
                            <a target="_blank" href="{{ route('admin.employerJobs.show', encrypt_data($candidate->getJobDetail->id)) }}">
                                {{ $candidate->getJobDetail->job_title ?? '' }}
                            </a>
                        </td>
                        <td>
                            <a target="_blank" href="{{ route('admin.candidate.show', encrypt_data($candidate->Candidate[0]->id)) }}">
                                {{ $candidate->candidate_id ?? '' }}
                            </a>
                        
                        </td>
                        <td>
                            {{ $candidate->candidate[0]->name ?? '' }}
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
