<?php $__env->startSection('content'); ?>

<?php $__env->startPush('css'); ?>


<?php $__env->stopPush(); ?>
<?php
$userid = auth()->user()->id;

$roleName = getUserRole($userid);
?>
<div class="card">
    <div class="card-header">
        <?php echo e(trans('global.create')); ?> <?php echo e(trans('cruds.employerJobs.title_singular')); ?>

    </div>

    <div class="card-body">
        <form action="<?php echo e(route('admin.employerJobs.store')); ?>" method="POST" id="employerJobForm" autocomplete="off" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <b>Basic Job Details</b>
            <input type="hidden" name="empolyer_job_id" value="<?php echo e(encrypt_data($employerjob->id)); ?>">

            <div class="pb-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group <?php echo e($errors->has('job_title') ? 'has-error' : ''); ?>">
                            <label for="job_title"><?php echo e(trans('cruds.employerJobs.fields.job_title')); ?>*</label>
                            <input type="text" id="job_title" name="job_title" class="form-control"
                                value="<?php echo e(old('job_title', isset($employerjob) ? $employerjob->job_title : '')); ?>"
                                placeholder="First Name">
                            <?php if($errors->has('job_title')): ?>
                                <em class="invalid-feedback">
                                    <?php echo e($errors->first('job_title')); ?>

                                </em>
                            <?php endif; ?>
                            <p class="helper-block">
                                <?php echo e(trans('cruds.employerJobs.fields.job_title_helper')); ?>

                            </p>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="job_type"><?php echo e(trans('cruds.employerJobs.fields.job_type')); ?>*</label>
                        <select class="form-control select2bs4" id="job_type" name="job_type">
                            <option value="">Select Type</option>
                            <option value="Full Time" <?php if($employerjob->job_type == 'Full Time'): ?> selected <?php endif; ?>>Full Time
                            </option>
                            <option value="Part Time" <?php if($employerjob->job_type == 'Part Time'): ?> selected <?php endif; ?>>Part Time
                            </option>
                        </select>
                        <?php if($errors->has('job_type')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('job_type')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.job_type_helper')); ?>

                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="job_role"><?php echo e(trans('cruds.employerJobs.fields.job_role')); ?>*</label>
                        <select class="form-control select2bs4" id="job_role" name="job_role">
                            <option value="">Select <?php echo e(trans('cruds.employerJobs.fields.job_role')); ?></option>
                            <option value="Service Focused" <?php if($employerjob->job_role == 'Service Focused'): ?> selected <?php endif; ?>>Service
                                Focused</option>
                            <option value="Sales Focused" <?php if($employerjob->job_role == 'Sales Focused'): ?> selected <?php endif; ?>>Sales
                                Focused</option>
                            <option value="Both" <?php if($employerjob->job_role == 'Both'): ?> selected <?php endif; ?>>Both</option>
                        </select>
                        <?php if($errors->has('job_role')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('job_role')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.job_role_helper')); ?>

                        </p>
                    </div>
                    <div class="col-md-6">
                            <div class="form-group <?php echo e($errors->has('location') ? 'has-error' : ''); ?>">
                                <label for="location">Select location*</label>

                                <input type="text" name="location" id="location" class="form-control" value="<?php echo e(old('location', isset($employerjob) ? $employerjob->location : '')); ?>" placeholder="Choose Location" >
                                <input type="hidden" id="latitude" name="latitude" value="<?php echo e(old('latitude', isset($employerjob) ? $employerjob->latitude : '')); ?>">
                                <input type="hidden" name="longitude" id="longitude" value="<?php echo e(old('longitude', isset($employerjob) ? $employerjob->longitude : '')); ?>">
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
                    <div class="form-group col-md-6">
                        <label
                            for="number_of_vacancies"><?php echo e(trans('cruds.employerJobs.fields.number_of_vacancies')); ?>*</label>
                        <select class="form-control select2bs4" id="number_of_vacancies" name="number_of_vacancies">
                            <option value="">Select <?php echo e(trans('cruds.employerJobs.fields.number_of_vacancies')); ?>

                            </option>
                            <option value="1" <?php echo e($employerjob->number_of_vacancies == 1 ? 'selected' : ''); ?>>1
                            </option>
                            <option value="2" <?php echo e($employerjob->number_of_vacancies == 2 ? 'selected' : ''); ?>>2
                            </option>
                            <option value="3" <?php echo e($employerjob->number_of_vacancies == 3 ? 'selected' : ''); ?>>3
                            </option>
                            <option value="4" <?php echo e($employerjob->number_of_vacancies == 4 ? 'selected' : ''); ?>>4
                            </option>
                        </select>

                        <?php if($errors->has('number_of_vacancies')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('number_of_vacancies')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.number_of_vacancies_helper')); ?>

                        </p>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="job_shift"><?php echo e(trans('cruds.employerJobs.fields.job_shift')); ?>*</label>
                        <select class="form-control select2bs4" id="job_shift" name="job_shift">
                            <option value="">Select Job Work</option>
                            <option value="Work from office" <?php if($employerjob->job_shift === 'Work from office'): ?> selected <?php endif; ?>>On-site
                            </option>
                            <option value="Work from home" <?php if($employerjob->job_shift === 'Work from home'): ?> selected <?php endif; ?>>Work from
                                home</option>
                        </select>
                        <?php if($errors->has('job_shift')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('job_shift')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.job_shift_helper')); ?>

                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group <?php echo e($errors->has('job_description') ? 'has-error' : ''); ?>">
                            <label
                                for="job_description"><?php echo e(trans('cruds.employerJobs.fields.job_description')); ?>*</label>
                            <textarea placeholder="Job description" class="form-control" id="job_description" name="job_description" rows="3"><?php echo e(old('job_description', isset($employerjob) ? $employerjob->job_description : '')); ?></textarea>


                            <?php if($errors->has('job_description')): ?>
                                <em class="invalid-feedback">
                                    <?php echo e($errors->first('job_description')); ?>

                                </em>
                            <?php endif; ?>
                            <p class="helper-block">
                                <?php echo e(trans('cruds.employerJobs.fields.job_description_helper')); ?>

                            </p>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label
                            for="total_number_of_working_days"><?php echo e(trans('cruds.employerJobs.fields.total_number_of_working_days')); ?>*</label>
                        <select class="form-control select2bs4" id="total_number_of_working_days"
                            name="total_number_of_working_days">
                            <option value="">Select
                                <?php echo e(trans('cruds.employerJobs.fields.total_number_of_working_days')); ?></option>
                            <option value="1"
                                <?php echo e($employerjob->total_number_of_working_days == 1 ? 'selected' : ''); ?>>1 Day</option>
                            <option value="2"
                                <?php echo e($employerjob->total_number_of_working_days == 2 ? 'selected' : ''); ?>>2 Days</option>
                            <option value="3"
                                <?php echo e($employerjob->total_number_of_working_days == 3 ? 'selected' : ''); ?>>3 Days</option>
                            <option value="4"
                                <?php echo e($employerjob->total_number_of_working_days == 4 ? 'selected' : ''); ?>>4 Days</option>
                            <option value="5"
                                <?php echo e($employerjob->total_number_of_working_days == 5 ? 'selected' : ''); ?>>5 Days</option>
                            <option value="6"
                                <?php echo e($employerjob->total_number_of_working_days == 6 ? 'selected' : ''); ?>>6 Days</option>
                            <option value="7"
                                <?php echo e($employerjob->total_number_of_working_days == 7 ? 'selected' : ''); ?>>7 Days</option>

                        </select>
                        <?php if($errors->has('total_number_of_working_days')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('total_number_of_working_days')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.total_number_of_working_days_helper')); ?>

                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group <?php echo e($errors->has('job_benefits') ? 'has-error' : ''); ?>">
                            <label for="job_benefits"><?php echo e(trans('cruds.employerJobs.fields.job_benefits')); ?></label>
                            <textarea placeholder="Job benefits" class="form-control" id="job_benefits" name="job_benefits" rows="3"><?php echo e(old('job_benefits', isset($employerjob) ? $employerjob->job_benefits : '')); ?>

                            </textarea>


                            <?php if($errors->has('job_benefits')): ?>
                                <em class="invalid-feedback">
                                    <?php echo e($errors->first('job_benefits')); ?>

                                </em>
                            <?php endif; ?>
                            <p class="helper-block">
                                <?php echo e(trans('cruds.employerJobs.fields.job_benefits_helper')); ?>

                            </p>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label
                            for="any_other_langauge"><?php echo e(trans('cruds.employerJobs.fields.any_other_langauge')); ?>*</label>
                        <select class="form-control select2bs4" id="any_other_langauge" name="any_other_langauge">
                            <option value="">Select langauge</option>
                            <option value="spanish"
                                <?php echo e($employerjob->any_other_langauge === 'spanish' ? 'selected' : ''); ?>>Spanish</option>
                            <option value="korean"
                                <?php echo e($employerjob->any_other_langauge === 'korean' ? 'selected' : ''); ?>>Korean</option>
                            <option value="chinese"
                                <?php echo e($employerjob->any_other_langauge === 'chinese' ? 'selected' : ''); ?>>Chinese</option>
                            <option value="Other"
                                <?php echo e($employerjob->any_other_langauge === 'Other' ? 'selected' : ''); ?>>Other</option>

                        </select>
                        <?php if($errors->has('any_other_langauge')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('any_other_langauge')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.any_other_langauge_helper')); ?>

                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6 other_any_other_langauge" style="display: <?php echo $employerjob->any_other_langauge === 'Other' ? 'block' : 'none'; ?>">
                        <label
                            for="other_any_other_langauge"><?php echo e(trans('cruds.employerJobs.fields.other_any_other_langauge')); ?>*</label>
                        <input type="text" id="other_any_other_langauge" name="other_any_other_langauge"
                            class="form-control"
                            value="<?php echo e(old('other_any_other_langauge', isset($employerjob) ? $employerjob->other_any_other_langauge : '')); ?>"
                            placeholder="<?php echo e(trans('cruds.employerJobs.fields.other_any_other_langauge')); ?>">

                        <?php if($errors->has('other_any_other_langauge')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('other_any_other_langauge')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.other_any_other_langauge_helper')); ?>

                        </p>
                    </div>
                    <div class="form-group col-md-6  <?php echo e($errors->has('job_start_date') ? 'has-error' : ''); ?>">
                        <label for="job_start_date"><?php echo e(trans('cruds.employerJobs.fields.job_start_date')); ?>*</label>
                        <div class='input-group date' id='job_start_date'>
                            <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                            </span>
                            <input type='text' placeholder="job Launch date" class="form-control"
                                name="job_start_date" />
                            <?php if($errors->has('job_start_date')): ?>
                                <em class="invalid-feedback">
                                    <?php echo e($errors->first('job_start_date')); ?>

                                </em>
                            <?php endif; ?>
                            <p class="helper-block">
                                <?php echo e(trans('cruds.employerJobs.fields.job_start_date_helper')); ?>

                            </p>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label
                            for="job_recruiment_duration"><?php echo e(trans('cruds.employerJobs.fields.job_recruiment_duration')); ?>*</label>
                        <input type="number" min="1" id="job_recruiment_duration"
                            name="job_recruiment_duration" class="form-control"
                            value="<?php echo e(old('job_recruiment_duration', isset($employerjob) ? $employerjob->job_recruiment_duration : '')); ?>"
                            placeholder="<?php echo e(trans('cruds.employerJobs.fields.job_recruiment_duration')); ?> (In Days)">

                        <?php if($errors->has('job_recruiment_duration')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('job_recruiment_duration')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.job_recruiment_duration_helper')); ?>

                        </p>
                    </div>
                </div>
            </div>
            <b>Experience Requirement Details</b>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="experience_sf"><?php echo e(trans('cruds.employerJobs.fields.experience_sf')); ?>*</label>
                    <select class="form-control select2bs4" id="experience_sf" name="experience_sf">
                        <option value="">Select Experience</option>
                        <option value="Yes" <?php if($employerjob->experience_sf === 'Yes'): ?> selected <?php endif; ?>>Yes</option>
                        <option value="No" <?php if($employerjob->experience_sf === 'No'): ?> selected <?php endif; ?>>No</option>
                    </select>

                    <?php if($errors->has('experience_sf')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('experience_sf')); ?>

                        </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.employerJobs.fields.experience_sf_helper')); ?>

                    </p>
                </div>

                <div class="form-group col-md-6 license_requirement" style="display: <?php echo $employerjob->experience_sf === 'Yes' ? 'block' : 'none'; ?>">
                    <label
                        for="license_requirement"><?php echo e(trans('cruds.employerJobs.fields.license_requirement')); ?>*</label>
                    <select class="form-control select2bs4" id="license_requirement" name="license_requirement">
                        <option value="">Select License</option>


                            <option value="Property & Casualty (P&C)" <?php echo e($employerjob->license_requirement === 'Property & Casualty (P&C)' ? 'selected' : ''); ?>>Property & Casualty (P&C)</option>
                            <option value="Life and Health (L&H)" <?php echo e($employerjob->license_requirement === 'Life and Health (L&H)' ? 'selected' : ''); ?>>Life and Health (L&H)</option>
                            <option value="Both" <?php echo e($employerjob->license_requirement === 'Both' ? 'selected' : ''); ?>>Both</option>
                            <option value="No License Required" <?php echo e($employerjob->license_requirement === 'No License Required' ? 'selected' : ''); ?>>No License Required</option>

                    </select>

                    <?php if($errors->has('license_requirement')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('license_requirement')); ?>

                        </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.employerJobs.fields.license_requirement_helper')); ?>

                    </p>
                </div>

                <div class="form-group col-md-6 how_many_years_of_experience" style="display: <?php echo $employerjob->experience_sf === 'Yes' ? 'block' : 'none'; ?>">
                    <label
                        for="how_many_years_of_experience"><?php echo e(trans('cruds.employerJobs.fields.how_many_years_of_experience')); ?></label>
                    <select class="form-control select2bs4" id="how_many_years_of_experience"
                        name="how_many_years_of_experience">
                        <option value="">Select Experience</option>
                        <option value="0-2 years" <?php if($employerjob->how_many_years_of_experience === '0-2 years'): ?> selected <?php endif; ?>>0-2 years
                        </option>
                        <option value="2-5 years" <?php if($employerjob->how_many_years_of_experience === '2-5 years'): ?> selected <?php endif; ?>>2-5 years
                        </option>
                        <option value="more than 5 years" <?php if($employerjob->how_many_years_of_experience === 'more than 5 years'): ?> selected <?php endif; ?>>More than
                            5 years</option>
                    </select>


                    <?php if($errors->has('how_many_years_of_experience')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('how_many_years_of_experience')); ?>

                        </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.employerJobs.fields.how_many_years_of_experience_helper')); ?>

                    </p>
                </div>
                <div class="form-group col-md-6">
                    <label
                        for="license_candidate_basic_training"><?php echo e(trans('cruds.employerJobs.fields.license_candidate_basic_training')); ?></label>
                    <select class="form-control select2bs4" id="license_candidate_basic_training"
                        name="license_candidate_basic_training">
                        <option value="">Select</option>
                        <option value="Yes" <?php if($employerjob->license_candidate_basic_training === 'Yes'): ?> selected <?php endif; ?>>Yes</option>
                        <option value="No" <?php if($employerjob->license_candidate_basic_training === 'No'): ?> selected <?php endif; ?>>No</option>
                    </select>

                    <?php if($errors->has('license_candidate_basic_training')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('license_candidate_basic_training')); ?>

                        </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.employerJobs.fields.license_candidate_basic_training_helper')); ?>

                    </p>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label
                        for="license_candidate_banking_finance"><?php echo e(trans('cruds.employerJobs.fields.license_candidate_banking_finance')); ?></label>
                    <select class="form-control select2bs4" id="license_candidate_banking_finance"
                        name="license_candidate_banking_finance">
                        <option value="">Select</option>
                        <option value="Yes" <?php if($employerjob->license_candidate_banking_finance === 'Yes'): ?> selected <?php endif; ?>>Yes</option>
                        <option value="No" <?php if($employerjob->license_candidate_banking_finance === 'No'): ?> selected <?php endif; ?>>No</option>
                    </select>

                    <?php if($errors->has('license_candidate_banking_finance')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('license_candidate_banking_finance')); ?>

                        </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.employerJobs.fields.license_candidate_banking_finance_helper')); ?>

                    </p>
                </div>



            </div>

            <div class="row">
                <div class="form-group col-md-6">

                    <label for="minimum_pay_per_hour"><?php echo e(trans('cruds.employerJobs.fields.minimum_pay_per_hour')); ?>*</label>
                    <div class="input-group">
                   <span class="input-group-text" id="basic-addon1">$</span>
                    <input type="number" id="minimum_pay_per_hour" name="minimum_pay_per_hour" class="form-control"
                        value="<?php echo e(old('minimum_pay_per_hour', isset($employerjob) ? $employerjob->minimum_pay_per_hour : '')); ?>"
                        min="1" placeholder="<?php echo e(trans('cruds.employerJobs.fields.minimum_pay_per_hour')); ?>">
                    </div>
                    <?php if($errors->has('minimum_pay_per_hour')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('minimum_pay_per_hour')); ?>

                        </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.employerJobs.fields.minimum_pay_per_hour_helper')); ?>

                    </p>
                </div>
                <div class="form-group col-md-6">

                    <label for="maximum_pay_per_hour"><?php echo e(trans('cruds.employerJobs.fields.maximum_pay_per_hour')); ?>*</label>
                    <div class="input-group">
                   <span class="input-group-text" id="basic-addon1">$</span>
                    <input type="number" id="maximum_pay_per_hour" name="maximum_pay_per_hour" class="form-control"
                        value="<?php echo e(old('maximum_pay_per_hour', isset($employerjob) ? $employerjob->maximum_pay_per_hour : '')); ?>"
                        min="1" placeholder="<?php echo e(trans('cruds.employerJobs.fields.maximum_pay_per_hour')); ?>">
                    </div>
                    <?php if($errors->has('maximum_pay_per_hour')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('maximum_pay_per_hour')); ?>

                        </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.employerJobs.fields.maximum_pay_per_hour_helper')); ?>

                    </p>
                </div>
                <div class="col-md-6">
                    <div class="form-group <?php echo e($errors->has('additional_information') ? 'has-error' : ''); ?>">
                        <label
                            for="additional_information"><?php echo e(trans('cruds.employerJobs.fields.additional_information')); ?></label>
                        <textarea placeholder="<?php echo e(trans('cruds.employerJobs.fields.additional_information')); ?>" class="form-control"
                            id="additional_information" name="additional_information" rows="3"><?php echo e(old('additional_information', isset($employerjob) ? $employerjob->additional_information : '')); ?></textarea>


                        <?php if($errors->has('additional_information')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('additional_information')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.additional_information_helper')); ?>

                        </p>
                    </div>
                </div>

            </div>
            <br>
            <div>
                <button class="btn btn-info" type="submit"><?php echo e(trans('global.save')); ?></button>
                
            </div>
        </form>


    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>

<!-- jquery-validation -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo e(asset('js/additional-methods.min.js')); ?>"></script>

<?php echo $__env->make('admin.customJs.employerJobs.create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/laravel/bod/resources/views/admin/employerJobs/createExitJob.blade.php ENDPATH**/ ?>