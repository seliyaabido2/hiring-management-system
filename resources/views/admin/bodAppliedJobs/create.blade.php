@extends('layouts.admin')
@section('content')

@push('css')


@endpush

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.employerJobs.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route('admin.employerJobs.store') }}" method="POST" id="employerJobForm" autocomplete="off" enctype="multipart/form-data">
            @csrf

            <div class="row">

                <div class="col-md-8">
                    <div class="form-group {{ $errors->has('job_title') ? 'has-error' : '' }}">
                        <label for="job_title">{{ trans('cruds.employerJobs.fields.job_title') }}*</label>
                        <input type="text" id="job_title" name="job_title" class="form-control" value="{{ old('job_title', isset($user) ? $user->job_title : '') }}" placeholder="First Name" >
                        @if($errors->has('job_title'))
                            <em class="invalid-feedback">
                                {{ $errors->first('job_title') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.job_title_helper') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group {{ $errors->has('job_description') ? 'has-error' : '' }}">
                        <label for="job_description">{{ trans('cruds.employerJobs.fields.job_description') }}*</label>
                        <textarea placeholder="Job description" class="form-control" id="job_description" name="job_description" rows="3" ></textarea>


                        @if($errors->has('job_description'))
                            <em class="invalid-feedback">
                                {{ $errors->first('job_description') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.job_description_helper') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group {{ $errors->has('job_address') ? 'has-error' : '' }}">
                        <label for="job_address">{{ trans('cruds.employerJobs.fields.job_address') }}*</label>

                        <textarea placeholder="Job Address" class="form-control" id="job_address" name="job_address" rows="3" ></textarea>

                        @if($errors->has('job_address'))
                            <em class="invalid-feedback">
                                {{ $errors->first('job_address') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.job_address_helper') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="experience_sf" >{{ trans('cruds.employerJobs.fields.experience_sf') }}*</label>
                        <select class="form-control select2bs4" id="experience_sf" name="experience_sf" >
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                            </select>
                        @if($errors->has('experience_sf'))
                            <em class="invalid-feedback">
                                {{ $errors->first('experience_sf') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.experience_sf_helper') }}
                        </p>
                </div>
                <div class="form-group col-md-6">
                    <label for="experience_without_sf">{{ trans('cruds.employerJobs.fields.experience_without_sf') }}*</label>
                        <select class="form-control select2bs4" id="experience_without_sf" name="experience_without_sf">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                        @if($errors->has('experience_without_sf'))
                            <em class="invalid-feedback">
                                {{ $errors->first('experience_without_sf') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.experience_without_sf_helper') }}
                        </p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="license_candidate_basic_training">{{ trans('cruds.employerJobs.fields.license_candidate_basic_training') }}*</label>
                        <select class="form-control select2bs4" id="license_candidate_basic_training" name="license_candidate_basic_training">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                        @if($errors->has('license_candidate_basic_training'))
                            <em class="invalid-feedback">
                                {{ $errors->first('license_candidate_basic_training') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.license_candidate_basic_training_helper') }}
                        </p>
                </div>
                <div class="form-group col-md-6">
                    <label for="license_candidate_no_experience">{{ trans('cruds.employerJobs.fields.license_candidate_no_experience') }}*</label>
                        <select class="form-control select2bs4" id="license_candidate_no_experience" name="license_candidate_no_experience">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                        @if($errors->has('license_candidate_no_experience'))
                            <em class="invalid-feedback">
                                {{ $errors->first('license_candidate_no_experience') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.license_candidate_no_experience_helper') }}
                        </p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-8">
                    <label for="license_candidate_banking_finance">{{ trans('cruds.employerJobs.fields.license_candidate_banking_finance') }}*</label>
                    <select class="form-control select2bs4" id="license_candidate_banking_finance" name="license_candidate_banking_finance">
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                    @if($errors->has('license_candidate_banking_finance'))
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
                    <label for="job_role">{{ trans('cruds.employerJobs.fields.job_role') }}*</label>
                    <select class="form-control select2bs4" id="job_role" name="job_role">
                        <option value="Service Focused">Service Focused</option>
                        <option value="Sales Focused">Sales Focused</option>
                        <option value="Both">Both</option>
                    </select>
                    @if($errors->has('job_role'))
                        <em class="invalid-feedback">
                            {{ $errors->first('job_role') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.job_role_helper') }}
                    </p>
                </div>
                <div class="form-group col-md-6">
                    <label for="total_number_of_working_days">{{ trans('cruds.employerJobs.fields.total_number_of_working_days') }}*</label>
                    <select class="form-control select2bs4" id="total_number_of_working_days" name="total_number_of_working_days">

                        <option value="1">1 Day</option>
                        <option value="2">2 Days</option>
                        <option value="3">3 Days</option>
                        <option value="4">4 Days</option>
                        <option value="5">5 Days</option>
                        <option value="6">6 Days</option>
                        <option value="7">7 Days</option>
                    </select>
                    @if($errors->has('total_number_of_working_days'))
                        <em class="invalid-feedback">
                            {{ $errors->first('total_number_of_working_days') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.total_number_of_working_days_helper') }}
                    </p>
                </div>

            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="job_shift">{{ trans('cruds.employerJobs.fields.job_shift') }}*</label>
                    <select class="form-control select2bs4" id="job_shift" name="job_shift">
                        <option value="Work from home">Work from home</option>
                        <option value="Work from office">Work from office</option>
                    </select>
                    @if($errors->has('job_shift'))
                        <em class="invalid-feedback">
                            {{ $errors->first('job_shift') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.job_shift_helper') }}
                    </p>
                </div>
                <div class="form-group col-md-6">
                    <label for="job_type">{{ trans('cruds.employerJobs.fields.job_type') }}*</label>
                    <select class="form-control select2bs4" id="job_type" name="job_type">
                        <option value="Full Time">Full Time</option>
                        <option value="Hourly">Hourly</option>
                    </select>
                    @if($errors->has('job_type'))
                        <em class="invalid-feedback">
                            {{ $errors->first('job_type') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.job_type_helper') }}
                    </p>
                </div>

            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="working_days_per_week">{{ trans('cruds.employerJobs.fields.working_days_per_week') }}*</label>
                    <select class="form-control select2bs4" id="working_days_per_week" name="working_days_per_week">
                        <option value="1">1 Days</option>
                        <option value="2">2 Days</option>
                        <option value="3">3 Days</option>
                        <option value="4">4 Days</option>
                        <option value="5">5 Days</option>
                        <option value="6">6 Days</option>
                        <option value="7">7 Days</option>
                    </select>
                    @if($errors->has('working_days_per_week'))
                        <em class="invalid-feedback">
                            {{ $errors->first('working_days_per_week') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.working_days_per_week_helper') }}
                    </p>
                </div>
                <div class="form-group col-md-6">
                    <label for="working_day">{{ trans('cruds.employerJobs.fields.working_day') }}*</label>
                    <select class="form-control select2bs4" id="working_day" name="working_day">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                    </select>
                    @if($errors->has('working_day'))
                        <em class="invalid-feedback">
                            {{ $errors->first('working_day') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.working_day_helper') }}
                    </p>
                </div>

            </div>
            <div class="row">
                <div class="form-group col-md-8">
                    <label for="salary_type">{{ trans('cruds.employerJobs.fields.salary_type') }}*</label>
                    <select class="form-control select2bs4" id="salary_type" name="salary_type">
                        <option value="Hourly Pay">Hourly Pay</option>
                        <option value="Monthly Pay">Monthly Pay</option>
                    </select>
                    @if($errors->has('salary_type'))
                        <em class="invalid-feedback">
                            {{ $errors->first('salary_type') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.salary_type_helper') }}
                    </p>
                </div>

            </div>
            <div class="row">
                <div class="form-group col-md-6">

                    <label for="pay_per_hour">{{ trans('cruds.employerJobs.fields.pay_per_hour') }}*</label>
                   <div class="input-group">
                   <span class="input-group-text" id="basic-addon1">$</span>
                       <input type="number" id="pay_per_hour" name="pay_per_hour" class="form-control" value="" min="1" placeholder="{{ trans('cruds.employerJobs.fields.pay_per_hour') }}" >
                   </div>

                    @if($errors->has('pay_per_hour'))
                        <em class="invalid-feedback">
                            {{ $errors->first('pay_per_hour') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.pay_per_hour_helper') }}
                    </p>
                </div>
                <div class="form-group col-md-6">
                    <label for="pay_day">{{ trans('cruds.employerJobs.fields.pay_day') }}*</label>
                    <input type="number" min="1" id="pay_day" name="pay_day" class="form-control" value="" placeholder="{{ trans('cruds.employerJobs.fields.pay_day') }}" >

                    @if($errors->has('pay_day'))
                        <em class="invalid-feedback">
                            {{ $errors->first('pay_day') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.pay_day_helper') }}
                    </p>
                </div>

            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="bonus_commission">{{ trans('cruds.employerJobs.fields.bonus_commission') }}*</label>
                    <input type="number" min="1" id="bonus_commission" name="bonus_commission" class="form-control" value="" placeholder="{{ trans('cruds.employerJobs.fields.bonus_commission') }}" >

                    @if($errors->has('bonus_commission'))
                        <em class="invalid-feedback">
                            {{ $errors->first('bonus_commission') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.bonus_commission_helper') }}
                    </p>
                </div>

            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group {{ $errors->has('job_benefits') ? 'has-error' : '' }}">
                        <label for="job_benefits">{{ trans('cruds.employerJobs.fields.job_benefits') }}*</label>
                        <textarea placeholder="Job description" class="form-control" id="job_benefits" name="job_benefits" rows="3" ></textarea>


                        @if($errors->has('job_benefits'))
                            <em class="invalid-feedback">
                                {{ $errors->first('job_benefits') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.job_benefits_helper') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-8">
                    <label for="language_preference">{{ trans('cruds.employerJobs.fields.language_preference') }}*</label>
                    <select class="form-control select2bs4" id="language_preference" name="any_other_langauge">
                        <option value="English">English</option>
                        <option value="Hindi">Hindi</option>
                        <option value="Hindi">Gujarati</option>
                    </select>
                    @if($errors->has('any_other_langauge'))
                        <em class="invalid-feedback">
                            {{ $errors->first('any_other_langauge') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.any_other_langauge_helper') }}
                    </p>
                </div>

            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="job_field_role">{{ trans('cruds.employerJobs.fields.job_field_role') }}*</label>
                    <select class="form-control select2bs4" id="job_field_role" name="job_field_role">
                        <option value="Service Focused">Service Focused</option>
                        <option value="Sales Focused">Sales Focused</option>
                        <option value="Both">Both</option>
                    </select>
                    @if($errors->has('job_field_role'))
                        <em class="invalid-feedback">
                            {{ $errors->first('job_field_role') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.job_field_role_helper') }}
                    </p>
                </div>

                <div class="form-group col-md-6">
                    <label for="parking_free">{{ trans('cruds.employerJobs.fields.parking_free') }}*</label>
                    <select class="form-control select2bs4" id="parking_free" name="parking_free">
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                    @if($errors->has('parking_free'))
                        <em class="invalid-feedback">
                            {{ $errors->first('parking_free') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.parking_free_helper') }}
                    </p>
                </div>


            </div>
            <div class="row parking_fee_div" style="display:none">
                <div class="form-group col-md-6">
                    <label for="parking_fee">{{ trans('cruds.employerJobs.fields.parking_fee') }}*</label>
                    <input type="text" id="parking_fee" name="parking_fee" class="form-control" value="" placeholder="{{ trans('cruds.employerJobs.fields.parking_fee') }}" >

                    @if($errors->has('parking_fee'))
                        <em class="invalid-feedback">
                            {{ $errors->first('parking_fee') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.parking_fee_helper') }}
                    </p>
                </div>

            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="license_requirement">{{ trans('cruds.employerJobs.fields.license_requirement') }}*</label>
                    <select class="form-control select2bs4" id="license_requirement" name="license_requirement">
                        <option value="">Select License</option>
                        <option value="Property & Casualty (P&C)">Property & Casualty (P&C)</option>
                        <option value="Life and Health (L&H)">Life and Health (L&H)</option>
                        <option value="Both">Both</option>
                        <option value="No License Required">No License Required</option>
                    </select>
                    @if($errors->has('license_requirement'))
                        <em class="invalid-feedback">
                            {{ $errors->first('license_requirement') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.license_requirement_helper') }}
                    </p>
                </div>
                <div class="form-group col-md-6 other_license_requirement" style="display:none">
                    <label for="other_license_requirements">{{ trans('cruds.employerJobs.fields.other_license_requirement') }}*</label>
                    <input type="text" id="other_license_requirement" name="other_license_requirement" class="form-control" value="" placeholder="{{ trans('cruds.employerJobs.fields.other_license_requirement') }}" >

                    @if($errors->has('other_license_requirement'))
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
                <div class="form-group col-md-6  {{ $errors->has('job_start_date') ? 'has-error' : '' }}">
                    <label for="job_start_date">{{ trans('cruds.employerJobs.fields.job_start_date') }}*</label>
                <div class='input-group date' id='job_start_date'>

                            <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                            </span>
                        <input type='text'placeholder="job start date" class="form-control" name="job_start_date" />
                @if($errors->has('job_start_date'))
                        <em class="invalid-feedback">
                            {{ $errors->first('job_start_date') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.job_start_date_helper') }}
                    </p>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="number_of_vacancies">{{ trans('cruds.employerJobs.fields.number_of_vacancies') }}*</label>
                    <input type="number" min="1" id="number_of_vacancies" name="number_of_vacancies" class="form-control" value="" placeholder="{{ trans('cruds.employerJobs.fields.number_of_vacancies') }}" >

                    @if($errors->has('number_of_vacancies'))
                        <em class="invalid-feedback">
                            {{ $errors->first('number_of_vacancies') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.number_of_vacancies_helper') }}
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                        <label for="job_qualification">{{ trans('cruds.employerJobs.fields.job_qualification') }}*</label>
                        <select class="form-control select2bs4" id="job_qualification" name="job_qualification">
                            <option value="BCA">BCA</option>
                            <option value="MCA">MCA</option>
                            <option value="Other">Other</option>
                        </select>
                        @if($errors->has('job_qualification'))
                            <em class="invalid-feedback">
                                {{ $errors->first('job_qualification') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.job_qualification_helper') }}
                        </p>
                </div>

                <div class="form-group col-md-6 other_job_qualification" style="display:none">
                        <label for="other_job_qualification">{{ trans('cruds.employerJobs.fields.other_job_qualification') }}*</label>
                        <input type="text" id="other_job_qualification" name="other_job_qualification" class="form-control" placeholder="{{ trans('cruds.employerJobs.fields.other_job_qualification') }}" >

                        @if($errors->has('other_job_qualification'))
                            <em class="invalid-feedback">
                                {{ $errors->first('other_job_qualification') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.employerJobs.fields.other_job_qualification_helper') }}
                        </p>
                </div>

            </div>
            <div class="row">

                <div class="form-group col-md-6">
                    <label for="job_recruiment_duration">{{ trans('cruds.employerJobs.fields.job_recruiment_duration') }}*</label>
                    <input type="number" min="1" id="job_recruiment_duration" name="job_recruiment_duration" class="form-control" value="" placeholder="{{ trans('cruds.employerJobs.fields.job_recruiment_duration') }}" >

                    @if($errors->has('job_recruiment_duration'))
                        <em class="invalid-feedback">
                            {{ $errors->first('job_recruiment_duration') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.employerJobs.fields.job_recruiment_duration_helper') }}
                    </p>
                </div>

                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('additional_information') ? 'has-error' : '' }}">
                        <label for="additional_information">{{ trans('cruds.employerJobs.fields.additional_information') }}*</label>
                        <textarea placeholder="{{ trans('cruds.employerJobs.fields.additional_information') }}" class="form-control" id="additional_information" name="additional_information" rows="3" ></textarea>


                        @if($errors->has('additional_information'))
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

@include('admin.customJs.employerJobs.create')
@endpush

