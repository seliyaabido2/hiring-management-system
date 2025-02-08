<?php $__env->startSection('content'); ?>

<div class="card">

    <div class="card-header">
       <center class="fw-bold">Candidate Details </center>
    </div>

    <div class="card-body candidate-details">
        <div class="mb-2">
            <h5 class="border_bottom">Basic Candidate Details</h5>
            <li>Candidate Id: <b><?php echo e($candidate->candidate_id); ?></b></li>
            <li>Name: <b><?php echo e($candidate->name); ?></b></li>
            <li>Email: <b><?php echo e($candidate->email); ?></b></li>
            <li>Contact Number: <b><?php echo e($candidate->contact_no); ?></b></li>
            <?php if($candidate->date_of_birth !== null): ?>
                <li>Date Of Birth: <b><?php echo e(getConvertedDate($candidate->date_of_birth)); ?></b></li>
            <?php else: ?>
            <li>Date Of Birth: - </li>
            <?php endif; ?>

            <?php if($candidate->gender !== null): ?>
            <li>Gender: <b><?php echo e($candidate->gender); ?></b></li>
            <?php else: ?>
            <li>Gender: - </li>
            <?php endif; ?>
            <li>Location: <b><?php echo e($candidate->location); ?></b></li>


            <br>
            <h5 class="border_bottom">Job Preference & Experience Details</h5>
            <li>Job Preference: <b><?php echo e($candidate->job_preference); ?></b></li>
            <li>Job Type: <b><?php echo e($candidate->job_type); ?></b></li>
            <li>Previous Insurance Experience with State Farm: <b><?php echo e($candidate->experience_sf); ?></b></li>
            <?php if($candidate->experience_sf == 'Yes'): ?>
            <li>How Many Experience: <b><?php echo e($candidate->how_many_experience); ?></b></li>
            <li>Presently Working in State Farm: <b><?php echo e($candidate->presently_working_in_sf); ?></b></li>
            <?php if($candidate->presently_working_in_sf == 'No'): ?>
            <li><?php echo e(trans('cruds.employerJobs.fields.last_month_year_in_sf')); ?>: <b>
                <?php echo e($candidate->last_month_year_in_sf !== null ? $candidate->last_month_year_in_sf : ''); ?>

            </b></li>

            <?php endif; ?>
            <?php else: ?> <?php endif; ?>
            <li>License Requirement: <b><?php echo e($candidate->license_requirement); ?></b></li>
            <li>Licensed Candidate with basic Training: <b><?php echo e($candidate->license_candidate_basic_training); ?></b></li>

            <li>License candidate without insurance Experience but have experience in Banking/Finance: <b><?php echo e($candidate->license_candidate_banking_finance); ?></b></li>

            <li>Any other language:
                <b><?php echo ($candidate->any_other_langauge === 'Other') ? $candidate->other_any_other_langauge : $candidate->any_other_langauge; ?></b>
            </li>

            <li>Expected pay per hour: <b><?php echo e($candidate->expected_pay_per_hour ? '$' . $candidate->expected_pay_per_hour : ''); ?></b></li>

            <li>Currrent pay per hour: <b><?php echo e($candidate->current_pay_per_hour ? '$' . $candidate->current_pay_per_hour : ''); ?></b></li>

            <?php if($candidate->additional_information != NULL): ?>
            <li>Additional Information: <b><?php echo e($candidate->additional_information); ?></b></li>
            <?php endif; ?>
            <?php if($candidate->reference_check != NULL): ?>
            <li>Reference Check: <b><?php echo e($candidate->reference_check); ?></b></li>
            <?php endif; ?>

            <li>CV: <b><a href="<?php echo e(asset('/candidate_resume').'/'.$candidate->resume); ?>" download>
                Download
            </a></b></li>
            <li>status: <b><?php echo e($candidate->status); ?></b></li>
            <li>Date Of Register: <b><?php echo e(getConvertedDate(date('d-m-Y', strtotime($candidate->created_at)))); ?></b></li>

            <a style="margin-top:20px;" class="btn btn-default" href="<?php echo e(url()->previous()); ?>">
                <?php echo e(trans('global.back_to_list')); ?>

            </a>
        </div>
        <nav class="mb-3">
            <div class="nav nav-tabs">

            </div>
        </nav>
        <div class="tab-content">

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/laravel/bod/resources/views/admin/candidates/show.blade.php ENDPATH**/ ?>