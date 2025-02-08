<?php $__env->startSection('content'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bod_candidate_create')): ?>
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="<?php echo e(route('admin.bodCandidate.create')); ?>">
                    <?php echo e(trans('global.add')); ?> <?php echo e(trans('cruds.bodCandidates.title_singular')); ?>

                </a>
                <a class="btn btn-success" href="<?php echo e(route('admin.bodCandidate.bod_bulk_candidate')); ?>">
                    BOD Bulk Candidate
                </a>
            </div>
        </div>
    <?php endif; ?>
    <div class="card">
        <div class="card-header">
            <?php echo e(trans('cruds.bodCandidates.title_singular')); ?> <?php echo e(trans('global.list')); ?>

        </div>

        <div class="card-body">
            <div  class="table-responsive">
                <table id="bodCandidateTbl" class="table table-bordered table-striped table-hover datatable-apply-selected"
                    style="width: 100%">
                    <thead>
                        <tr>
                            <th width="10">
                                <input type="checkbox" id="select-all-checkbox">
                            </th>
                            <th>
                                <?php echo e(trans('cruds.bodCandidates.fields.name')); ?>

                            </th>
                            <th>
                                <?php echo e(trans('cruds.bodCandidates.fields.email')); ?>

                            </th>
                            <th>
                                <?php echo e(trans('cruds.bodCandidates.fields.resume')); ?>

                            </th>
                            <th>
                                <?php echo e(trans('cruds.bodCandidates.fields.status')); ?>

                            </th>
                            <th>
                                <?php echo e(trans('cruds.bodCandidates.fields.last_updated_date')); ?>

                            </th>
                            <th>
                                Action&nbsp;
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
    <?php echo $__env->make('admin.customJs.bodCandidates.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/bod/resources/views/admin/bodCandidates/index.blade.php ENDPATH**/ ?>