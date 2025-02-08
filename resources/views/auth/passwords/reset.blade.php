@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-10">
        <div class="card-group">
            <div class="card p-4 black-bg">
            <img src="{{ asset('images/bod-logo.png') }}" class="img-fluid auth-logo" alt="">
                <div class="card-body">
                    <form method="POST" id="rest-password-form" action="{{ route('password.request') }}">
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
                            <input name="token" value="{{ $token }}" type="hidden">
                            <div class="form-group has-feedback">
                                <input type="hidden" name="email" value="{{ $_GET['email'] }}" >
                                <input type="text" class="form-control"  value="{{ $_GET['email'] }}"  placeholder="{{ trans('global.login_email') }}" disabled>
                                @if($errors->has('email'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </em>
                                @endif
                            </div>
                            <div class="form-group has-feedback">
                                <input type="password" id="password" name="password" class="form-control"  placeholder="{{ trans('global.login_password') }}">
                                @if($errors->has('password'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('password') }}
                                    </em>
                                @endif
                            </div>
                            <div class="form-group has-feedback">
                                <input type="password" name="password_confirmation" class="form-control"  placeholder="{{ trans('global.login_password_confirmation') }}">
                                @if($errors->has('password_confirmation'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('password_confirmation') }}
                                    </em>
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

@section('scripts')

<!-- jquery-validation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>

<script>
    $('#rest-password-form').validate({
            rules: {
                email: {
                    required: true,
                    email:true,

                },
                password: {
                    required: true,
                        minlength : 4,
                        maxlength : 12,

                },
                password_confirmation: {
                    required: true,
                        minlength : 4,
                        maxlength : 12,
                    equalTo: "#password"
                }
            },
            messages: {
            email: {
                required: "Please enter valid email",

            },
            password: {
                required: "Please enter password.",
                minlength: "Please enter at least 4 characters.",
                maxlength :"Please enter no more than 12 characters."
            },
            password_confirmation: {
                required: "Please enter confirm password",
                minlength: "Please enter at least 4 characters.",
                maxlength :"Please enter no more than 12 characters.",
                equalTo: "Passwords do not match"
            }
            },
            submitHandler: function(form) {
            // Your code to handle the form submission
            form.submit();
            }
        });
</script>
@endsection
