<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header">
       <b><?php echo e($employerJobs->job_title); ?> </b>
    </div>
    <div class="card-body requirements">
        <p>Requirement of a <?php echo e($employerJobs->job_title); ?> with a experience of a <?php echo e($employerJobs->job_requirement_experience); ?>.</p>
        <p><?php echo e($employerJobs->location); ?> |  Posted:<?php echo e(getConvertedDate($employerJobs->created_at)); ?> | Requirement: <?php echo e($employerJobs->number_of_vacancies); ?></p>
    </div>
    <div class="card-header">
       <center class="fw-bold"> Job Details </center>
    </div>

    <div class="card-body">
        <div class="mb-2 job-details">
            <h5 class="border_bottom">Basic Job Details</h5>
           <li><b>Job Type: </b> <?php echo e($employerJobs->job_type); ?></li>
           <li><b>Job Role: </b> <?php echo e($employerJobs->job_role); ?></li>
             <li><b>Job Address: </b> <?php echo e($employerJobs->location); ?></li>
           <li> <b>Job Description: </b> <?php echo e($employerJobs->job_description); ?></li>
            
            <li><b>Job Benefits: </b> <?php echo e($employerJobs->job_benefits); ?></li>
          
            <li><b>Number of Vacancies: </b> <?php echo e($employerJobs->number_of_vacancies); ?></li>

            <li><b>Job Work From Office/Home:</b> <?php echo e($employerJobs->job_shift); ?></li>

            <li><b>Total Number of Working Days: </b> <?php echo e($employerJobs->total_number_of_working_days); ?> Days</li>

            <li><b>Any other language:</b>
                <?php echo ($employerJobs->any_other_langauge === 'Other') ? $employerJobs->other_any_other_langauge : $employerJobs->any_other_langauge; ?>
            </li>

            <li><b>Job Launch Date: </b><?php echo e($employerJobs->job_start_date); ?></li>
            <li><b>Job Requirement Duration: </b><?php echo e($employerJobs->job_recruiment_duration); ?> Days</li>

            <br>
          
            <h5 class="border_bottom">Experience Requirement Details</h5>
            <li>Previous Insurance Experience with State Farm: <b><?php echo e($employerJobs->experience_sf); ?></b></li>

            <?php if($employerJobs->experience_sf == 'Yes'): ?>
                <li>License requirement: <b><?php echo e($employerJobs->license_requirement); ?></b></li>
                <li>How Many Years of Experience: <b><?php echo e($employerJobs->how_many_years_of_experience); ?></b></li>
            <?php endif; ?>

            <li>Licensed Candidate with completed Basic Training: <b><?php echo e($employerJobs->license_candidate_basic_training); ?></b></li>
            <li>License candidate without insurance Experience but have experience in Banking/Finance: <b><?php echo e($employerJobs->license_candidate_banking_finance); ?></b></li>
            <li>Minimum Pay Per Hour: <b>$<?php echo e($employerJobs->minimum_pay_per_hour); ?></b></li>
            <li>Maximum Pay Per Hour: <b>$<?php echo e($employerJobs->maximum_pay_per_hour); ?></b></li>
            
            <br>
            <b class="border_bottom">Additional Information:</b>
            <li><?php echo e($employerJobs->additional_information); ?></li>
            <br>


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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/laravel/bod/resources/views/admin/employerJobs/show.blade.php ENDPATH**/ ?>