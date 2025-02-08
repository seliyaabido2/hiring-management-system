<?php $__env->startSection('content'); ?>
<?php $__env->startSection('styles'); ?>

<?php $__env->stopSection(); ?>

<style>
.loader-main {
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    z-index: 9999999;
    background-color: #000;
    opacity: 0.5;
    width: 100%;
    height: 100%;
}

.loader {
    position: absolute;
    z-index: 9;
    top: 50%;
    transform: translate(50%, 50%);
    left: 50%;
    color: red;
    bottom: 50%;
}

</style>

<div class="card">
    <div class="card-header">
        BOD Bulk Candidate

    </div>

    <div class="card-body col-md-6">
        <form action="<?php echo e(route('admin.bodCandidate.storeBODBulkCandidate')); ?>" id="add-bodbulk-form" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <div class="form-group <?php echo e($errors->has('bodbulkcan') ? 'has-error' : ''); ?>">
                <label for="bodbulkcan">File upload*</label>
                <div class="input-group">
                    <input type="file" id="bodbulkcan" name="bodbulkcan" class="form-control">
                </div>
                <?php if($errors->has('bodbulkcan')): ?>
                    <span class="help-block"><?php echo e($errors->first('bodbulkcan')); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <a href="<?php echo e(url('sample/BOD_Bulk_can.xlsx')); ?>" download="BOD_Bulk_can_sample.xlsx">
                    Download Sample format
                </a>
            </div>

            <div>
                <button class="btn btn-danger" type="submit" value=""><?php echo e(trans('global.save')); ?></button>
            </div>
        </form>
    </div>

</div>
<div class="card">
<div class="card-body">
    <div class="table-responsive">
        <table class=" table table-bordered table-striped table-hover datatable datatable-Role datatable-User">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $SheetStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $SheetStatusone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr data-entry-id="<?php echo e($SheetStatusone->id); ?>">
                        <td><?php echo e($key+1); ?></td>
                        <td><?php echo e($SheetStatusone->sheet_name ?? ''); ?></td>
                        <td><?php echo e($SheetStatusone->status ?? ''); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
</div>



<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<!-- <script src="https://cdn.ckeditor.com/ckeditor5/38.0.1/classic/ckeditor.js"></script> -->


<!-- jquery-validation -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo e(asset('js/additional-methods.min.js')); ?>"></script>
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>


<?php echo $__env->make('admin.customJs.bodCandidates.bodBulkCandidates', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/laravel/bod/resources/views/admin/bodCandidates/bodBulkCandidate.blade.php ENDPATH**/ ?>