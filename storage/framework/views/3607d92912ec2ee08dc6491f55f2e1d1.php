<?php $__env->startSection('content'); ?>
<?php
    $routename = request()->route()->getName();

?>

<div class="card">
    <div class="card-header">

        <?php echo e(trans('cruds.appliedJobs.title_singular')); ?> <?php echo e(trans('global.list')); ?>

    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>

                        <th>
                            <?php echo e(trans('cruds.appliedJobs.fields.id')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.appliedJobs.fields.job_title')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.appliedJobs.fields.job_type')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.appliedJobs.fields.job_expiry_date')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.appliedJobs.fields.total_candidate_applied')); ?>

                        </th>

                        <th>
                            <?php echo e(trans('cruds.appliedJobs.fields.status')); ?>

                        </th>
                        <!-- <th>
                        Action&nbsp;
                        </th> -->
                    </tr>
                </thead>
                <tbody>

                     <?php $__currentLoopData = $appliedJobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr data-entry-id="<?php echo e(1); ?>">
                            <td>
                                <?php echo e(($key + 1) ?? ''); ?>

                            </td>
                            <td>
                                <?php echo e($job->job_title ?? ''); ?>

                            </td>
                            <td>
                                <?php echo e($job->job_type ?? ''); ?>

                            </td>

                            <td>
                             <?php echo e(date('d-m-Y', strtotime(GetJobExpiryDate($job->job_start_date,$job->job_recruiment_duration)))); ?>

                            </td>
                            <td>
                                 <a href="<?php echo e(route('admin.appliedJobs.show',$job->job_id)); ?>" target="_blank"><?php echo e($job->total_candidates ?? ''); ?></a>
                            </td>

                            <td>
                                <?php if($job->status == 'Active'): ?>
                                    <button class="btn btn-xs btn-success disabled">Active</button>
                                <?php elseif($job->status == 'Deactive'): ?>
                                    <button class="btn btn-xs btn-danger disabled"><?php echo e(trans('global.deactive')); ?></button>
                                <?php elseif($job->status == 'Closed'): ?>
                                    <button class="btn btn-xs btn-info disabled">Closed</button>
                                <?php elseif($job->status == 'Hold'): ?>
                                    <button class="btn btn-xs btn-warning disabled">Hold</button>
                                <?php endif; ?>

                            </td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>


    </div>
</div>

 <!-- Add a modal dialog for the confirmation -->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php echo \Illuminate\View\Factory::parentPlaceholder('scripts'); ?>
<script>
    $(function () {
     let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

    // <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user_delete')): ?>
    // let deleteButtonTrans = '<?php echo e(trans('global.datatables.delete')); ?>'
    // let deleteButton = {
    //     text: deleteButtonTrans,
    //     url: "<?php echo e(route('admin.appliedJobs.massDestroy')); ?>",
    //     className: 'btn-danger',
    //     action: function (e, dt, node, config) {
    //     var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
    //         return $(entry).data('entry-id')
    //     });

    //     if (ids.length === 0) {
    //         alert('<?php echo e(trans('global.datatables.zero_selected')); ?>')

    //         return
    //     }

    //     if (confirm('<?php echo e(trans('global.areYouSure')); ?>')) {
    //         $.ajax({
    //         headers: {'x-csrf-token': _token},
    //         method: 'POST',
    //         url: config.url,
    //         data: { ids: ids, _method: 'DELETE' }})
    //         .done(function () { location.reload() })
    //     }
    //     }
    // }
    // dtButtons.push(deleteButton)
    // <?php endif; ?>

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'asc' ]],
    pageLength: 10,
  });

//   $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
//     $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
//         $($.fn.dataTable.tables(true)).DataTable()
//             .columns.adjust();
//     });
// })

</script>

<!-- delete Data script -->
<script>
    $(document).ready(function() {
        $('.delete-btn').click(function() {
            var itemId = $(this).data('id');
            $('#delete-model').modal('show');
            $('.delete-form').attr('action', "<?php echo e(url('admin/appliedJobs/destroy')); ?>?id=" + itemId);
        });

        $('.cancel-btn').click(function() {
            $(this).closest('.modal').modal('hide');
        });
    });
</script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/laravel/bod/resources/views/admin/appliedJobs/index.blade.php ENDPATH**/ ?>