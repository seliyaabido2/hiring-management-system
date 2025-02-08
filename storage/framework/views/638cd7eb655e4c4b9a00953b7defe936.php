<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    
    <div class="col-lg-6 col-md-10">
        <div class="card-group">
            <div class="card p-4 black-bg">
                <img src="<?php echo e(asset('images/bod-logo.png')); ?>" class="img-fluid auth-logo" alt="">
                <div class="card-body">
                    <?php if(\Session::has('message')): ?>
                        <p class="alert alert-info">
                            <?php echo e(\Session::get('message')); ?>

                        </p>
                    <?php endif; ?>
                    <?php
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
                    ?>
                    <form method="POST" action="<?php echo e(route('login')); ?>">
                        <?php echo e(csrf_field()); ?>

                        <!-- <h1><?php echo e(trans('panel.site_title')); ?></h1> -->
                        <p class=" text-white"><?php echo e(trans('global.login')); ?></p>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-user"></i>
                                </span>
                            </div>
                            <input name="email" type="text" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>"  autofocus placeholder="<?php echo e(trans('global.login_email')); ?>" value="<?php echo e($login_email); ?>">
                            <?php if($errors->has('email')): ?>
                                <div class="invalid-feedback">
                                    <?php echo e($errors->first('email')); ?>

                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input name="password" type="password"  value="<?php echo e($login_pass); ?>" class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>"  placeholder="<?php echo e(trans('global.login_password')); ?>">
                            <?php if($errors->has('password')): ?>
                                <div class="invalid-feedback">
                                    <?php echo e($errors->first('password')); ?>

                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="input-group mb-4">
                            <div class="form-check checkbox">

                                <input class="form-check-input " name="remember" type="checkbox" id="remember"  style="vertical-align: middle;" <?php echo e($is_remember); ?> />
                                <label class="form-check-label text-white" for="remember" style="vertical-align: middle;">
                                    <?php echo e(trans('global.remember_me')); ?>

                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-12 text-sm-left text-center">
                                <button type="submit" class="btn btn-primary px-4 custom-btn">
                                    <?php echo e(trans('global.login')); ?>

                                </button>
                            </div>
                            <div class="col-sm-6 col-12 text-sm-right text-center">
                                <a class="btn btn-link px-0 gray-text" href="<?php echo e(route('password.request')); ?>">
                                    <?php echo e(trans('global.forgot_password')); ?>

                                </a>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/laravel/bod/resources/views/auth/login.blade.php ENDPATH**/ ?>