<?php $__env->startSection('content'); ?>
<?php
    $routename = request()->route()->getName();

?>
<!-- <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('job_create')): ?>
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <?php if($routename == 'admin.employerJobs.myjob'): ?>
            <a class="btn btn-success" href="<?php echo e(route("admin.employerJobs.create")); ?>">
                <?php echo e(trans('global.add')); ?> <?php echo e(trans('cruds.myJobs.title_singular')); ?>

            </a>
            <?php else: ?>
            <a class="btn btn-success" href="<?php echo e(route("admin.employerJobs.create")); ?>">
                <?php echo e(trans('global.add')); ?> <?php echo e(trans('cruds.employerJobs.title_singular')); ?>

            </a>
            <?php endif; ?>

        </div>
    </div>
<?php endif; ?> -->
<div class="card">
    <div class="card-header">
       Active  <?php echo e(trans('cruds.employerJobs.title_singular')); ?> <?php echo e(trans('global.list')); ?>


       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('job_applied_create')): ?>
       <a class="btn btn-success btn-sm ml-2" href="<?php echo e(route('admin.employerJobs.bulkApplyJob')); ?>">Bulk Apply job</a>
       <?php endif; ?>

       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bod_job_applied_create')): ?>
       <a class="btn btn-success btn-sm ml-2" href="<?php echo e(route('admin.employerJobs.bulkApplyJob')); ?>">Bulk Apply job</a>
       <?php endif; ?>

    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>

                        <th>
                            <?php echo e(trans('cruds.employerJobs.fields.id')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.employerJobs.fields.job_title')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.employerJobs.fields.job_recruiment_duration')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.employerJobs.fields.job_start_date')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.employerJobs.fields.job_expire_date')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.employerJobs.fields.job_type')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.employerJobs.fields.location')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.employerJobs.fields.status')); ?>

                        </th>
                        <th>
                            <?php echo e(trans('cruds.employerJobs.fields.addedBy')); ?>

                        </th>
                        <th>
                        Action&nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>

                     <?php $__currentLoopData = $EmployerJob; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr data-entry-id="<?php echo e($job->id); ?>">

                            <td>
                             <?php echo e(($key + 1) ?? ''); ?>

                            </td>
                            <td>
                                <?php echo e($job->job_title ?? ''); ?>

                            </td>
                            <td>
                                <?php echo e($job->job_recruiment_duration ?? ''); ?> Days
                            </td>
                            <td>
                                <?php echo e(date("d-m-Y", strtotime($job->job_start_date)) ?? ''); ?>

                            </td>
                            <?php
                            $enddate=calculateEndDate($job->job_start_date,$job->job_recruiment_duration);

                            ?>
                            <td>
                                <?php echo e(date("d-m-Y", strtotime($enddate)) ?? ''); ?>

                            </td>
                            <td>
                                <?php echo e($job->job_type  ?? ''); ?>

                            </td>
                            <td>
                                <?php echo e($job->location  ?? ''); ?>

                            </td>

                            <td>
                                <?php if($job->status == 'Active'): ?>
                                    <button class="btn btn-xs btn-success disabled">Active</button>
                                <?php elseif($job->status == 'Deactive'): ?>
                                    <button class="btn btn-xs btn-danger disabled"><?php echo e(trans('global.deactive')); ?></button>
                                <?php elseif($job->status == 'Hold'): ?>
                                    <button class="btn btn-xs btn-warning disabled">Hold</button>
                                <?php endif; ?>

                            </td>
                            <td>
                                <?php

                                $addedby = getUserName($job->user_id);

                                ?>
                               <?php echo e(isset($addedby) ? $addedby->first_name.' '.$addedby->last_name : ""); ?>


                            </td>
                            <td>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('job_applied_create')): ?>
                                <a class="btn btn-xs btn-success mt-11" href="<?php echo e(route('admin.employerJobs.applyJob', encrypt_data($job->id))); ?>">
                                    <?php echo e(trans('global.applyjob')); ?>

                                </a>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bod_job_applied_create')): ?>
                                    <a class="btn btn-xs btn-success mt-11" href="<?php echo e(route('admin.employerJobs.applyJob', encrypt_data($job->id))); ?>">
                                        <?php echo e(trans('global.applyjob')); ?>

                                    </a>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('job_show')): ?>
                                    <a class="btn btn-xs btn-primary mt-11" href="<?php echo e(route('admin.employerJobs.show', encrypt_data($job->id))); ?>">
                                        <?php echo e(trans('global.view')); ?>

                                    </a>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('job_edit')): ?>
                                    <a class="btn btn-xs btn-info mt-11" href="<?php echo e(route('admin.employerJobs.edit',encrypt_data($job->id))); ?>">
                                        <?php echo e(trans('global.edit')); ?>

                                    </a>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bod_saved_template_access')): ?>
                                <?php if(!empty($job->savedJobTemplate)): ?>
                                <a class="btn btn-xs btn-warning mt-11" href="<?php echo e(route('admin.appliedJobs.unSavedJobTemplate', ['id' =>encrypt_data($job->id) ])); ?>">
                                    un-save Job template
                                </a>
                                <?php else: ?>
                                <a class="btn btn-xs btn-success mt-11" href="<?php echo e(route('admin.appliedJobs.savedJobTemplate', ['id' =>encrypt_data($job->id) ])); ?>">
                                    Save Job template
                                </a>
                                <?php endif; ?>
                                <?php endif; ?>


                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('saved_template_access')): ?>
                                <?php if(!empty($job->savedJobTemplate)): ?>
                                <a class="btn btn-xs btn-warning mt-11" href="<?php echo e(route('admin.appliedJobs.unSavedJobTemplate', ['id' =>encrypt_data($job->id) ])); ?>">
                                    un-save Job template
                                </a>
                                <?php else: ?>
                                <a class="btn btn-xs btn-success mt-11" href="<?php echo e(route('admin.appliedJobs.savedJobTemplate', ['id' =>encrypt_data($job->id) ])); ?>">
                                    Save Job template
                                </a>
                                <?php endif; ?>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('job_delete')): ?>
                                <button class="btn btn-xs btn-danger delete-btn mt-11" data-id="<?php echo e(encrypt_data($job->id)); ?>">
                                        <?php echo e(trans('global.delete')); ?>

                                </button>
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
        //     url: "<?php echo e(route('admin.users.massDestroy')); ?>",
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
    order: [[ 0, 'asc' ]],
    pageLength: 10,
  });

//   $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
//     $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
//         $($.fn.dataTable.tables(true)).DataTable()
//             .columns.adjust();
//     });
})

</script>

<!-- delete Data script -->
<script>
    $(document).ready(function() {
        $('.delete-btn').click(function() {
            var itemId = $(this).data('id');
            $('#delete-model').modal('show');
            $('.delete-form').attr('action', "<?php echo e(url('admin/employerJobs/destroy')); ?>?id=" + itemId);
        });

        $('.cancel-btn').click(function() {
            $(this).closest('.modal').modal('hide');
        });
    });
</script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/bod/resources/views/admin/employerJobs/activeJobs.blade.php ENDPATH**/ ?>