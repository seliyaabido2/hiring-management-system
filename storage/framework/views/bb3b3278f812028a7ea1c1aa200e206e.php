<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header">
        <?php echo e(trans('global.view')); ?> <?php echo e(trans('cruds.contracts.title')); ?>

    </div>

    <div class="card-body">
        <div class="mb-2">

                <?php if(isset($contract->AssignedContractDetail)): ?>
                    <?php $__currentLoopData = $contract->AssignedContractDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $AssignedContractDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        Contract Type
                                    </th>
                                    <td>
                                        <?php echo e($AssignedContractDetail->ContractDetail->name); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Start Date
                                    </th>
                                    <td>
                                        <?php echo e($AssignedContractDetail->start_date); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        End Date
                                    </th>
                                    <td>
                                        <?php echo e($AssignedContractDetail->end_date ?: '-'); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Status
                                    </th>
                                    <td>
                                        <?php echo e($AssignedContractDetail->status); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        contract upload
                                    </th>
                                    <td>
                                        <a href="<?php echo e(asset('contract_upload').'/'.$AssignedContractDetail->contract_upload); ?>" download>
                                            Download
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Checklist upload
                                    </th>
                                    <td>
                                        <?php if($AssignedContractDetail->checklist_upload != null): ?>
                                        <a href="<?php echo e(asset('checklist_upload').'/'.$AssignedContractDetail->checklist_upload); ?>" download>
                                            Download
                                        </a>
                                        <?php else: ?>
                                        -
                                        <?php endif; ?>
                                    </td>
                                </tr>


                            </tbody>
                        </table>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php else: ?>
                    <table class="table table-bordered table-striped">
    
                        <tbody>
                            <tr>
                                <th>
                                    <?php echo e(trans('cruds.contracts.fields.name')); ?>

                                </th>
                                <td>
                                    <?php echo e($contract->AssignedOneContractDetail->ContractDetail->name); ?>

                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php echo e(trans('cruds.contracts.fields.description')); ?>

                                </th>
                                <td>
                                    <?php echo e($contract->AssignedOneContractDetail->ContractDetail->description ?: '-'); ?>

        
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php echo e(trans('cruds.contracts.fields.expire_alert')); ?>

                                </th>
                                <td>
                                    <?php echo e($contract->AssignedOneContractDetail->ContractDetail->expire_alert); ?> Days
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php echo e(trans('cruds.contracts.fields.start_date')); ?>

                                </th>
                                <td>
                                    <?php echo e($contract->AssignedOneContractDetail->start_date ? date('d-m-Y', strtotime($contract->AssignedOneContractDetail->start_date)) : ''); ?>

        
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php echo e(trans('cruds.contracts.fields.end_date')); ?>

                                </th>
                                <td>
                                    <?php echo e($contract->AssignedOneContractDetail->end_date ? date('d-m-Y', strtotime($contract->AssignedOneContractDetail->end_date)) : '-'); ?>

                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php echo e(trans('cruds.contracts.fields.contractLink')); ?>

                                </th>
                                <td>
                                    <?php if($contract->AssignedOneContractDetail->contract_upload != null): ?>
                                    <a href="<?php echo e(asset('contract_upload').'/'.$contract->AssignedOneContractDetail->contract_upload); ?>" download>
                                        Download
                                    </a>
                                    <?php endif; ?>
                                
                                </td>
                            </tr>
                            <?php if($contract->AssignedOneContractDetail->checklist_upload != null): ?>
                            <tr>
                                <th>
                                    <?php echo e(trans('cruds.contracts.fields.checklistLink')); ?>

                                </th>
                                <td>
                                    
                                    <a href="<?php echo e(asset('checklist_upload').'/'.$contract->AssignedOneContractDetail->checklist_upload); ?>" download>
                                        Download
                                    </a>
                    
                                </td>
                            </tr>
                            <?php endif; ?>
        
                            <tr>
                                <th>
                                    <?php echo e(trans('cruds.contracts.fields.recurring_contracts')); ?>

                                </th>
                                <td>
                                    
                                    <?php echo e($contract->AssignedOneContractDetail->recurring_contracts == 1 ? 'Yes' : 'No'); ?>

                    
                                </td>
                            </tr>
        
        
                            <!-- Add more fields as needed -->
                        </tbody>
                    
                    
                    </table>
                <?php endif; ?>
           
            <a style="margin-top:20px;" class="btn btn-default" href="<?php echo e(url()->previous()); ?>">
                <?php echo e(trans('global.back_to_list')); ?>

            </a>
        </div>


    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/laravel/bod/resources/views/admin/users/view_contract.blade.php ENDPATH**/ ?>