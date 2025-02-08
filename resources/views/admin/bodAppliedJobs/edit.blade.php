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
        {{ trans('global.edit') }} {{ trans('cruds.appliedJobs.title_singular') }}
    </div>

    <div class="card-body">
    <form action="{{ route("admin.bodAppliedJobs.singleUpdate") }}" method="POST" id="EditAppliedJobForm" autocomplete="off" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <input type="hidden" id="candidateStatus_id" name="candidateStatus_id" value="{{$candidateJobStatusComment->id}}">

            </div>
            @php
            if($candidateJobStatusComment->status == 'Phone Interview' || $candidateJobStatusComment->status == 'Assessment'){
                $viewitem = "block";
            }
            elseif($candidateJobStatusComment->status == 'Stand By'){
                $viewitem = "stand_by";
            }
            else{
                $viewitem = "none";
            }
            @endphp

            @if($viewitem  == "stand_by")

            <div class="row stand_by-div">
                <div class="form-group col-md-8 {{ $errors->has('status') ? 'has-error' : '' }}">
                    <label for="status">{{ trans('cruds.appliedJobs.fields.status') }}*</label>
                    <select class="form-control" name="status" id="status">
                    <option disabled selected>Select Status</option>
                        <option>Selected</option>
                        <option>Rejected</option>
                    </select>
                    @if($errors->has('status'))
                    <em class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.appliedJobs.fields.status_helper') }}
                    </p>
                </div>

                <div class="col-md-8">
                    <div class="form-group {{ $errors->has('additional_note') ? 'has-error' : '' }}">
                        <label for="additional_note">{{ trans('cruds.appliedJobs.fields.additional_note') }}*</label>
                        <textarea placeholder="Additional note" class="form-control" id="additional_note" name="additional_note" rows="3" ></textarea>


                        @if($errors->has('additional_note'))
                            <em class="invalid-feedback">
                                {{ $errors->first('additional_note') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.appliedJobs.fields.additional_note_helper') }}
                        </p>
                    </div>
                </div>
            </div>

            @else

            <div class="row assessment_link-div" style="display: {{ $viewitem }}">
                <div class="form-group col-md-8 {{ $errors->has('assessment_link') ? 'has-error' : '' }}">
                    <label for="assessment_link">{{ trans('cruds.appliedJobs.fields.assessment_link') }}* <a href="#" data-placement="right" data-bs-toggle="tooltip" title='1. Go to your Calendly account and navigate to the "Event Types" section. You can access it through the following link: Calendly Event Types. &#10;

                        2. Find the event you want to share and click on the "Share" button associated with that event.

                        3. In the sharing options, locate the "Share a link" tab and ensure that the "Make link single-use" option is checked.

                        4. Copy the generated link provided in the "Share a link" tab. This link is now configured as a single-use link, meaning it can only be accessed once by the recipient.'><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>

                     <input type="text" id="assessment_link" name="assessment_link" class="form-control" placeholder="Link url" value="{{ isset($candidateJobStatusComment->links) ? $candidateJobStatusComment->links : '' }}">
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

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group {{ $errors->has('additional_note') ? 'has-error' : '' }}">
                        <label for="additional_note">{{ trans('cruds.appliedJobs.fields.additional_note') }}*</label>
                        <textarea placeholder="Additional note" class="form-control" id="additional_note" name="additional_note" rows="3" >{{ isset($candidateJobStatusComment->additional_note) ? $candidateJobStatusComment->additional_note : '' }}</textarea>


                        @if($errors->has('additional_note'))
                            <em class="invalid-feedback">
                                {{ $errors->first('additional_note') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.appliedJobs.fields.additional_note_helper') }}
                        </p>
                    </div>
                </div>
            </div>

            @endif




            <br>
            <div>
                <button class="btn btn-info" type="submit">{{ trans('global.save') }}</button>
                {{-- <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}"> --}}
            </div>
        </form>


    </div>
</div>
@endsection

@push('js')

<!-- jquery-validation -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>

@include('admin.customJs.appliedJobs.EditAppliedjob')
@endpush

