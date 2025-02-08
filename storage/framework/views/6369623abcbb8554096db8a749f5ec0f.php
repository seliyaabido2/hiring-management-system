<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header">
        <?php echo e(trans('global.create')); ?> <?php echo e(trans('cruds.user.title_singular')); ?>

    </div>

    <div class="card-body">
        <form action="<?php echo e(route('admin.employer.store')); ?>" method="POST" id="employerCreateFormId" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <div class="card-header">
                <?php echo e(trans('global.create')); ?> <?php echo e(trans('cruds.employer.title').' Details'); ?>

            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group <?php echo e($errors->has('first_name') ? 'has-error' : ''); ?>">

                            <label for="first_name">Client Name*</label>

                            <input type="text" id="first_name" name="first_name" class="form-control first_name" placeholder="First Name" >

                            <?php if($errors->has('first_name')): ?>
                                <em class="invalid-feedback">
                                    <?php echo e($errors->first('first_name')); ?>

                                </em>
                            <?php endif; ?>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo e($errors->has('authorized_name') ? 'has-error' : ''); ?>">

                            <label for="authorized_name">Authorized Name*</label>
                            <input type="text" id="authorized_name" name="authorized_name" class="form-control"  placeholder="Authorized Name" >

                            <?php if($errors->has('authorized_name')): ?>
                                <em class="invalid-feedback">
                                    <?php echo e($errors->first('authorized_name')); ?>

                                </em>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group <?php echo e($errors->has('phone_no') ? 'has-error' : ''); ?>">

                            <label for="phone_no">Phone No*</label>
                            <input type="text" id="phone_no" name="phone_no" class="form-control" placeholder="phone No" >

                            <?php if($errors->has('phone_no')): ?>
                                <em class="invalid-feedback">
                                    <?php echo e($errors->first('phone_no')); ?>

                                </em>
                            <?php endif; ?>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group <?php echo e($errors->has('email') ? 'has-error' : ''); ?>">

                            <label for="email">Email*</label>
                            <input type="text" id="email" name="email" class="form-control"  placeholder="Email"  >

                            <?php if($errors->has('email')): ?>
                                <em class="invalid-feedback">
                                    <?php echo e($errors->first('email')); ?>

                                </em>
                            <?php endif; ?>

                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>


                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group <?php echo e($errors->has('password') ? 'has-error' : ''); ?>">

                            <label for="password">Password*</label>
                            <input type="password" id="password" name="password" class="form-control"  placeholder="Password" autocomplete="new-password" >

                            <?php if($errors->has('password')): ?>
                                <em class="invalid-feedback">
                                    <?php echo e($errors->first('password')); ?>

                                </em>
                            <?php endif; ?>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group <?php echo e($errors->has('company_name') ? 'has-error' : ''); ?>">

                            <label for="company_name">Company Name*</label>
                            <input type="text" id="company_name" name="company_name" class="form-control"  placeholder="Company Name" >

                            <?php if($errors->has('company_name')): ?>
                                <em class="invalid-feedback">
                                    <?php echo e($errors->first('company_name')); ?>

                                </em>
                            <?php endif; ?>

                        </div>
                    </div>


                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group <?php echo e($errors->has('location') ? 'has-error' : ''); ?>">
                            <label for="location">Select location*</label>

                            <input type="text" name="location" id="location" class="form-control" placeholder="Choose Location">
                            <input type="hidden" id="latitude" name="latitude" value="">
                            <input type="hidden" name="longitude" id="longitude" value="">
                            <label class="location-error error"></label>
                            <?php if($errors->has('location')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('location')); ?>

                            </em>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-header">
                <?php echo e(trans('global.create').' Contract Details'); ?>

            </div>

            <div class="card-body contract_block">
                <div class="contract_section" id="contract_section_1">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group <?php echo e($errors->has('contract_type') ? 'has-error' : ''); ?>">

                                <label for="contract_type">Contract Type*</label>


                                <select class="form-control select2bs4 contract_type" id="contract_type_1" name="contract_type[1]" style="width: 100%;">
                                    <option value="">Select Contract Type</option>

                                    <?php $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contractType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($contractType->id); ?>">
                                        <?php echo e($contractType->name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="error-message"></div>


                                <?php if($errors->has('contract_type')): ?>
                                    <em class="invalid-feedback">
                                        <?php echo e($errors->first('contract_type')); ?>

                                    </em>
                                <?php endif; ?>

                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group  <?php echo e($errors->has('start_date') ? 'has-error' : ''); ?>">
                                <label for="start_date">Start Date*</label>
                                <div class='input-group date' id='start_date_1'>

                                    <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                                    </span>

                                    <input type='text' placeholder="Start Date" id="input_start_date_1" class="form-control input_start_date datepicker" name="start_date[1]"/>

                                    <?php if($errors->has('start_date')): ?>
                                    <em class="invalid-feedback">
                                        <?php echo e($errors->first('start_date')); ?>

                                    </em>
                                    <?php endif; ?>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">


                            <div class="form-group  <?php echo e($errors->has('end_date') ? 'has-error' : ''); ?>">
                                <label for="end_date">End Date (Optional)</label>
                                <div class='input-group date' id='end_date_1'>

                                    <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                                    </span>
                                    <input type='text' placeholder="End Date" id="input_end_date_1" class="form-control input_end_date datepicker" name="end_date[1]" />

                                    <?php if($errors->has('end_date')): ?>
                                    <em class="invalid-feedback">
                                        <?php echo e($errors->first('end_date')); ?>

                                    </em>
                                    <?php endif; ?>

                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group <?php echo e($errors->has('contract_upload') ? 'has-error' : ''); ?>">

                                <label for="contract_upload">Contract Upload*</label>

                                <input type="file" id="contract_upload_1" name="contract_upload[1]" class="form-control">

                                <?php if($errors->has('contract_upload')): ?>
                                    <em class="invalid-feedback">
                                        <?php echo e($errors->first('contract_upload')); ?>

                                    </em>
                                <?php endif; ?>

                            </div>
                        </div>

                    </div>

                    <div class="row" id="checklist_upload_row_1" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-group <?php echo e($errors->has('checklist_upload') ? 'has-error' : ''); ?>">

                                <label for="checklist_upload">Check List Upload*</label>

                                <input type="file" id="checklist_upload_1" name="checklist_upload[1]" class="form-control">

                                <?php if($errors->has('checklist_upload')): ?>
                                    <em class="invalid-feedback">
                                        <?php echo e($errors->first('checklist_upload')); ?>

                                    </em>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="checkbox" id="recurring_contracts_1" name="recurring_contracts[1]" value="0" class="checkbox">
                                <label for="recurring_contracts">Recurring Contracts</label>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="btn btn-sm btn-success add_contract">+</a>

                <a class="btn btn-sm btn-danger remove_contract">-</a>

            </div>

            <div class="mt-3">
                <input class="btn btn-success" type="submit" value="<?php echo e(trans('global.save')); ?>">
            </div>



        </form>


    </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('js'); ?>
<!-- jquery-validation -->
<script type="text/javascript" src="<?php echo e(asset('js/jquery.validate.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/additional-methods.min.js')); ?>"></script>

<?php echo $__env->make('admin.customJs.employer.create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/laravel/bod/resources/views/admin/employer/create.blade.php ENDPATH**/ ?>