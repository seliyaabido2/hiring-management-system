<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-10">
        <div class="card-group">
            
            <div class="card p-4 black-bg position-relative">
            <img src="<?php echo e(asset('images/bod-logo.png')); ?>" class="img-fluid auth-logo" alt="">
            <div class="login-back">
                <a href="<?php echo e(url('login')); ?>" class=" pl-5 btn-sm text-light"><i class="fas fa-arrow-left fs-2x"></i></a>
            </div>
                <div class="card-body">

                    <form method="POST" action="<?php echo e(route('forget-mail-send')); ?>">
                        <?php echo e(csrf_field()); ?>

                        <!-- <h1>
                            <div class="login-logo">
                                <a href="#">
                                    <?php echo e(trans('panel.site_title')); ?>

                                </a>
                            </div>
                        </h1> -->
                        <p class="text-muted"></p>
                        <div>
                            <div class="form-group has-email">
                                <input type="email" name="email" class="form-control" placeholder="<?php echo e(trans('global.login_email')); ?>">
                                <?php if($errors->has('email')): ?>
                                <div class="invalid-email error">
                                    <?php echo e($errors->first('email')); ?>

                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary btn-block btn-flat custom-btn">
                                    <?php echo e(trans('global.reset_password')); ?>

                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/laravel/bod/resources/views/auth/passwords/email.blade.php ENDPATH**/ ?>