<?php $__env->startSection('content'); ?>
<?php
    $user_id = auth()->user()->id;
    $roleName =  getUserRole($user_id);
?>
<div class="card">
    <div class="card-header">
        <?php echo e(trans('cruds.recruiterReports.title_singular')); ?> <?php echo e(trans('global.list')); ?>

    </div>


    <div class="card-body    filter-div">
        <form id ="report-filter">
             <?php echo csrf_field(); ?>
            <div class="form-row">


                <div class="form-group col-md-4  <?php echo e($errors->has('from_date') ? 'has-error' : ''); ?>">
                    <label for="from_date"><?php echo e(trans('cruds.recruiterReports.fields.from_date')); ?>*</label>
                <div class='input-group date' id='from_date'>

                            <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                            </span>
                        <input type='text'placeholder="From date" class="form-control job-data" name="from_date" />
                    <?php if($errors->has('from_date')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('from_date')); ?>

                        </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.recruiterReports.fields.from_date_helper')); ?>

                    </p>
                    </div>
                </div>

                <div class="form-group col-md-4  <?php echo e($errors->has('to_date') ? 'has-error' : ''); ?>">
                    <label for="to_date"><?php echo e(trans('cruds.recruiterReports.fields.to_date')); ?>*</label>
                <div class='input-group date' id='to_date'>

                            <span class="input-group-addon input-group-text"><span class="fa fa-calendar"></span>
                            </span>
                        <input type='text'placeholder="To date" class="form-control job-data" name="to_date" />
                    <?php if($errors->has('to_date')): ?>
                        <em class="invalid-feedback">
                            <?php echo e($errors->first('to_date')); ?>

                        </em>
                    <?php endif; ?>
                    <p class="helper-block">
                        <?php echo e(trans('cruds.recruiterReports.fields.to_date_helper')); ?>

                    </p>
                    </div>
                </div>

                <?php if(in_array($roleName, ['Super Admin', 'Admin'])): ?>
                <div class="form-group col-md-4">
                    <label for="job_id">Recruiter</label>
                    <select class="form-control  select2 job-data" id="recruiter_id" name="recruiter_id"  >
                        <option value="">All</option>
                        <?php $__currentLoopData = $recruiterUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recruiter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($recruiter->id); ?>"><?php echo e($recruiter->first_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <?php endif; ?>
                 <div class="form-group col-md-4">
                    <label for="job_id">Candidates</label>
                    <select class="form-control  select2 job-data" id="candidate_id" name="candidate_id"  >
                        <option value="">All</option>
                        <?php $__currentLoopData = $candidates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $candidate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($candidate->candidate_id); ?>"><?php echo e($candidate->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label for="job_id">Job Title</label>
                    <select class="form-control  select2 " id="job_id" name="job_id"  >
                        <option value="">All</option>
                        <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($job->id); ?>"><?php echo e($job->job_title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="job_round">Job Round Name</label>
                    <select class="form-control select2" id="job_round_name" name="job_round_name">
                        <option value="">All</option>
                        <option value="None">None</option>
                        <option value="Shortlist">Shortlist</option>
                        <option value="Assessment">Assessment</option>
                        <option value="Phone Interview">Phone Interview</option>
                        <option value="In person Interview">In person Interview</option>
                        <option value="Background Check">Background Check</option>
                        <option value="Assessment">Assessment</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="job_round_status">Job Round Status</label>
                    <select class="form-control select2bs4" id="job_round_status" name="job_round_status">
                        <option value="">All</option>
                        <option value="None">None</option>
                        <option value="Selected">Selected</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Stand By">Stand By</option>
                        <option value="No Response">No Response</option>
                        <option value="Skip">Skip</option>
                    </select>
                </div>

                <div class="form-group col-md-4 mt-4">

                    <button type="submit" class="btn btn-primary">Generate Report </button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Role datatable-custom">
                <thead>
                    <tr>

                        <th>
                            S.No.
                        </th>

                        <th>
                            From Date
                        </th>
                        <th>
                            To Date
                        </th>
                        <?php if(in_array($roleName, ['Super Admin', 'Admin'])): ?>
                        <th>
                            Recruiter name
                        </th>
                        <?php endif; ?>
                        <th>
                            Candidate name
                        </th>

                        <th>
                            Job Title
                        </th>
                        <th>
                            Job Status
                        </th>
                        <th>
                            Job Round Name
                        </th>
                        <th>
                            Job Round Status
                        </th>
                        <th>
                            Created Date
                        </th>
                        <th>
                            Downloads&nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>

                    <?php $__currentLoopData = $getAllData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <tr>
                        <td><?php echo e($key + 1); ?></td>

                        <td><?php echo e($data->from_date); ?></td>
                        <td><?php echo e($data->to_date); ?></td>
                        <?php if(in_array($roleName, ['Super Admin', 'Admin'])): ?>
                        <td><?php echo e($data->recruiter_name); ?></td>
                        <?php endif; ?>
                        <td><?php echo e($data->candidate_name); ?></td>

                        <td><?php echo e($data->job_title); ?></td>
                        <td><?php echo e($data->job_status); ?></td>
                        <td><?php echo e($data->round_name); ?></td>
                        <td><?php echo e($data->round_status); ?></td>
                        <td><?php echo e($data->created_at_formatted); ?></td>
                        <td><a href="<?php echo e(url('/').$data->link); ?>" download>
                            <i class="fa fa-download" aria-hidden="true"></i>
                         </a></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                </tbody>
            </table>
        </div>


    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
<!-- jquery-validation -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo e(asset('js/additional-methods.min.js')); ?>"></script>

<?php echo $__env->make('admin.customJs.recruiterReports.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/laravel/bod/resources/views/admin/recruiterReports/index.blade.php ENDPATH**/ ?>