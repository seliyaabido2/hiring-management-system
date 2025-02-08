@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-10">
        <div class="card-group">
            
            <div class="card p-4 black-bg position-relative">
            <img src="{{ asset('images/bod-logo.png') }}" class="img-fluid auth-logo" alt="">
            <div class="login-back">
                <a href="{{url('login')}}" class=" pl-5 btn-sm text-light"><i class="fas fa-arrow-left fs-2x"></i></a>
            </div>
                <div class="card-body">

                    <form method="POST" action="{{ route('forget-mail-send') }}">
                        {{ csrf_field() }}
                        <!-- <h1>
                            <div class="login-logo">
                                <a href="#">
                                    {{ trans('panel.site_title') }}
                                </a>
                            </div>
                        </h1> -->
                        <p class="text-muted"></p>
                        <div>
                            <div class="form-group has-email">
                                <input type="email" name="email" class="form-control" placeholder="{{ trans('global.login_email') }}">
                                @if($errors->has('email'))
                                <div class="invalid-email error">
                                    {{ $errors->first('email') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary btn-block btn-flat custom-btn">
                                    {{ trans('global.reset_password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection