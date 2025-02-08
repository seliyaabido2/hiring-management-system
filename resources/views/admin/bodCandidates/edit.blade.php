@extends('layouts.admin')
@section('content')
@section('styles')
@endsection

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.bodCandidates.title_singular') }}
    </div>

    <div class="card-body">
        @if (count($errors) > 0)
            <div class = "alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <b>Basic Details</b>

        <form class="ml-md-5 pl-md-5 mt-3 mt-md-0 navbar-search" method="post" enctype="multipart/form-data" action="{{ route("admin.resumeFetchData.upload") }}">
            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
            <input type="hidden" id="candidates_id" name="candidates_id" value="{{ $candidate->id }}">

            <div class="col-md-6">
                <div class="form-group {{ $errors->has('resume') ? 'has-error' : '' }}">
                    <label for="resume">{{ trans('cruds.employerJobs.fields.resume') }}*</label>
                    <div class="input-group">
                        <input type="file" id="file" name="file" class="form-control">
                    </div>
                    <br>
                    @if ($errors->has('resume'))
                        <em class="invalid-feedback">
                            {{ $errors->first('resume') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.resume_helper') }}
                    </p>

                </div>
            </div>

            <button type="submit" class="btn btn-primary upload-btn">Upload</button>
        </form>


        <form action="{{ route('admin.bodCandidate.update', [$candidate->id]) }}" method="POST" id="EditCandidateForm"
            autocomplete="off" enctype="multipart/form-data">

            @csrf
            @method('PUT')
            <?php

            if (!empty($candidate_resume)) {
                $candidate_resume = (object)$candidate_resume;
            }

             ?>
            <input type="hidden" id="candidates_hidden_id" name="id" value="{{ $candidate->id }}">
            @if (isset($candidate_resume->resume_path))
                <input type="hidden" id="resume_path" name="resume_path" value="{{ isset($candidate_resume->resume_path) ? $candidate_resume->resume_path : "" }}">
            @else
                <input type="hidden" id="resume_path" name="resume_path" value="{{ isset($candidate->resume) ? $candidate->resume : "" }}">
            @endif

            <div class="row">

                <div class="col-md-12">

                    <div class="form-group {{ $errors->has('candidate_name') ? 'has-error' : '' }}">
                        <label for="candidate_name">{{ trans('cruds.employerJobs.fields.candidate_name') }}*</label>
                        @if (isset($candidate_resume->candidate_name))
                            <input type="text" id="candidate_name" name="candidate_name" class="form-control"
                            value="{{ old('name', isset($candidate_resume->candidate_name) ? $candidate_resume->candidate_name : '') }}"
                            placeholder="Full Name">
                        @else
                            <input type="text" id="candidate_name" name="candidate_name" class="form-control"
                            value="{{ old('name', isset($candidate) ? $candidate->name : '') }}"
                            placeholder="Full Name">
                        @endif

                        @if ($errors->has('candidate_name'))
                            <em class="invalid-feedback">
                                {{ $errors->first('candidate_name') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.candidate_name_helper') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        <label for="email">{{ trans('cruds.employerJobs.fields.email') }}*</label>
                        @if (isset($candidate_resume->email))
                            <input type="text" id="email" name="email" class="form-control unique"
                            value="{{ old('email', isset($candidate_resume->email) ? $candidate_resume->email : '') }}"
                            placeholder="{{ trans('cruds.employerJobs.fields.email') }}">
                        @else
                            <input type="text" id="email" name="email" class="form-control unique"
                            value="{{ old('email', isset($candidate) ? $candidate->email : '') }}"
                            placeholder="{{ trans('cruds.employerJobs.fields.email') }}">
                        @endif

                        @if ($errors->has('email'))
                            <em class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.email_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('contact_number') ? 'has-error' : '' }}">
                        <label for="contact_number">{{ trans('cruds.employerJobs.fields.contact_number') }}*</label>
                        @if (isset($candidate_resume->contact_number))
                            <input type="text" id="contact_number" name="contact_number" class="form-control"
                            value="{{ old('contact_no', isset($candidate_resume->contact_number) ? $candidate_resume->contact_number : '') }}"
                            placeholder="{{ trans('cruds.employerJobs.fields.contact_number') }}">
                        @else
                            <input type="text" id="contact_number" name="contact_number" class="form-control"
                            value="{{ old('contact_no', isset($candidate) ? $candidate->contact_no : '') }}"
                            placeholder="{{ trans('cruds.employerJobs.fields.contact_number') }}">
                        @endif

                        @if ($errors->has('contact_number'))
                            <em class="invalid-feedback">
                                {{ $errors->first('contact_number') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.contact_number_helper') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6  {{ $errors->has('date_of_birth') ? 'has-error' : '' }}">
                    <label for="date_of_birth">{{ trans('cruds.employerJobs.fields.date_of_birth') }}</label>
                    <div class='input-group date' id='date_of_birth'>

                        <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                        </span>
                        <input type='text' placeholder="Date of birth" value="{{ date("d-m-Y", strtotime($candidate->date_of_birth)) }}"
                            class="form-control" id="date_of_birth" name="date_of_birth" />
                        @if ($errors->has('date_of_birth'))
                            <em class="invalid-feedback">
                                {{ $errors->first('date_of_birth') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.date_of_birth_helper') }}
                        </p>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="gender">{{ trans('cruds.employerJobs.fields.gender') }}</label>
                    <select class="form-control select2bs4" id="gender" name="gender">
                        <option value="">Select gender</option>
                        <option value="Male" {{ $candidate->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $candidate->gender == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ $candidate->gender == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @if ($errors->has('gender'))
                        <em class="invalid-feedback">
                            {{ $errors->first('gender') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.gender_helper') }}
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                        <label for="location">Select location*</label>

                        <input type="text" name="location" id="location" class="form-control" value="{{ old('location', isset($candidate) ? $candidate->location : '') }}" placeholder="Choose Location" >
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', isset($candidate) ? $candidate->latitude : '') }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', isset($candidate) ? $candidate->longitude : '') }}">
                        <label class="location-error error"></label>
                        @if($errors->has('location'))
                        <em class="invalid-feedback">
                            {{ $errors->first('location') }}
                        </em>
                        @endif
                    </div>
                </div>
            </div>
            <br>

            <b>Job Preference & Experience Details</b>
            <div class="row">

                <div class="form-group col-md-6">
                    <label for="job_preference">{{ trans('cruds.employerJobs.fields.job_preference') }}</label>
                    <select class="form-control select2bs4" id="job_preference" name="job_preference">
                        <option value="">Select</option>

                        <option value="Service Focused"
                            {{ $candidate->job_preference == 'Service Focused' ? 'selected' : '' }}>Service Focused
                        </option>
                        <option value="Sales Focused"
                            {{ $candidate->job_preference == 'Sales Focused' ? 'selected' : '' }}>Sales Focused
                        </option>
                        <option value="Both" {{ $candidate->job_preference == 'Both' ? 'selected' : '' }}>Both
                        </option>

                    </select>
                    @if ($errors->has('job_preference'))
                        <em class="invalid-feedback">
                            {{ $errors->first('job_preference') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.job_preference_helper') }}
                    </p>
                </div>

                <div class="col-md-6">

                    <div class="form-group {{ $errors->has('job_type') ? 'has-error' : '' }}">
                        <label for="job_type">{{ trans('cruds.employerJobs.fields.job_type') }}</label>

                        <select class="form-control select2bs4" id="job_type" name="job_type">

                            <option value="">Select Job Type</option>
                            <option value="Full Time" {{ $candidate->job_type == 'Full Time' ? 'selected' : '' }}>Full
                                Time</option>
                            <option value="Part Time" {{ $candidate->job_type == 'Part Time' ? 'selected' : '' }}>Part
                                Time</option>

                        </select>
                        <br>
                        @if ($errors->has('job_type'))
                            <em class="invalid-feedback">
                                {{ $errors->first('job_type') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.job_type_helper') }}
                        </p>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="experience_sf">{{ trans('cruds.employerJobs.fields.experience_sf') }}*</label>
                    <select class="form-control select2bs4" id="experience_sf" name="experience_sf">
                        <option value="Yes" {{ $candidate->experience_sf == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ $candidate->experience_sf == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                    @if ($errors->has('experience_sf'))
                        <em class="invalid-feedback">
                            {{ $errors->first('experience_sf') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.experience_sf_helper') }}
                    </p>
                </div>
                <div class="form-group col-md-6">
                    <label
                        for="license_requirement">{{ trans('cruds.employerJobs.fields.license_requirement') }}*</label>
                        <select class="form-control select2bs4" id="license_requirement" name="license_requirement">

                            <option value="Property & Casualty (P&C)" {{ $candidate->license_requirement === 'Property & Casualty (P&C)' ? 'selected' : '' }}>Property & Casualty (P&C)</option>
                            <option value="Life and Health (L&H)" {{ $candidate->license_requirement === 'Life and Health (L&H)' ? 'selected' : '' }}>Life and Health (L&H)</option>
                            <option value="Both" {{ $candidate->license_requirement === 'Both' ? 'selected' : '' }}>Both</option>
                            <option value="No License Required" {{ $candidate->license_requirement === 'No License Required' ? 'selected' : '' }}>No License Required</option>

                        </select>

                    @if ($errors->has('license_requirement'))
                        <em class="invalid-feedback">
                            {{ $errors->first('license_requirement') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.license_requirement_helper') }}
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 how_many_experience" style="display: <?php echo $candidate->experience_sf === 'Yes' ? 'block' : 'none'; ?>">
                    <label
                        for="how_many_experiences">{{ trans('cruds.employerJobs.fields.how_many_experience') }}</label>
                    <select class="form-control select2bs4" id="how_many_experience" name="how_many_experience">
                        <option value="" @if ($candidate->how_many_experience === '') selected @endif>Select Experience
                        </option>
                        <option value="0-2 years" @if ($candidate->how_many_experience === '0-2 years') selected @endif>0-2 years</option>
                        <option value="2-5 years" @if ($candidate->how_many_experience === '2-5 years') selected @endif>2-5 years</option>
                        <option value="more than 5 years" @if ($candidate->how_many_experience === 'more than 5 years') selected @endif>More than
                            5 years</option>
                    </select>
                    @if ($errors->has('how_many_experience'))
                        <em class="invalid-feedback">
                            {{ $errors->first('how_many_experience') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.how_many_experience_helper') }}
                    </p>
                </div>
                <div class="form-group col-md-6 presently_working_in_sf" style="display: <?php echo $candidate->experience_sf === 'Yes' ? 'block' : 'none'; ?>">
                    <label
                        for="presently_working_in_sf">{{ trans('cruds.employerJobs.fields.presently_working_in_sf') }}</label>
                    <select class="form-control select2bs4" id="presently_working_in_sf"
                        name="presently_working_in_sf">
                        <option value="">Select </option>
                        <option value="Yes" @if ($candidate->presently_working_in_sf === 'Yes') selected @endif>Yes</option>
                        <option value="No" @if ($candidate->presently_working_in_sf === 'No') selected @endif>No</option>
                    </select>
                    @if ($errors->has('presently_working_in_sf'))
                        <em class="invalid-feedback">
                            {{ $errors->first('presently_working_in_sf') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.presently_working_in_sf_helper') }}
                    </p>
                </div>
            </div>
            <div class="row">

                <div class="form-group col-md-6 last_month_year_in_sf" style="display: <?php echo ($candidate->experience_sf === 'Yes' && $candidate->presently_working_in_sf === 'No') ? 'block' : 'none'; ?>">
                    <label
                        for="last_month_year_in_sf">{{ trans('cruds.employerJobs.fields.last_month_year_in_sf') }}</label>
                    <input type="text" class="form-control" name="last_month_year_in_sf" id="last_month_year_in_sf" value="{{$candidate->last_month_year_in_sf}}" >
                    @if ($errors->has('last_month_year_in_sf'))
                        <em class="invalid-feedback">
                            {{ $errors->first('last_month_year_in_sf') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.last_month_year_in_sf_helper') }}
                    </p>
                </div>


            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label
                        for="license_candidate_basic_training">{{ trans('cruds.employerJobs.fields.license_candidate_basic_training') }}</label>
                    <select class="form-control select2bs4" id="license_candidate_basic_training"
                        name="license_candidate_basic_training">
                        <option value="">Select</option>
                        <option value="Yes"
                            {{ $candidate->license_candidate_basic_training == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No"
                            {{ $candidate->license_candidate_basic_training == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                    @if ($errors->has('license_candidate_basic_training'))
                        <em class="invalid-feedback">
                            {{ $errors->first('license_candidate_basic_training') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.license_candidate_basic_training_helper') }}
                    </p>
                </div>
                <div class="form-group col-md-6">
                    <label
                        for="license_candidate_banking_finance">{{ trans('cruds.employerJobs.fields.license_candidate_banking_finance') }}</label>
                    <select class="form-control select2bs4" id="license_candidate_banking_finance"
                        name="license_candidate_banking_finance">
                        <option value="">Select</option>
                        <option value="Yes" @if ($candidate->license_candidate_banking_finance === 'Yes') selected @endif>Yes</option>
                        <option value="No" @if ($candidate->license_candidate_banking_finance === 'No') selected @endif>No</option>
                    </select>
                    @if ($errors->has('license_candidate_banking_finance'))
                        <em class="invalid-feedback">
                            {{ $errors->first('license_candidate_banking_finance') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.license_candidate_banking_finance_helper') }}
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label
                        for="any_other_langauge">{{ trans('cruds.employerJobs.fields.any_other_langauge') }}*</label>
                    <select class="form-control select2bs4" id="any_other_langauge" name="any_other_langauge">
                        <option value="">Select langauge</option>
                        <option value="spanish" {{ $candidate->any_other_langauge == 'spanish' ? 'selected' : '' }}>
                            spanish</option>
                        <option value="korean" {{ $candidate->any_other_langauge == 'korean' ? 'selected' : '' }}>
                            korean</option>
                        <option value="chinese" {{ $candidate->any_other_langauge == 'chinese' ? 'selected' : '' }}>
                            chinese</option>
                        <option value="Other" {{ $candidate->any_other_langauge == 'Other' ? 'selected' : '' }}>Other
                        </option>

                    </select>
                    @if ($errors->has('any_other_langauge'))
                        <em class="invalid-feedback">
                            {{ $errors->first('any_other_langauge') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.any_other_langauge_helper') }}
                    </p>
                </div>

                <div class="form-group col-md-6 other_any_other_langauge" style="display: <?php echo $candidate->any_other_langauge === 'Other' ? 'block' : 'none'; ?>">

                    <label
                        for="other_any_other_langauges">{{ trans('cruds.employerJobs.fields.other_any_other_langauge') }}*</label>
                    <input type="text" id="other_any_other_langauge" name="other_any_other_langauge"
                        class="form-control"
                        value="{{ old('other_any_other_langauge', isset($candidate) ? $candidate->other_any_other_langauge : '') }}"
                        placeholder="{{ trans('cruds.employerJobs.fields.other_any_other_langauge') }}">

                    @if ($errors->has('other_any_other_langauge'))
                        <em class="invalid-feedback">
                            {{ $errors->first('other_any_other_langauge') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.other_any_other_langauge_helper') }}
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('expected_pay_per_hour') ? 'has-error' : '' }}">
                        <label
                            for="expected_pay_per_hour">{{ trans('cruds.employerJobs.fields.expected_pay_per_hour') }}</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">$</span>
                            <input type="number" id="expected_pay_per_hour" name="expected_pay_per_hour"
                                value="{{ $candidate->expected_pay_per_hour }}"
                                placeholder="Enter Expected pay per hour" class="form-control">
                        </div>
                        <br>
                        @if ($errors->has('expected_pay_per_hour'))
                            <em class="invalid-feedback">
                                {{ $errors->first('expected_pay_per_hour') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.expected_pay_per_hour_helper') }}
                        </p>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 other_license_requirement" style="<?php $candidate->license_requirement == 'Other' ? 'display:block' : 'display:none'; ?> ">
                    <label
                        for="other_license_requirements">{{ trans('cruds.employerJobs.fields.other_license_requirement') }}*</label>
                    <input type="text" id="other_license_requirement" name="other_license_requirement"
                        class="form-control" value="{{ $candidate->other_license_requirement }}"
                        placeholder="{{ trans('cruds.employerJobs.fields.other_license_requirement') }}">

                    @if ($errors->has('other_license_requirement'))
                        <em class="invalid-feedback">
                            {{ $errors->first('other_license_requirement') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.other_license_requirement_helper') }}
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('current_pay_per_hour') ? 'has-error' : '' }}">
                        <label for="current_pay_per_hour">{{ trans('cruds.employerJobs.fields.current_pay_per_hour') }}</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">$</span>
                            <input type="number" id="current_pay_per_hour" name="current_pay_per_hour"
                                value="{{ $candidate->current_pay_per_hour }}"
                                placeholder="Enter Expected pay per hour" class="form-control">
                        </div>
                        <br>
                        @if ($errors->has('current_pay_per_hour'))
                            <em class="invalid-feedback">
                                {{ $errors->first('current_pay_per_hour') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.current_pay_per_hour_helper') }}
                        </p>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('reference_check') ? 'has-error' : '' }}">
                        <label
                            for="reference_check">{{ trans('cruds.employerJobs.fields.reference_check') }}</label>
                            <select class="form-control select2bs4" id="reference_check"
                            name="reference_check">
                            <option value="">Select</option>
                            <option value="Yes"
                            {{ $candidate->reference_check == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No"
                            {{ $candidate->reference_check == 'No' ? 'selected' : '' }}>No</option>
                            </select>


                        @if ($errors->has('reference_check'))
                            <em class="invalid-feedback">
                                {{ $errors->first('reference_check') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.reference_check_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('additional_information') ? 'has-error' : '' }}">
                        <label
                            for="additional_information">{{ trans('cruds.employerJobs.fields.additional_information') }}</label>
                        <textarea placeholder="{{ trans('cruds.employerJobs.fields.additional_information') }}" class="form-control"
                            id="additional_information" name="additional_information" rows="3">{{ $candidate->additional_information }}</textarea>


                        @if ($errors->has('additional_information'))
                            <em class="invalid-feedback">
                                {{ $errors->first('additional_information') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.additional_information_helper') }}
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">{{ trans('cruds.employerJobs.fields.status') }}*</label>
                        <select class="form-control select2bs4" id="status" name="status">
                            <option value="Active" {{ $candidate->status == 'Active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="Deactive" {{ $candidate->status == 'Deactive' ? 'selected' : '' }}>
                                {{ trans('global.deactive') }}</option>
                        </select>
                        @if ($errors->has('status'))
                            <em class="invalid-feedback">
                                {{ $errors->first('status') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.status_helper') }}
                        </p>
                    </div>
                </div>
            </div>
            <div>
                <button class="btn btn-info" type="submit">{{ trans('global.save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection


@push('js')
<!-- jquery-validation -->

<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>

@include('admin.customJs.candidates.edit')
@endpush
