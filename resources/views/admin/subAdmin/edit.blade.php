@extends('layouts.admin')
@section('content')

<div class="card">

    <form action="{{ route("admin.SubAdmin.update", [$adminDetail->id]) }}"  id="AdminEditFormId" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="hidden" id="RoleName" value="Admin">
        <input type="hidden" id="userId" value="{{$adminDetail->id}}">

        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.admin.title').' Details' }}
        </div>

        <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">

                            <label for="first_name">Sub Admin Name*</label>


                            <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name', isset($adminDetail) ? $adminDetail->first_name : '') }}" placeholder="First Name" >

                            @if($errors->has('first_name'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('first_name') }}
                                </em>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">

                            <label for="email">Email*</label>
                            <input type="text" id="email" name="email" class="form-control" value="{{ old('email', isset($adminDetail) ? $adminDetail->email : '') }}" placeholder="Email" >

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
                        <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                            <label for="location">Select location*</label>

                            <input type="text" name="location" id="location" class="form-control"  value="{{ old('location', isset($adminDetail) ? $adminDetail->AdminDetail->location : '') }}" placeholder="Choose Location">
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', isset($adminDetail) ? $adminDetail->AdminDetail->latitude : '') }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', isset($adminDetail) ? $adminDetail->AdminDetail->longitude : '') }}">
                            <label class="location-error error"></label>
                            @if($errors->has('location'))
                            <em class="invalid-feedback">
                                {{ $errors->first('location') }}
                            </em>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('phone_no') ? 'has-error' : '' }}">

                            <label for="phone_no">Phone No*</label>
                            <input type="text" id="phone_no" name="phone_no" class="form-control" value="{{ old('phone_no', isset($adminDetail) ? $adminDetail->AdminDetail->phone_no : '') }}" placeholder="phone No" >

                            @if($errors->has('phone_no'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('phone_no') }}
                                </em>
                            @endif

                        </div>
                    </div>

                </div>


                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('designation') ? 'has-error' : '' }}">

                            <label for="designation">Designation*</label>
                            <input type="text" id="designation" name="designation" class="form-control" value="{{ old('designation', isset($adminDetail) ? $adminDetail->AdminDetail->designation : '') }}" placeholder="phone No" >

                            @if($errors->has('designation'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('designation') }}
                                </em>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-6">

                        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">

                            <label for="password">Password</label>

                            <input type="text" id="password" name="password" class="form-control" placeholder="Enter New Password">

                            @if($errors->has('password'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </em>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-6">

                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">

                            <label for="status">status*</label>

                            <select class="form-control select2bs4" id="status" name="status" style="width: 100%;">
                                <option value="">select status</option>

                                        <option value="Active" <?php if ($adminDetail->status == 'Active') {
                                            echo 'selected';
                                        } ?>>
                                        Active</option>
                                        <option value="Deactive" <?php if ($adminDetail->status == 'Deactive') {
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

@include('admin.customJs.admin.edit')
@endpush


