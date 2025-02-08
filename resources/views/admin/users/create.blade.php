@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST" id="userFormId" autocomplete="off" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-2">
                    <div class="form-check">
                        <input type="radio" class="form-check-input role-name" id="radio1" name="role" value="2" checked>Admin
                        <label class="form-check-label" for="radio1"></label>
                      </div>
                </div>
                <div class="col-md-2">
                      <div class="form-check">
                        <input type="radio" class="form-check-input role-name" id="radio2" name="role" value="3">Employer
                        <label class="form-check-label" for="radio2"></label>
                      </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input type="radio" class="form-check-input role-name" id="radio3" name="role" value="4">Recruiter
                        <label class="form-check-label"></label>
                    </div>
                </div>
            </div>
            <br>

           
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                        <label for="first_name">{{ trans('cruds.user.fields.first_name') }}*</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name', isset($user) ? $user->first_name : '') }}" placeholder="First Name" >
                        @if($errors->has('first_name'))
                            <em class="invalid-feedback">
                                {{ $errors->first('first_name') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.user.fields.first_name_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                        <label for="last_name">{{ trans('cruds.user.fields.last_name') }}*</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name', isset($user) ? $user->last_name : '') }}" placeholder="Last Name" >
                        @if($errors->has('last_name'))
                            <em class="invalid-feedback">
                                {{ $errors->first('last_name') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.user.fields.last_name_helper') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        <label for="email">{{ trans('cruds.user.fields.email') }}*</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($user) ? $user->email : '') }}" placeholder="Email Address" >
                        @if($errors->has('email'))
                            <em class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.user.fields.email_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        <label for="password">{{ trans('cruds.user.fields.password') }}</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                        @if($errors->has('password'))
                            <em class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.user.fields.password_helper') }}
                        </p>
                    </div>
                </div>
            </div>

          

            @include('admin.users.user_detail')
            
            <div>
                <button class="btn btn-danger" type="submit">{{ trans('global.save') }}</button>
            </div>
        </form>


    </div>
</div>
@endsection

@push('js')
<!-- jquery-validation -->
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>

@include('admin.customJs.users.create')
@endpush

