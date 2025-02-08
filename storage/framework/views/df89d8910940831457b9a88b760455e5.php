<?php $__env->startSection('content'); ?>

<div class="card">



    <form action="<?php echo e(route("admin.employer.update", [$employerDetail->id])); ?>"  id="employerEditFormId" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <input type="hidden" id="userId" value="<?php echo e($employerDetail->id); ?>">

        <div class="card-header">
            <?php echo e(trans('global.edit')); ?> <?php echo e(trans('cruds.employer.title').' Details'); ?>

        </div>

        <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group <?php echo e($errors->has('first_name') ? 'has-error' : ''); ?>">

                            <label for="first_name">Client Name*</label>

                            <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo e(old('first_name', isset($employerDetail) ? $employerDetail->first_name : '')); ?>" placeholder="First Name" >

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
                            <input type="text" id="authorized_name" name="authorized_name" class="form-control" value="<?php echo e(old('authorized_name', isset($employerDetail) ? $employerDetail->EmployerDetail->authorized_name : '')); ?>" placeholder="Authorized Name" >

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
                            <input type="text" id="phone_no" name="phone_no" class="form-control" value="<?php echo e(old('phone_no', isset($employerDetail) ? $employerDetail->EmployerDetail->phone_no : '')); ?>" placeholder="phone No" >

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
                            <input type="text" id="email" name="email" class="form-control" value="<?php echo e(old('email', isset($employerDetail) ? $employerDetail->email : '')); ?>" placeholder="Email" >

                            <?php if($errors->has('email')): ?>
                                <em class="invalid-feedback">
                                    <?php echo e($errors->first('email')); ?>

                                </em>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group <?php echo e($errors->has('company_name') ? 'has-error' : ''); ?>">

                            <label for="company_name">Company Name*</label>
                            <input type="text" id="company_name" name="company_name" class="form-control" value="<?php echo e(old('company_name', isset($employerDetail) ? $employerDetail->EmployerDetail->company_name : '')); ?>" placeholder="Company Name" >

                            <?php if($errors->has('company_name')): ?>
                                <em class="invalid-feedback">
                                    <?php echo e($errors->first('company_name')); ?>

                                </em>
                            <?php endif; ?>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group <?php echo e($errors->has('location') ? 'has-error' : ''); ?>">
                            <label for="location">Select location*</label>

                            <input type="text" name="location" id="location" class="form-control" placeholder="Choose Location" value="<?php echo e(old('location', isset($employerDetail) ? $employerDetail->EmployerDetail->location : '')); ?>">
                            <input type="hidden" id="latitude" name="latitude" value="<?php echo e(old('latitude', isset($employerDetail) ? $employerDetail->EmployerDetail->latitude : '')); ?>">
                            <input type="hidden" name="longitude" id="longitude" value="<?php echo e(old('longitude', isset($employerDetail) ? $employerDetail->EmployerDetail->longitude : '')); ?>">
                            <label class="location-error error"></label>
                            <?php if($errors->has('location')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('location')); ?>

                            </em>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group <?php echo e($errors->has('employerStatus') ? 'has-error' : ''); ?>">

                            <label for="employerStatus">status*</label>

                            <select class="form-control select2bs4" id="employerStatus" name="employerStatus" style="width: 100%;">
                                <option value="">select status</option>

                                        <option value="Active" <?php if ($employerDetail->status == 'Active') {
                                            echo 'selected';
                                        } ?>>
                                        Active</option>
                                        <option value="Deactive" <?php if ($employerDetail->status == 'Deactive') {
                                            echo 'selected';
                                        } ?>>
                                        <?php echo e(trans('global.deactive')); ?></option>
                            </select>

                            <?php if($errors->has('employerStatus')): ?>
                                <em class="invalid-feedback">
                                    <?php echo e($errors->first('employerStatus')); ?>

                                </em>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
        </div>

        <div class="card-header">
            <?php echo e(trans('global.edit').' Contract Details'); ?>

        </div>
        <input type="hidden" id="sectionCount" value="<?php echo e(count($employerDetail->AssignedContractDetail)); ?>">
        <?php if(isset($employerDetail->AssignedContractDetail)): ?>



                <div class="card-body contract_block">
                    <?php $__currentLoopData = $employerDetail->AssignedContractDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $AssignedContractDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


                    <div class="contract_section" id="contract_section_<?php echo e($key+1); ?>">

                        <input type="hidden" name="assign_contract_id[<?php echo e($key+1); ?>]" id="assign_contract_id_<?php echo e($key+1); ?>" value="<?php echo e($AssignedContractDetail->id); ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group <?php echo e($errors->has('contract_type') ? 'has-error' : ''); ?>">

                                    <label for="contract_type">Contract Type*</label>


                                    <select class="form-control select2bs4 contract_type" id="contract_type_<?php echo e($key+1); ?>" name="contract_type[<?php echo e($key+1); ?>]" style="width: 100%;">
                                        <option value="">Select Contract Type</option>

                                        <?php $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contractType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($contractType->id); ?>"  <?php echo e($AssignedContractDetail->ContractDetail->id === $contractType->id ? 'selected' : ''); ?>>
                                            <?php echo e($contractType->name); ?>

                                        </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>


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
                                    <div class='input-group date' id='start_date_<?php echo e($key+1); ?>'>

                                        <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                                        </span>


                                        <input type='text' placeholder="Start Date" id="input_start_date_<?php echo e($key+1); ?>" class="form-control datepicker input_start_date" name="start_date[<?php echo e($key+1); ?>]" value="<?php echo e(old('start_date', isset($AssignedContractDetail) ? date('d-m-Y', strtotime($AssignedContractDetail->start_date)) : '')); ?>"/>

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
                                    <div class='input-group date' id='end_date_<?php echo e($key+1); ?>'>

                                        <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                                        </span>
                                        <input type='text' placeholder="End Date" id="input_end_date_<?php echo e($key+1); ?>" value="<?php echo e(old('end_date', isset($AssignedContractDetail->end_date) ? date('d-m-Y', strtotime($AssignedContractDetail->end_date)) : '')); ?>" class="form-control datepicker input_end_date" name="end_date[<?php echo e($key+1); ?>]" />
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

                                    <input type="file" id="contract_upload_<?php echo e($key+1); ?>" name="contract_upload[<?php echo e($key+1); ?>]" class="form-control">

                                    <?php if($errors->has('contract_upload')): ?>
                                        <em class="invalid-feedback">
                                            <?php echo e($errors->first('contract_upload')); ?>

                                        </em>
                                    <?php endif; ?>

                                </div>
                            </div>

                        </div>

                        <div class="row" id="checklist_upload_row_<?php echo e($key+1); ?>" style="display: <?php echo e($AssignedContractDetail->ContractDetail->id == 1 ? 'block' : 'none'); ?>;">

                            <div class="col-md-6">
                                <div class="form-group <?php echo e($errors->has('checklist_upload') ? 'has-error' : ''); ?>">

                                    <label for="checklist_upload">Check List Upload*</label>

                                    <input type="file" id="checklist_upload_<?php echo e($key+1); ?>" name="checklist_upload[<?php echo e($key+1); ?>]" class="form-control">

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
                                    <input type="checkbox" id="recurring_contracts_<?php echo e($key+1); ?>" name="recurring_contracts[<?php echo e($key+1); ?>]" value="<?php echo e($key+1); ?>" class="checkbox" <?php echo e($AssignedContractDetail->recurring_contracts == '1' ? 'checked' : ''); ?>>
                                    <label for="recurring_contracts">Recurring Contracts</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(count($employerDetail->AssignedContractDetail) == ($key+1)): ?>
                    <a class="btn btn-sm btn-success add_contract">+</a>
                    <a class="btn btn-sm btn-danger remove_contract">-</a>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>



        <?php endif; ?>


        <div class="mt-3">
            <button class="btn btn-success" type="submit"><?php echo e(trans('global.save')); ?></button>
        </div>

    </form>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
<!-- jquery-validation -->
<script type="text/javascript" src="<?php echo e(asset('js/jquery.validate.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/additional-methods.min.js')); ?>"></script>

<?php echo $__env->make('admin.customJs.employer.edit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/laravel/bod/resources/views/admin/employer/edit.blade.php ENDPATH**/ ?>