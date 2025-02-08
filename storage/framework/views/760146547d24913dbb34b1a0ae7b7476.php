<?php $__env->startSection('content'); ?>
<?php $__env->startSection('styles'); ?>
<?php $__env->stopSection(); ?>


<div class="card">
    <div class="card-header">
        <?php echo e(trans('global.edit')); ?> <?php echo e(trans('cruds.candidates.title_singular')); ?>

    </div>

    <div class="card-body">
        <?php if(count($errors) > 0): ?>
            <div class = "alert alert-danger auto-hide">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="ml-md-5 pl-md-5 mt-3 mt-md-0 navbar-search" method="post" enctype="multipart/form-data" action="<?php echo e(route("admin.resumeFetchData.upload")); ?>">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" id="candidates_id" name="candidates_id" value="<?php echo e($candidate->id); ?>">

            <div class="col-md-6">
                <div class="form-group <?php echo e($errors->has('resume') ? 'has-error' : ''); ?>">
                    <label for="resume"><?php echo e(trans('cruds.employerJobs.fields.resume')); ?>*</label>
                    <div class="input-group">
                        <input type="file" id="file" name="file" class="form-control">
                    </div>
                    <br>
                    <?php if($errors->has('resume')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('resume')); ?>

                        </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.employerJobs.fields.resume_helper')); ?>

                    </p>

                </div>
            </div>

            <button type="submit" class="btn btn-primary upload-btn">Upload</button>
        </form>


        <form action="<?php echo e(route('admin.candidate.update', [$candidate->id])); ?>" method="POST" id="EditCandidateForm"
            autocomplete="off" enctype="multipart/form-data">

            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <?php
            if (!empty($candidate_resume)) {
                $candidate_resume = (object)$candidate_resume;
            }
            ?>

            <?php if(isset($candidate_resume->resume_path)): ?>
                <input type="hidden" id="resume_path" name="resume_path" value="<?php echo e(isset($candidate_resume->resume_path) ? $candidate_resume->resume_path : ""); ?>">
            <?php else: ?>
                <input type="hidden" id="resume_path" name="resume_path" value="<?php echo e(isset($candidate->resume) ? $candidate->resume : ""); ?>">
            <?php endif; ?>


            <b>Basic Details</b>
            <input type="hidden" id="candidates_hidden_id" name="id" value="<?php echo e($candidate->id); ?>">
            <div class="row">

                <div class="col-md-12">

                    <div class="form-group <?php echo e($errors->has('candidate_name') ? 'has-error' : ''); ?>">
                        <label for="candidate_name"><?php echo e(trans('cruds.employerJobs.fields.candidate_name')); ?>*</label>
                        
                        <?php if(isset($candidate_resume->candidate_name)): ?>
                            <input type="text" id="candidate_name" name="candidate_name" class="form-control"
                            value="<?php echo e(old('name', isset($candidate_resume->candidate_name) ? $candidate_resume->candidate_name : '')); ?>"
                            placeholder="Full Name">
                        <?php else: ?>
                            <input type="text" id="candidate_name" name="candidate_name" class="form-control"
                            value="<?php echo e(old('name', isset($candidate) ? $candidate->name : '')); ?>"
                            placeholder="Full Name">
                        <?php endif; ?>

                        <?php if($errors->has('candidate_name')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('candidate_name')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.candidate_name_helper')); ?>

                        </p>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group <?php echo e($errors->has('email') ? 'has-error' : ''); ?>">
                        <label for="email"><?php echo e(trans('cruds.employerJobs.fields.email')); ?>*</label>
                        
                        <?php if(isset($candidate_resume->email)): ?>
                            <input type="text" id="email" name="email" class="form-control unique"
                            value="<?php echo e(old('email', isset($candidate_resume->email) ? $candidate_resume->email : '')); ?>"
                            placeholder="<?php echo e(trans('cruds.employerJobs.fields.email')); ?>">
                        <?php else: ?>
                            <input type="text" id="email" name="email" class="form-control unique"
                            value="<?php echo e(old('email', isset($candidate) ? $candidate->email : '')); ?>"
                            placeholder="<?php echo e(trans('cruds.employerJobs.fields.email')); ?>">
                        <?php endif; ?>

                        <?php if($errors->has('email')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('email')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.email_helper')); ?>

                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group <?php echo e($errors->has('contact_number') ? 'has-error' : ''); ?>">
                        <label for="contact_number"><?php echo e(trans('cruds.employerJobs.fields.contact_number')); ?>*</label>
                        
                        <?php if(isset($candidate_resume->contact_number)): ?>
                            <input type="text" id="contact_number" name="contact_number" class="form-control"
                            value="<?php echo e(old('contact_no', isset($candidate_resume->contact_number) ? $candidate_resume->contact_number : '')); ?>"
                            placeholder="<?php echo e(trans('cruds.employerJobs.fields.contact_number')); ?>">
                        <?php else: ?>
                            <input type="text" id="contact_number" name="contact_number" class="form-control"
                            value="<?php echo e(old('contact_no', isset($candidate) ? $candidate->contact_no : '')); ?>"
                            placeholder="<?php echo e(trans('cruds.employerJobs.fields.contact_number')); ?>">
                        <?php endif; ?>

                        <?php if($errors->has('contact_number')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('contact_number')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.contact_number_helper')); ?>

                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6  <?php echo e($errors->has('date_of_birth') ? 'has-error' : ''); ?>">
                    <label for="date_of_birth"><?php echo e(trans('cruds.employerJobs.fields.date_of_birth')); ?></label>
                    <div class='input-group date' id='date_of_birth'>

                        <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                        </span>
                        <input type='text' placeholder="Date of birth" value="<?php echo e(date("d-m-Y", strtotime($candidate->date_of_birth))); ?>"
                            class="form-control" id="date_of_birth"  name="date_of_birth" />
                        <?php if($errors->has('date_of_birth')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('date_of_birth')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.date_of_birth_helper')); ?>

                        </p>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="gender"><?php echo e(trans('cruds.employerJobs.fields.gender')); ?></label>
                    <select class="form-control select2bs4" id="gender" name="gender">
                        <option value="">Select gender</option>
                        <option value="Male" <?php echo e($candidate->gender == 'Male' ? 'selected' : ''); ?>>Male</option>
                        <option value="Female" <?php echo e($candidate->gender == 'Female' ? 'selected' : ''); ?>>Female</option>
                        <option value="Other" <?php echo e($candidate->gender == 'Other' ? 'selected' : ''); ?>>Other</option>
                    </select>
                    <?php if($errors->has('gender')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('gender')); ?>

                        </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.employerJobs.fields.gender_helper')); ?>

                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group <?php echo e($errors->has('location') ? 'has-error' : ''); ?>">
                        <label for="location">Select location*</label>

                        <input type="text" name="location" id="location" class="form-control" value="<?php echo e(old('location', isset($candidate) ? $candidate->location : '')); ?>" placeholder="Choose Location" >
                        <input type="hidden" id="latitude" name="latitude" value="<?php echo e(old('latitude', isset($candidate) ? $candidate->latitude : '')); ?>">
                        <input type="hidden" name="longitude" id="longitude" value="<?php echo e(old('longitude', isset($candidate) ? $candidate->longitude : '')); ?>">
                        <label class="location-error error"></label>
                        <?php if($errors->has('location')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('location')); ?>

                        </em>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <br>

            <b>Job Preference & Experience Details</b>
            <div class="row">

                <div class="form-group col-md-6">
                    <label for="job_preference"><?php echo e(trans('cruds.employerJobs.fields.job_preference')); ?></label>
                    <select class="form-control select2bs4" id="job_preference" name="job_preference">
                        <option value="">Select</option>

                        <option value="Service Focused" <?php echo e($candidate->job_preference == 'Service Focused' ? 'selected' : ''); ?>>Service Focused</option>
                        <option value="Sales Focused" <?php echo e($candidate->job_preference == 'Sales Focused' ? 'selected' : ''); ?>>Sales Focused</option>
                        <option value="Both" <?php echo e($candidate->job_preference == 'Both' ? 'selected' : ''); ?>>Both</option>

                    </select>
                    <?php if($errors->has('job_preference')): ?>
                    <em class="invalid-feedback">
                        <?php echo e($errors->first('job_preference')); ?>

                    </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.employerJobs.fields.job_preference_helper')); ?>

                    </p>
                </div>

                <div class="col-md-6">

                    <div class="form-group <?php echo e($errors->has('job_type') ? 'has-error' : ''); ?>">
                        <label for="job_type"><?php echo e(trans('cruds.employerJobs.fields.job_type')); ?></label>

                        <select class="form-control select2bs4" id="job_type" name="job_type">
                            <option value="">Select Job Type</option>
                            <option value="Full Time" <?php echo e($candidate->job_type == 'Full Time' ? 'selected' : ''); ?>>Full Time</option>
                            <option value="Part Time" <?php echo e($candidate->job_type == 'Part Time' ? 'selected' : ''); ?>>Part Time</option>
                        </select>
                        <br>
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

            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="experience_sf"><?php echo e(trans('cruds.employerJobs.fields.experience_sf')); ?>*</label>
                    <select class="form-control select2bs4" id="experience_sf" name="experience_sf">
                        <option value="">Select</option>
                        <option value="Yes" <?php echo e($candidate->experience_sf == 'Yes' ? 'selected' : ''); ?>>Yes</option>
                        <option value="No" <?php echo e($candidate->experience_sf == 'No' ? 'selected' : ''); ?>>No</option>
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
                <div class="form-group col-md-6">
                    <label
                        for="license_requirement"><?php echo e(trans('cruds.employerJobs.fields.license_requirement')); ?>*</label>
                        <select class="form-control select2bs4" id="license_requirement" name="license_requirement">
                            <option value="Property & Casualty (P&C)" <?php echo e($candidate->license_requirement === 'Property & Casualty (P&C)' ? 'selected' : ''); ?>>Property & Casualty (P&C)</option>
                            <option value="Life and Health (L&H)" <?php echo e($candidate->license_requirement === 'Life and Health (L&H)' ? 'selected' : ''); ?>>Life and Health (L&H)</option>
                            <option value="Both" <?php echo e($candidate->license_requirement === 'Both' ? 'selected' : ''); ?>>Both</option>
                            <option value="No License Required" <?php echo e($candidate->license_requirement === 'No License Required' ? 'selected' : ''); ?>>No License Required</option>

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
            </div>
            <div class="row">
                <div class="form-group col-md-6 how_many_experience" style="display: <?php echo $candidate->experience_sf === 'Yes' ? 'block' : 'none'; ?>">
                    <label
                        for="how_many_experiences"><?php echo e(trans('cruds.employerJobs.fields.how_many_experience')); ?></label>
                    <select class="form-control select2bs4" id="how_many_experience" name="how_many_experience">
                        <option value="">Select Experience</option>
                        <option value="0-2 years" <?php if($candidate->how_many_experience === '0-2 years'): ?> selected <?php endif; ?>>0-2 years</option>
                        <option value="2-5 years" <?php if($candidate->how_many_experience === '2-5 years'): ?> selected <?php endif; ?>>2-5 years</option>
                        <option value="more than 5 years" <?php if($candidate->how_many_experience === 'more than 5 years'): ?> selected <?php endif; ?>>More than
                            5 years</option>
                    </select>
                    <?php if($errors->has('how_many_experience')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('how_many_experience')); ?>

                        </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.employerJobs.fields.how_many_experience_helper')); ?>

                    </p>
                </div>
                <div class="form-group col-md-6 presently_working_in_sf" style="display: <?php echo $candidate->experience_sf === 'Yes' ? 'block' : 'none'; ?>">
                    <label
                        for="presently_working_in_sf"><?php echo e(trans('cruds.employerJobs.fields.presently_working_in_sf')); ?></label>
                    <select class="form-control select2bs4" id="presently_working_in_sf"
                        name="presently_working_in_sf">
                        <option value="">Select </option>
                        <option value="Yes" <?php if($candidate->presently_working_in_sf === 'Yes'): ?> selected <?php endif; ?>>Yes</option>
                        <option value="No" <?php if($candidate->presently_working_in_sf === 'No'): ?> selected <?php endif; ?>>No</option>
                    </select>
                    <?php if($errors->has('presently_working_in_sf')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('presently_working_in_sf')); ?>

                        </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.employerJobs.fields.presently_working_in_sf_helper')); ?>

                    </p>
                </div>
            </div>
            <div class="row">

                <div class="form-group col-md-6 last_month_year_in_sf" style="display: <?php echo ($candidate->experience_sf === 'Yes' && $candidate->presently_working_in_sf === 'No') ? 'block' : 'none'; ?>">
                    <label
                        for="last_month_year_in_sf"><?php echo e(trans('cruds.employerJobs.fields.last_month_year_in_sf')); ?></label>
                    <input type="text" class="form-control" name="last_month_year_in_sf" id="last_month_year_in_sf" value="<?php echo e($candidate->last_month_year_in_sf); ?>" >
                    <?php if($errors->has('last_month_year_in_sf')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('last_month_year_in_sf')); ?>

                        </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.employerJobs.fields.last_month_year_in_sf_helper')); ?>

                    </p>
                </div>


            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label
                        for="license_candidate_basic_training"><?php echo e(trans('cruds.employerJobs.fields.license_candidate_basic_training')); ?></label>
                    <select class="form-control select2bs4" id="license_candidate_basic_training"
                        name="license_candidate_basic_training">
                        <option value="Yes"
                            <?php echo e($candidate->license_candidate_basic_training == 'Yes' ? 'selected' : ''); ?>>Yes</option>
                        <option value="No"
                            <?php echo e($candidate->license_candidate_basic_training == 'No' ? 'selected' : ''); ?>>No</option>
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
                <div class="form-group col-md-6">
                    <label
                        for="license_candidate_banking_finance"><?php echo e(trans('cruds.employerJobs.fields.license_candidate_banking_finance')); ?></label>
                    <select class="form-control select2bs4" id="license_candidate_banking_finance"
                        name="license_candidate_banking_finance">
                        <option value="">Select</option>

                        <option value="Yes"
                            <?php echo e($candidate->license_candidate_banking_finance == 'Male' ? 'Yes' : ''); ?>>Yes</option>
                        <option value="No"
                            <?php echo e($candidate->license_candidate_banking_finance == 'Male' ? 'No' : ''); ?>>No</option>
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
                    <label
                        for="any_other_langauge"><?php echo e(trans('cruds.employerJobs.fields.any_other_langauge')); ?>*</label>
                    <select class="form-control select2bs4" id="any_other_langauge" name="any_other_langauge">
                        <option value="">Select langauge</option>
                        <option value="spanish" <?php echo e($candidate->any_other_langauge == 'spanish' ? 'selected' : ''); ?>>
                            spanish</option>
                        <option value="korean" <?php echo e($candidate->any_other_langauge == 'korean' ? 'selected' : ''); ?>>
                            korean</option>
                        <option value="chinese" <?php echo e($candidate->any_other_langauge == 'chinese' ? 'selected' : ''); ?>>
                            chinese</option>
                        <option value="Other" <?php echo e($candidate->any_other_langauge == 'Other' ? 'selected' : ''); ?>>Other
                        </option>

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

                <div class="form-group col-md-6 other_any_other_langauge" style="display: <?php echo $candidate->any_other_langauge === 'Other' ? 'block' : 'none'; ?>">

                    <label
                        for="other_any_other_langauges"><?php echo e(trans('cruds.employerJobs.fields.other_any_other_langauge')); ?>*</label>
                    <input type="text" id="other_any_other_langauge" name="other_any_other_langauge"
                        class="form-control"
                        value="<?php echo e(old('other_any_other_langauge', isset($candidate) ? $candidate->other_any_other_langauge : '')); ?>"
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

            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group <?php echo e($errors->has('expected_pay_per_hour') ? 'has-error' : ''); ?>">
                        <label
                            for="expected_pay_per_hour"><?php echo e(trans('cruds.employerJobs.fields.expected_pay_per_hour')); ?></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">$</span>
                            <input type="number" id="expected_pay_per_hour" name="expected_pay_per_hour"
                                value="<?php echo e($candidate->expected_pay_per_hour); ?>"
                                placeholder="Enter Expected pay per hour" class="form-control">
                        </div>
                        <br>
                        <?php if($errors->has('expected_pay_per_hour')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('expected_pay_per_hour')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.expected_pay_per_hour_helper')); ?>

                        </p>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group <?php echo e($errors->has('current_pay_per_hour') ? 'has-error' : ''); ?>">
                        <label for="current_pay_per_hour"><?php echo e(trans('cruds.employerJobs.fields.current_pay_per_hour')); ?></label>
                        <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">$</span>

                            <input type="number" id="current_pay_per_hour" name="current_pay_per_hour"
                                value="<?php echo e($candidate->current_pay_per_hour); ?>"
                                placeholder="Enter Expected pay per hour" class="form-control">
                        </div>
                        <br>
                        <?php if($errors->has('current_pay_per_hour')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('current_pay_per_hour')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.current_pay_per_hour_helper')); ?>

                        </p>

                    </div>
                </div>

            </div>
            <div class="row">
                <div class="form-group col-md-6 other_license_requirement" style="<?php $candidate->license_requirement == 'Other' ? 'display:block' : 'display:none'; ?> ">
                    <label
                        for="other_license_requirements"><?php echo e(trans('cruds.employerJobs.fields.other_license_requirement')); ?>*</label>
                    <input type="text" id="other_license_requirement" name="other_license_requirement"
                        class="form-control" value="<?php echo e($candidate->other_license_requirement); ?>"
                        placeholder="<?php echo e(trans('cruds.employerJobs.fields.other_license_requirement')); ?>">

                    <?php if($errors->has('other_license_requirement')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('other_license_requirement')); ?>

                        </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.employerJobs.fields.other_license_requirement_helper')); ?>

                    </p>
                </div>
                

                <div class="col-md-6">
                    <div class="form-group <?php echo e($errors->has('reference_check') ? 'has-error' : ''); ?>">
                        <label
                            for="reference_check"><?php echo e(trans('cruds.employerJobs.fields.reference_check')); ?></label>
                            <select class="form-control select2bs4" id="reference_check"
                            name="reference_check">
                            <option value="">Select</option>
                            <option value="Yes"
                            <?php echo e($candidate->reference_check == 'Yes' ? 'selected' : ''); ?>>Yes</option>
                        <option value="No"
                            <?php echo e($candidate->reference_check == 'No' ? 'selected' : ''); ?>>No</option>
                            </select>


                        <?php if($errors->has('reference_check')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('reference_check')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.reference_check_helper')); ?>

                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group <?php echo e($errors->has('additional_information') ? 'has-error' : ''); ?>">
                        <label
                            for="additional_information"><?php echo e(trans('cruds.employerJobs.fields.additional_information')); ?></label>
                        <textarea placeholder="<?php echo e(trans('cruds.employerJobs.fields.additional_information')); ?>" class="form-control"
                            id="additional_information" name="additional_information" rows="3"><?php echo e($candidate->additional_information); ?></textarea>


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

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status"><?php echo e(trans('cruds.employerJobs.fields.status')); ?>*</label>
                        <select class="form-control select2bs4" id="status" name="status">
                            <option value="Active" <?php echo e($candidate->status == 'Active' ? 'selected' : ''); ?>>Active
                            </option>
                            <option value="Deactive" <?php echo e($candidate->status == 'Deactive' ? 'selected' : ''); ?>>
                                <?php echo e(trans('global.deactive')); ?></option>
                        </select>
                        <?php if($errors->has('status')): ?>
                            <em class="invalid-feedback">
                                <?php echo e($errors->first('status')); ?>

                            </em>
                        <?php endif; ?>
                        <p class="helper-block">
                            <?php echo e(trans('cruds.employerJobs.fields.status_helper')); ?>

                        </p>
                    </div>
                </div>
            </div>
            <div>
                <button class="btn btn-info" type="submit"><?php echo e(trans('global.save')); ?></button>
            </div>
        </form>


    </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('js'); ?>
<!-- jquery-validation -->

<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo e(asset('js/additional-methods.min.js')); ?>"></script>

<?php echo $__env->make('admin.customJs.candidates.edit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/laravel/bod/resources/views/admin/candidates/edit.blade.php ENDPATH**/ ?>