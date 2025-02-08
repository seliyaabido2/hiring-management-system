@extends('layouts.admin')
@section('content')

    @push('css')
        <style>
            .selectedOneRow {
                background-color: rgb(204, 16, 16) !important;
                color: #0f0e0e !important;
            }
        </style>
    @endpush

    <div class="card">
        <div class="card-header">
            {{ trans('global.applyjob') }} as {{ $employerJobs->job_title }}
        </div>


        <div class="card-body">
            <p>Requirement of a {{ $employerJobs->job_title }} with a experience of
                {{ $employerJobs->how_many_years_of_experience }}.</p>
            <p>{{ $employerJobs->location }} | Posted: {{ getConvertedDate($employerJobs->created_at) }} | Total
                Placement Required: {{ $employerJobs->number_of_vacancies }}</p>
        </div>
        <div class="card-header">
            Candidate details
        </div>

        <div class="card-body">
            @if (count($errors) > 0)
                <div class = "alert alert-danger auto-hide">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-2">
                    <label for="apply">{{ trans('cruds.employerJobs.fields.new_candidate') }}*</label>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input type="radio" class="form-check-input role-name" id="radio1" name="new_candidate"
                            value="Yes">Yes
                        <label class="form-check-label" for="radio1"></label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input type="radio" class="form-check-input new_candidate" id="radio2" name="new_candidate"
                            value="No" checked>No
                        <label class="form-check-label" for="radio2"></label>
                    </div>
                </div>

            </div>

                <div class="new-candidate-resume" style="display:none">
                    {{-- <form class="ml-md-5 pl-md-5 mt-3 mt-md-0 navbar-search" method="post" id="bodresume_fetch" enctype="multipart/form-data" action="{{ route("admin.resumeFetchData.upload") }}"> --}}
                        <form class="ml-md-5 pl-md-5 mt-3 mt-md-0 navbar-search" method="post" id="bodresume_fetch" enctype="multipart/form-data" action="">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <input type="hidden" name="apply_job_resume" value="apply_job_resume">

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
                        {{-- <button type="submit" class="btn btn-primary upload-btn" onclick="submitFormResume()">Next</button> --}}
                        <a class="btn btn-primary upload-btn" onclick="submitFormResume()">Next</a>
                    </form>
                </div>

            <form action="{{ route('admin.employerJobs.postApplyJob') }}" method="POST" id="applyJobForm" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <input name="job_id" id="job_id" type="hidden" value="{{ encrypt_data($employerJobs->id) }}">
                {{-- <input name="overwrite_candidate_id" id="overwrite_candidate_id" type="hidden"> --}}
                <input type="hidden" name="new_candidate_post" id="new_candidate_post">


                <div class="row saved-candidate mt-4">
                    @if (session()->has('checkCandidateArrSlugIsExist'))
                        <div class="alert alert-danger alert-dismissible manual-hide">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>{{ session('checkCandidateArrSlugIsExist') }}</strong>
                        </div>
                    @endif
                    <div class="form-group col-md-8">
                        <label for="formControlRange">Find the nearest candidates based on the job location and distance :                        </label>
                        <input type="range" max="200" class="form-control-range range-value" value="0"
                            id="formControlRange" onInput="$('#rangeval').html($(this).val())"
                            data-lat ="{{ $employerJobs->latitude }}" data-long ="{{ $employerJobs->longitude }}">
                        <span id="rangeval">0<!-- Default value --></span><span> Miles</span>
                    </div>

                    <div class="col-md-12">

                        <div class="table-responsive">
                            <table id="tblApplyjob"
                                class="table table-bordered table-striped table-hover datatable-apply-selected"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th width="10">
                                            <input type="checkbox" id="select-all-checkbox">
                                        </th>



                                        <th>
                                            {{ trans('cruds.candidates.fields.name') }}
                                        </th>
                                        <th>
                                            {{ trans('cruds.candidates.fields.email') }}
                                        </th>
                                        <th>
                                            {{ trans('cruds.candidates.fields.experirnce_sf') }}
                                        </th>
                                        <th>
                                            Location
                                        </th>
                                        <th>
                                            {{ trans('cruds.candidates.fields.status') }}
                                        </th>
                                    </tr>

                                </thead>
                                <tbody>


                                </tbody>

                            </table>

                        </div>

                    </div>
                    <div class="row mt-4">
                        <div class="col-md-2 ">
                            <button type="button" class="btn btn-success " id="getCheckedIds">Apply selected</button>
                        </div>
                    </div>
                </div>
                <div class="new-candidate-div" style="display:none">
                    <div class="row mt-4">
                        <h4>Add Candidate</h4>
                    </div>
                    <br>
                    <b>Basic Details</b>

                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" id="UniqueValidationError">
                            <input type="hidden" id="overwrite_candidate_id" name="overwrite_candidate_id">
                            <input type="hidden" id="resume_path" name="resume_path" value="">

                            <div class="form-group {{ $errors->has('candidate_name') ? 'has-error' : '' }}">
                                <label
                                    for="candidate_name">{{ trans('cruds.employerJobs.fields.candidate_name') }}*</label>
                                <input type="text" id="candidate_name" name="candidate_name" class="form-control"
                                    value="{{ old('candidate_name', isset($user) ? $user->candidate_name : '') }}"
                                    placeholder="Full Name">
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
                                <input type="text" id="email" name="email" class="form-control unique"
                                    value="{{ old('email', isset($user) ? $user->email : '') }}"
                                    placeholder="{{ trans('cruds.employerJobs.fields.email') }}">
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
                                <label
                                    for="contact_number">{{ trans('cruds.employerJobs.fields.contact_number') }}*</label>
                                <input type="text" id="contact_number" name="contact_number" class="form-control"
                                    value="{{ old('contact_number', isset($user) ? $user->contact_number : '') }}"
                                    placeholder="{{ trans('cruds.employerJobs.fields.contact_number') }}">
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
                                <input type='text' placeholder="Date of birth" class="form-control"
                                    id="date_of_birth" name="date_of_birth" />
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
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
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

                                <input type="text" name="location" id="location" class="form-control"
                                    placeholder="Choose Location">
                                <input type="hidden" id="latitude" name="latitude" value="">
                                <input type="hidden" name="longitude" id="longitude" value="">
                                <label class="location-error error"></label>
                                @if ($errors->has('location'))
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
                                <option value="Service Focused">Service Focused</option>
                                <option value="Sales Focused">Sales Focused</option>
                                <option value="Both">Both</option>
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
                                    <option value="Full Time">Full Time</option>
                                    <option value="Part Time">Part Time</option>
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
                                <option value="">Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
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
                                <option value="">Select License</option>
                                <option value="Property & Casualty (P&C)">Property & Casualty (P&C)</option>
                                <option value="Life and Health (L&H)">Life and Health (L&H)</option>
                                <option value="Both">Both</option>
                                <option value="No License Required">No License Required</option>
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
                        <div class="form-group col-md-6 how_many_experience" style="display:none">
                            <label
                                for="how_many_experiences">{{ trans('cruds.employerJobs.fields.how_many_experience') }}</label>
                            <select class="form-control select2bs4" id="how_many_experience" name="how_many_experience">
                                <option value="">Select Experience</option>
                                <option value="0-2 years">0-2 years</option>
                                <option value="2-5 years">2-5 years</option>
                                <option value="more than 5 years">more than 5 years</option>
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
                        <div class="form-group col-md-6 presently_working_in_sf" style="display:none">
                            <label
                                for="presently_working_in_sf">{{ trans('cruds.employerJobs.fields.presently_working_in_sf') }}</label>
                            <select class="form-control select2bs4" id="presently_working_in_sf"
                                name="presently_working_in_sf">
                                <option value="">Select </option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
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

                        <div class="form-group col-md-6 last_month_year_in_sf" style="display:none">
                            <label
                                for="last_month_year_in_sf">{{ trans('cruds.employerJobs.fields.last_month_year_in_sf') }}</label>
                                <input type="text" class="form-control" name="last_month_year_in_sf" id="last_month_year_in_sf">

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
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
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
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
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
                                <option value="spanish">spanish</option>
                                <option value="korean">korean</option>
                                <option value="chinese">chinese</option>
                                <option value="Other">Other</option>
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

                        <div class="form-group col-md-6 other_any_other_langauge" style="display:none">
                            <label
                                for="other_any_other_langauge">{{ trans('cruds.employerJobs.fields.other_any_other_langauge') }}*</label>
                            <input type="text" id="other_any_other_langauge" name="other_any_other_langauge"
                                class="form-control"
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
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('current_pay_per_hour') ? 'has-error' : '' }}">
                                <label
                                    for="current_pay_per_hour">{{ trans('cruds.employerJobs.fields.current_pay_per_hour') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">$</span>
                                    <input type="number" id="current_pay_per_hour" name="current_pay_per_hour"
                                        placeholder="Enter Current pay per hour" class="form-control">
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
                        {{-- <div class="col-md-6">
                            <div class="form-group {{ $errors->has('resume') ? 'has-error' : '' }}">
                                <label for="resume">{{ trans('cruds.employerJobs.fields.resume') }}*</label>
                                <div class="input-group">
                                    <input type="file" id="fileInput" name="resume" class="form-control">
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
                        </div> --}}
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('reference_check') ? 'has-error' : '' }}">
                                <label
                                    for="reference_check">{{ trans('cruds.employerJobs.fields.reference_check') }}</label>
                                <select class="form-control select2bs4" id="reference_check" name="reference_check">
                                    <option value="">Select</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
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
                                    id="additional_information" name="additional_information" rows="3"></textarea>


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
                    </div>
                    <div>
                        <button class="btn btn-info submit-btn" type="submit">{{ trans('global.save') }}</button>
                        {{-- <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}"> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- START - EDIT MODEL -->
    <div class="modal fade" id="dub-candidate-model" role="dialog">
        <style>
            .selectedOneRow {
                background-color: red !important;
                color: #0f0e0e !important;
            }
        </style>
        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dublicate Candidate Found!</h5>
                    <button type="button" class="close cancel-btn cancelCandidateData" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="row">
                        <div class="form-group col-md-12">
                            <table class="table" id="dub_candidate_tbl">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Name</th>
                                        <th>DOB</th>
                                        <th>Email</th>
                                        <th>Mobile Number</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                        </div>

                        <div class="form-group col-md-12 text-center">
                            <button class="btn btn-sm btn-primary overwrite">overwrite</button>
                            <button class="btn btn-sm btn-primary viewCandidateData">View</button>
                            <button class="btn btn-sm btn-primary cancelCandidateData">Cancel</button>
                            <button class="btn btn-sm btn-primary create_new ">Create New</button>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- END - EDIT MODEL -->
@endsection

@push('js')
    <!-- jquery-validation -->
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>

    @include('admin.customJs.employerJobs.applyJob')
@endpush


