@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route('admin.SubAdmin.store') }}" method="POST" id="AdminFormId" autocomplete="off" enctype="multipart/form-data">
            @csrf

            <div class="card-header">
                {{ trans('global.create') }} {{ trans('cruds.admin.title').' Details' }}
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">

                            <label for="first_name">Sub Admin Name*</label>

                            <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Full Name" >

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
                            <input type="text" id="email" name="email" class="form-control"  placeholder="Email" >

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
                        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">

                            <label for="password">Password*</label>
                            <input type="password" id="password" name="password" class="form-control"  placeholder="Password" >

                            @if($errors->has('password'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('password') }}
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


                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('designation') ? 'has-error' : '' }}">

                           <label for="designation">Designation*</label>
                            <input type="text" placeholder="Enter designation"  class="form-control" name="designation">

                            @if($errors->has('designation'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('designation') }}
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
</div>
@endsection


@push('js')
<!-- jquery-validation -->
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>

@include('admin.customJs.admin.create')
@endpush

