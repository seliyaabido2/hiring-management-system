<?php $__env->startSection('content'); ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user_create')): ?>
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="<?php echo e(route("admin.RecruiterPartner.create")); ?>">
            <?php echo e(trans('global.add')); ?> Recruiter <?php echo e(trans('cruds.user.title_singular')); ?>

        </a>
    </div>
</div>
<?php endif; ?>
<div class="card">
    <div class="card-header">
        Recruiter <?php echo e(trans('cruds.user.title_singular')); ?> <?php echo e(trans('global.list')); ?>

    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="recruiterTbl" class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th>
                            <?php echo e(trans('cruds.employer.fields.client_name')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.employer.fields.authorized_person_name')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.user.fields.company_name')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.employer.fields.email')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.employer.fields.phone_number')); ?>

                        </th>
                        <th>
                            Location
                        </th>

                        <th>
                            <?php echo e(trans('cruds.employer.fields.status')); ?>

                        </th>
                        <th>
                            Actions
                        </th>
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
<?php echo $__env->make('admin.customJs.recruiter.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/laravel/bod/resources/views/admin/recruiter/index.blade.php ENDPATH**/ ?>