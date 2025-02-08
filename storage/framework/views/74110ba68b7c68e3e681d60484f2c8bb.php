<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <?php echo e(trans('global.edit')); ?> <?php echo e(trans('cruds.profile.title_singular')); ?>

    </div>

    <div class="card-body">
     
        <form method="POST"  action="<?php echo e(route('admin.users.profile.update')); ?>" id="userEditFormId"  enctype="multipart/form-data">
         <?php echo csrf_field(); ?>
         
            <input type="hidden" name="RoleId" value="<?php echo e($user->roles[0]->id); ?>">
            <input type="hidden" name="RoleName" id="RoleName" value="<?php echo e($user->roles[0]->title); ?>">
            <input type="hidden" name="user_id" value="<?php echo e($user->id); ?>">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group <?php echo e($errors->has('first_name') ? 'has-error' : ''); ?>">
                        <label for="first_name"><?php echo e(trans('cruds.user.fields.first_name')); ?>*</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo e(old('first_name', isset($user) ? $user->first_name : '')); ?>" placeholder="First Name" >
                        <?php if($errors->has('first_name')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('first_name')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.user.fields.first_name_helper')); ?>

                        </p>


                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group <?php echo e($errors->has('last_name') ? 'has-error' : ''); ?>">
                        <label for="last_name"><?php echo e(trans('cruds.user.fields.last_name')); ?></label>
                        <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo e(old('last_name', isset($user) ? $user->last_name : '')); ?>" placeholder="Last Name" >
                        <?php if($errors->has('last_name')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('last_name')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.user.fields.last_name_helper')); ?>

                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group <?php echo e($errors->has('email') ? 'has-error' : ''); ?>">
                        <label for="email"><?php echo e(trans('cruds.user.fields.email')); ?>*</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo e(old('email', isset($user) ? $user->email : '')); ?>" >
                        <?php if($errors->has('email')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('email')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.user.fields.email_helper')); ?>

                        </p>
                    </div>
                </div>
                <?php if(isset($user->roles[0]->title) && $user->roles[0]->title != 'Super Admin'): ?>

                <div class="col-md-6">
                    <div class="form-group <?php echo e($errors->has('location') ? 'has-error' : ''); ?>">
                        <label for="location">Select location*</label>

                        <input type="text" name="location" id="location" class="form-control" value="<?php echo e(old('location', isset($userDetail->$model) ? $userDetail->$model->location : '')); ?>" placeholder="Choose Location" >
                        <input type="hidden" id="latitude" name="latitude" value="<?php echo e(old('latitude', isset($userDetail->$model) ? $userDetail->$model->latitude : '')); ?>">
                        <input type="hidden" name="longitude" id="longitude" value="<?php echo e(old('longitude', isset($userDetail->$model) ? $userDetail->$model->longitude : '')); ?>">
                        <label class="location-error error"></label>
                        <?php if($errors->has('location')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('location')); ?>

                        </em>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endif; ?>

            <div class="col-md-6">
                <div class="form-group <?php echo e($errors->has('image') ? 'has-error' : ''); ?>">
                    <label for="image"><?php echo e(trans('cruds.user.fields.image')); ?></label>
                    <input type="file" id="image" name="image" class="form-control">
                    <br>
    
                    <?php if($user->image != null): ?>
                        <img src="\user_image\<?php echo e($user->image); ?>" height="100" width="100">
                    <?php else: ?>
                        <img src="\image\default-user.jpg" height="100" width="100">
                    <?php endif; ?>
    
                    <?php if($errors->has('image')): ?>
                    <em class="invalid-feedback">
                        <?php echo e($errors->first('image')); ?>

                    </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.user.fields.image_helper')); ?>

                    </p>
                </div>
            </div>


            </div>  
            <?php if(isset($user->roles[0]->title) && $user->roles[0]->title != 'Super Admin'): ?>
           
              
           
                 <?php echo $__env->make('admin.users.user_detail', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <?php endif; ?>
            <div>
                <input class="btn btn-danger" type="submit" value="<?php echo e(trans('global.save')); ?>">
            </div>
        </form>


    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
<!-- jquery-validation -->
<script type="text/javascript" src="<?php echo e(asset('js/jquery.validate.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/additional-methods.min.js')); ?>"></script>

<?php echo $__env->make('admin.customJs.users.edit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/bod/resources/views/admin/users/profileUpdate.blade.php ENDPATH**/ ?>