<?php $__env->startSection('content'); ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('notifications_create')): ?>
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="<?php echo e(route("admin.notifications.create")); ?>">
                <?php echo e(trans('global.add')); ?> <?php echo e(trans('cruds.notifications.title_singular')); ?>

            </a>
        </div>
    </div>
<?php endif; ?>
<div class="card">
    <div class="card-header">
        <?php echo e(trans('cruds.notifications.title_singular')); ?> <?php echo e(trans('global.list')); ?>

    </div>

    <div class="card-body">
        <div class="table-responsive">
            <div class="row mb-4">
                <div class="col-md-2 ">
                    <button type="button" class="btn btn-danger" id="Bulkdelete">Delete All</button>
                </div>
            </div>
            <table id="notificationTbl" class=" table table-bordered table-striped table-hover datatable datatable-notification">
                <thead>
                    <tr>
                        <th width="10">
                            <input type="checkbox" id="select-all-checkbox">
                        </th>
                        
                        <th>
                            <?php echo e(trans('cruds.notifications.fields.title')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.notifications.fields.message')); ?>

                        </th>
                        <th>
                            Created at
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


<?php $__env->startPush('js'); ?>

<!-- jquery-validation -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo e(asset('js/additional-methods.min.js')); ?>"></script>

<?php echo $__env->make('admin.customJs.notifications.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/bod/resources/views/admin/notifications/index.blade.php ENDPATH**/ ?>