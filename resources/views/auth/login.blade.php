@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    
    <div class="col-lg-6 col-md-10">
        <div class="card-group">
            <div class="card p-4 black-bg">
                <img src="{{ asset('images/bod-logo.png') }}" class="img-fluid auth-logo" alt="">
                <div class="card-body">
                    @if(\Session::has('message'))
                        <p class="alert alert-info">
                            {{ \Session::get('message') }}
                        </p>
                    @endif
                    @php
                    if(isset($_COOKIE['login_email']) && isset($_COOKIE['login_pass']))
                    {
                        $login_email = $_COOKIE['login_email'];
                        $login_pass  = $_COOKIE['login_pass'];
                        $is_remember = "checked='checked'";
                    }
                    else{
                        $login_email ='';
                        $login_pass = '';
                        $is_remember = "";
                    }
                    @endphp
                    <form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <!-- <h1>{{ trans('panel.site_title') }}</h1> -->
                        <p class=" text-white">{{ trans('global.login') }}</p>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-user"></i>
                                </span>
                            </div>
                            <input name="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"  autofocus placeholder="{{ trans('global.login_email') }}" value="{{ $login_email }}">
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input name="password" type="password"  value="{{ $login_pass }}" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"  placeholder="{{ trans('global.login_password') }}">
                            @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                        </div>

                        <div class="input-group mb-4">
                            <div class="form-check checkbox">

                                <input class="form-check-input " name="remember" type="checkbox" id="remember"  style="vertical-align: middle;" {{ $is_remember }} />
                                <label class="form-check-label text-white" for="remember" style="vertical-align: middle;">
                                    {{ trans('global.remember_me') }}
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-12 text-sm-left text-center">
                                <button type="submit" class="btn btn-primary px-4 custom-btn">
                                    {{ trans('global.login') }}
                                </button>
                            </div>
                            <div class="col-sm-6 col-12 text-sm-right text-center">
                                <a class="btn btn-link px-0 gray-text" href="{{ route('password.request') }}">
                                    {{ trans('global.forgot_password') }}
                                </a>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


