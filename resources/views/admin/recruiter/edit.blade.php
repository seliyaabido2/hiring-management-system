@extends('layouts.admin')
@section('content')

<div class="card">

    <form action="{{ route("admin.RecruiterPartner.update", [$recruiterDetail->id]) }}"  id="recruiterEditFormId" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="hidden" id="RoleName" value="Recruiter">
        <input type="hidden" id="userId" value="{{$recruiterDetail->id}}">

        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.recruiter.title').' Details' }}
        </div>

        <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">

                            <label for="first_name">Client Name*</label>

                            <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name', isset($recruiterDetail) ? $recruiterDetail->first_name : '') }}" placeholder="First Name" >

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
                            <input type="text" id="authorized_name" name="authorized_name" class="form-control" value="{{ old('authorized_name', isset($recruiterDetail) ? $recruiterDetail->RecruiterDetail->authorized_name : '') }}" placeholder="Authorized Name" >

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
                            <input type="text" id="phone_no" name="phone_no" class="form-control" value="{{ old('phone_no', isset($recruiterDetail) ? $recruiterDetail->RecruiterDetail->phone_no : '') }}" placeholder="phone No" >

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
                            <input type="text" id="email" name="email" class="form-control" value="{{ old('email', isset($recruiterDetail) ? $recruiterDetail->email : '') }}" placeholder="Email" >

                            @if($errors->has('email'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </em>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('company_name') ? 'has-error' : '' }}">

                            <label for="company_name">Company Name*</label>
                            <input type="text" id="company_name" name="company_name" class="form-control" value="{{ old('company_name', isset($recruiterDetail) ? $recruiterDetail->RecruiterDetail->company_name : '') }}" placeholder="Company Name" >

                            @if($errors->has('company_name'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('company_name') }}
                                </em>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                            <label for="location">Select location*</label>

                            <input type="text" name="location" id="location" class="form-control"  value="{{ old('location', isset($recruiterDetail) ? $recruiterDetail->RecruiterDetail->location : '') }}" placeholder="Choose Location">
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', isset($recruiterDetail) ? $recruiterDetail->RecruiterDetail->latitude : '') }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', isset($recruiterDetail) ? $recruiterDetail->RecruiterDetail->longitude : '') }}">
                            <label class="location-error error"></label>
                            @if($errors->has('location'))
                            <em class="invalid-feedback">
                                {{ $errors->first('location') }}
                            </em>
                            @endif
                        </div>
                    </div>
                </div>




                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">

                            <label for="status">status*</label>

                            <select class="form-control select2bs4" id="status" name="recruiterStatus" style="width: 100%;">
                                <option value="">select status</option>

                                        <option value="Active" <?php if ($recruiterDetail->status == 'Active') {
                                            echo 'selected';
                                        } ?>>
                                        Active</option>
                                        <option value="Deactive" <?php if ($recruiterDetail->status == 'Deactive') {
                                            echo 'selected';
                                        } ?>>
                                        {{ trans('global.deactive') }}</option>
                            </select>

                            @if($errors->has('status'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </em>
                            @endif

                        </div>
                    </div>
                </div>



        </div>

        <div class="card-header">
            {{ trans('global.edit').' Contract Details' }}
        </div>

        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('contract_type') ? 'has-error' : '' }}">

                        <label for="contract_type">Contract Type*</label>

                        <select class="form-control select2bs4" id="contract_type" name="contract_type" style="width: 100%;">
                            <option value="">Select Contract Type</option>

                            @foreach ($contracts as $contractType)
                            <option value="{{ $contractType->id }}"  {{$recruiterDetail->AssignedOneContractDetail->ContractDetail->id === $contractType->id ? 'selected' : '' }}>
                                {{ $contractType->name }}
                            </option>
                            @endforeach
                        </select>

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
                        <div class='input-group date' id='start_date'>

                            <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                            </span>

                            <input type='text' placeholder="Start Date" id="input_start_date" class="form-control" name="start_date" value="{{ old('start_date', isset($recruiterDetail) ? date('d-m-Y', strtotime($recruiterDetail->AssignedOneContractDetail->start_date)) : '') }}"/>

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
                        <div class='input-group date' id='end_date'>

                            <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                            </span>
                            <input type='text' placeholder="End Date" id="input_end_date" value="{{ old('end_date', isset($recruiterDetail->AssignedOneContractDetail->end_date) ? date('d-m-Y', strtotime($recruiterDetail->AssignedOneContractDetail->end_date)) : '') }}" class="form-control" name="end_date" />

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

                        <label for="contract_upload">Contract Upload</label>

                        <input type="file" id="contract_upload" name="contract_upload" class="form-control">

                        @if($errors->has('contract_upload'))
                            <em class="invalid-feedback">
                                {{ $errors->first('contract_upload') }}
                            </em>
                        @endif

                    </div>
                </div>

            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-group">

                        <input type="checkbox" id="recurring_contracts" name="recurring_contracts" class="checkbox" {{ $recruiterDetail->AssignedOneContractDetail->recurring_contracts == '1' ? 'checked' : '' }}>

                        <label for="recurring_contracts">Recurring Contracts</label>
                    </div>
                </div>
            </div>


            <div class="mt-3">
                <button class="btn btn-danger" type="submit">{{ trans('global.save') }}</button>
            </div>
        </div>

    </form>

</div>
@endsection

@push('js')
<!-- jquery-validation -->
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>

@include('admin.customJs.recruiter.edit')
@endpush


