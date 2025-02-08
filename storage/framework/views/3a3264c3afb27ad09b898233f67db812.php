<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">

            <?php echo e(trans('cruds.appliedJobs.fields.recent_top_20_record')); ?> <?php echo e(trans('global.list')); ?>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="recentUupdatesStatusTbl" class=" table table-bordered table-striped table-hover datatable">
                    <thead>
                        <tr>

                            <th>
                                Company Id
                                
                            </th>
                            <th>
                                <?php echo e(trans('cruds.employerJobs.fields.job_title')); ?>

                            </th>
                            <th>
                                <?php echo e(trans('cruds.employerJobs.fields.candidate_name')); ?>

                            </th>
                            <th>
                                <?php echo e(trans('cruds.employerJobs.fields.job_round')); ?>

                            </th>
                            <th>
                                <?php echo e(trans('cruds.employerJobs.fields.job_status')); ?>

                            </th>
                            <th>
                                <?php echo e(trans('cruds.appliedJobs.fields.recent_notes')); ?>

                            </th>
                            <th>
                                <?php echo e(trans('cruds.appliedJobs.fields.resume')); ?>

                            </th>

                            <th>Actions&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>


                    </tbody>
                </table>
            </div>


        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('scripts'); ?>

    <?php echo $__env->make('admin.customJs.appliedJobs.recentStatusUpdates', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/laravel/bod/resources/views/admin/appliedJobs/recentStatusUpdates.blade.php ENDPATH**/ ?>