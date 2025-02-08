@extends('layouts.admin')
@section('content')

<div class="card">



    <form action="{{ route("admin.employer.update", [$employerDetail->id]) }}"  id="employerEditFormId" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="hidden" id="userId" value="{{$employerDetail->id}}">

        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.employer.title').' Details' }}
        </div>

        <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">

                            <label for="first_name">Client Name*</label>

                            <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name', isset($employerDetail) ? $employerDetail->first_name : '') }}" placeholder="First Name" >

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
                            <input type="text" id="authorized_name" name="authorized_name" class="form-control" value="{{ old('authorized_name', isset($employerDetail) ? $employerDetail->EmployerDetail->authorized_name : '') }}" placeholder="Authorized Name" >

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
                            <input type="text" id="phone_no" name="phone_no" class="form-control" value="{{ old('phone_no', isset($employerDetail) ? $employerDetail->EmployerDetail->phone_no : '') }}" placeholder="phone No" >

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
                            <input type="text" id="email" name="email" class="form-control" value="{{ old('email', isset($employerDetail) ? $employerDetail->email : '') }}" placeholder="Email" >

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
                            <input type="text" id="company_name" name="company_name" class="form-control" value="{{ old('company_name', isset($employerDetail) ? $employerDetail->EmployerDetail->company_name : '') }}" placeholder="Company Name" >

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

                            <input type="text" name="location" id="location" class="form-control" placeholder="Choose Location" value="{{ old('location', isset($employerDetail) ? $employerDetail->EmployerDetail->location : '') }}">
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', isset($employerDetail) ? $employerDetail->EmployerDetail->latitude : '') }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', isset($employerDetail) ? $employerDetail->EmployerDetail->longitude : '') }}">
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

                        <div class="form-group {{ $errors->has('employerStatus') ? 'has-error' : '' }}">

                            <label for="employerStatus">status*</label>

                            <select class="form-control select2bs4" id="employerStatus" name="employerStatus" style="width: 100%;">
                                <option value="">select status</option>

                                        <option value="Active" <?php if ($employerDetail->status == 'Active') {
                                            echo 'selected';
                                        } ?>>
                                        Active</option>
                                        <option value="Deactive" <?php if ($employerDetail->status == 'Deactive') {
                                            echo 'selected';
                                        } ?>>
                                        {{ trans('global.deactive') }}</option>
                            </select>

                            @if($errors->has('employerStatus'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('employerStatus') }}
                                </em>
                            @endif

                        </div>
                    </div>
                </div>
        </div>

        <div class="card-header">
            {{ trans('global.edit').' Contract Details' }}
        </div>
        <input type="hidden" id="sectionCount" value="{{count($employerDetail->AssignedContractDetail)}}">
        @if(isset($employerDetail->AssignedContractDetail))



                <div class="card-body contract_block">
                    @foreach ($employerDetail->AssignedContractDetail as $key => $AssignedContractDetail)


                    <div class="contract_section" id="contract_section_{{$key+1}}">

                        <input type="hidden" name="assign_contract_id[{{$key+1}}]" id="assign_contract_id_{{$key+1}}" value="{{$AssignedContractDetail->id }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('contract_type') ? 'has-error' : '' }}">

                                    <label for="contract_type">Contract Type*</label>


                                    <select class="form-control select2bs4 contract_type" id="contract_type_{{$key+1}}" name="contract_type[{{$key+1}}]" style="width: 100%;">
                                        <option value="">Select Contract Type</option>

                                        @foreach ($contracts as $contractType)
                                        <option value="{{ $contractType->id }}"  {{$AssignedContractDetail->ContractDetail->id === $contractType->id ? 'selected' : '' }}>
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
                                    <div class='input-group date' id='start_date_{{$key+1}}'>

                                        <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                                        </span>


                                        <input type='text' placeholder="Start Date" id="input_start_date_{{$key+1}}" class="form-control datepicker input_start_date" name="start_date[{{$key+1}}]" value="{{ old('start_date', isset($AssignedContractDetail) ? date('d-m-Y', strtotime($AssignedContractDetail->start_date)) : '') }}"/>

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
                                    <div class='input-group date' id='end_date_{{$key+1}}'>

                                        <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                                        </span>
                                        <input type='text' placeholder="End Date" id="input_end_date_{{$key+1}}" value="{{ old('end_date', isset($AssignedContractDetail->end_date) ? date('d-m-Y', strtotime($AssignedContractDetail->end_date)) : '') }}" class="form-control datepicker input_end_date" name="end_date[{{$key+1}}]" />
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

                                    <input type="file" id="contract_upload_{{$key+1}}" name="contract_upload[{{$key+1}}]" class="form-control">

                                    @if($errors->has('contract_upload'))
                                        <em class="invalid-feedback">
                                            {{ $errors->first('contract_upload') }}
                                        </em>
                                    @endif

                                </div>
                            </div>

                        </div>

                        <div class="row" id="checklist_upload_row_{{$key+1}}" style="display: {{ $AssignedContractDetail->ContractDetail->id == 1 ? 'block' : 'none' }};">

                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('checklist_upload') ? 'has-error' : '' }}">

                                    <label for="checklist_upload">Check List Upload*</label>

                                    <input type="file" id="checklist_upload_{{$key+1}}" name="checklist_upload[{{$key+1}}]" class="form-control">

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
                                    <input type="checkbox" id="recurring_contracts_{{$key+1}}" name="recurring_contracts[{{$key+1}}]" value="{{$key+1}}" class="checkbox" {{ $AssignedContractDetail->recurring_contracts == '1' ? 'checked' : '' }}>
                                    <label for="recurring_contracts">Recurring Contracts</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(count($employerDetail->AssignedContractDetail) == ($key+1))
                    <a class="btn btn-sm btn-success add_contract">+</a>
                    <a class="btn btn-sm btn-danger remove_contract">-</a>
                    @endif
                    @endforeach

                </div>



        @endif


        <div class="mt-3">
            <button class="btn btn-success" type="submit">{{ trans('global.save') }}</button>
        </div>

    </form>

</div>
@endsection

@push('js')
<!-- jquery-validation -->
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>

@include('admin.customJs.employer.edit')
@endpush


