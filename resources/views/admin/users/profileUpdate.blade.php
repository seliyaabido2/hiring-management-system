@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.profile.title_singular') }}
    </div>

    <div class="card-body">
     
        <form method="POST"  action="{{ route('admin.users.profile.update') }}" id="userEditFormId"  enctype="multipart/form-data">
         @csrf
         
            <input type="hidden" name="RoleId" value="{{$user->roles[0]->id}}">
            <input type="hidden" name="RoleName" id="RoleName" value="{{$user->roles[0]->title}}">
            <input type="hidden" name="user_id" value="{{$user->id}}">

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
                        <label for="last_name">{{ trans('cruds.user.fields.last_name') }}</label>
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
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($user) ? $user->email : '') }}" >
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
                @if(isset($user->roles[0]->title) && $user->roles[0]->title != 'Super Admin')

                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                        <label for="location">Select location*</label>

                        <input type="text" name="location" id="location" class="form-control" value="{{ old('location', isset($userDetail->$model) ? $userDetail->$model->location : '') }}" placeholder="Choose Location" >
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', isset($userDetail->$model) ? $userDetail->$model->latitude : '') }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', isset($userDetail->$model) ? $userDetail->$model->longitude : '') }}">
                        <label class="location-error error"></label>
                        @if($errors->has('location'))
                        <em class="invalid-feedback">
                            {{ $errors->first('location') }}
                        </em>
                        @endif
                    </div>
                </div>

            @endif

            <div class="col-md-6">
                <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                    <label for="image">{{ trans('cruds.user.fields.image') }}</label>
                    <input type="file" id="image" name="image" class="form-control">
                    <br>
    
                    @if($user->image != null)
                        <img src="\user_image\{{$user->image}}" height="100" width="100">
                    @else
                        <img src="\image\default-user.jpg" height="100" width="100">
                    @endif
    
                    @if($errors->has('image'))
                    <em class="invalid-feedback">
                        {{ $errors->first('image') }}
                    </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.user.fields.image_helper') }}
                    </p>
                </div>
            </div>


            </div>  
            @if(isset($user->roles[0]->title) && $user->roles[0]->title != 'Super Admin')
           
              
           
                 @include('admin.users.user_detail')

            @endif
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection

@push('js')
<!-- jquery-validation -->
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>

@include('admin.customJs.users.edit')
@endpush