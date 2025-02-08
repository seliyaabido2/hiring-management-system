@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route('admin.employer.store') }}" method="POST" id="employerCreateFormId" enctype="multipart/form-data">
            @csrf

            <div class="card-header">
                {{ trans('global.create') }} {{ trans('cruds.employer.title').' Details' }}
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">

                            <label for="first_name">Client Name*</label>

                            <input type="text" id="first_name" name="first_name" class="form-control first_name" placeholder="First Name" >

                            @if($errors->has('first_name'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('first_name') }}
                                </em>
                            @endif

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('authorized_name') ? 'has-error' : '' }}">

                            <label for="authorized_name">Authorized Name*</label>
                            <input type="text" id="authorized_name" name="authorized_name" class="form-control"  placeholder="Authorized Name" >

                            @if($errors->has('authorized_name'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('authorized_name') }}
                                </em>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('phone_no') ? 'has-error' : '' }}">

                            <label for="phone_no">Phone No*</label>
                            <input type="text" id="phone_no" name="phone_no" class="form-control" placeholder="phone No" >

                            @if($errors->has('phone_no'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('phone_no') }}
                                </em>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">

                            <label for="email">Email*</label>
                            <input type="text" id="email" name="email" class="form-control"  placeholder="Email"  >

                            @if($errors->has('email'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </em>
                            @endif

                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror


                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">

                            <label for="password">Password*</label>
                            <input type="password" id="password" name="password" class="form-control"  placeholder="Password" autocomplete="new-password" >

                            @if($errors->has('password'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </em>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('company_name') ? 'has-error' : '' }}">

                            <label for="company_name">Company Name*</label>
                            <input type="text" id="company_name" name="company_name" class="form-control"  placeholder="Company Name" >

                            @if($errors->has('company_name'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('company_name') }}
                                </em>
                            @endif

                        </div>
                    </div>


                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                            <label for="location">Select location*</label>

                            <input type="text" name="location" id="location" class="form-control" placeholder="Choose Location">
                            <input type="hidden" id="latitude" name="latitude" value="">
                            <input type="hidden" name="longitude" id="longitude" value="">
                            <label class="location-error error"></label>
                            @if($errors->has('location'))
                            <em class="invalid-feedback">
                                {{ $errors->first('location') }}
                            </em>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-header">
                {{ trans('global.create').' Contract Details' }}
            </div>

            <div class="card-body contract_block">
                <div class="contract_section" id="contract_section_1">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('contract_type') ? 'has-error' : '' }}">

                                <label for="contract_type">Contract Type*</label>


                                <select class="form-control select2bs4 contract_type" id="contract_type_1" name="contract_type[1]" style="width: 100%;">
                                    <option value="">Select Contract Type</option>

                                    @foreach ($contracts as $contractType)
                                    <option value="{{ $contractType->id }}">
                                        {{ $contractType->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="error-message"></div>


                                @if($errors->has('contract_type'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('contract_type') }}
                                    </em>
                                @endif

                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group  {{ $errors->has('start_date') ? 'has-error' : '' }}">
                                <label for="start_date">Start Date*</label>
                                <div class='input-group date' id='start_date_1'>

                                    <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                                    </span>

                                    <input type='text' placeholder="Start Date" id="input_start_date_1" class="form-control input_start_date datepicker" name="start_date[1]"/>

                                    @if($errors->has('start_date'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('start_date') }}
                                    </em>
                                    @endif

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">


                            <div class="form-group  {{ $errors->has('end_date') ? 'has-error' : '' }}">
                                <label for="end_date">End Date (Optional)</label>
                                <div class='input-group date' id='end_date_1'>

                                    <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                                    </span>
                                    <input type='text' placeholder="End Date" id="input_end_date_1" class="form-control input_end_date datepicker" name="end_date[1]" />

                                    @if($errors->has('end_date'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('end_date') }}
                                    </em>
                                    @endif

                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('contract_upload') ? 'has-error' : '' }}">

                                <label for="contract_upload">Contract Upload*</label>

                                <input type="file" id="contract_upload_1" name="contract_upload[1]" class="form-control">

                                @if($errors->has('contract_upload'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('contract_upload') }}
                                    </em>
                                @endif

                            </div>
                        </div>

                    </div>

                    <div class="row" id="checklist_upload_row_1" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('checklist_upload') ? 'has-error' : '' }}">

                                <label for="checklist_upload">Check List Upload*</label>

                                <input type="file" id="checklist_upload_1" name="checklist_upload[1]" class="form-control">

                                @if($errors->has('checklist_upload'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('checklist_upload') }}
                                    </em>
                                @endif

                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id="recurring_contracts_1" name="recurring_contracts[1]" value="0" class="checkbox">
                                <label for="recurring_contracts">Recurring Contracts</label>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="btn btn-sm btn-success add_contract">+</a>

                <a class="btn btn-sm btn-danger remove_contract">-</a>

            </div>

            <div class="mt-3">
                <input class="btn btn-success" type="submit" value="{{ trans('global.save') }}">
            </div>



        </form>


    </div>
</div>
@endsection


@push('js')
<!-- jquery-validation -->
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>

@include('admin.customJs.employer.create')
@endpush

