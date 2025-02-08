<?php $__env->startSection('content'); ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('contracts_create')): ?>
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            
        </div>
    </div>
<?php endif; ?>
<div class="card">
    <div class="card-header">
        Resume Fetch
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <form class="ml-md-5 pl-md-5 mt-3 mt-md-0 navbar-search" method="post" enctype="multipart/form-data" action="<?php echo e(route("admin.resumeFetchData.upload")); ?>">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <div class="custom-file">
                    <input type="file" name="file" class="custom-file-input" id="file">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
                <button type="submit" class="btn btn-primary upload-btn">Upload</button>
            </form>
    </div>
</div>


<?php if(session('user')): ?>
<?php $user = session('user'); 
?>
<div class="container py-4 my-2">
    <div class="row">
        <div class="col-md-4 pr-md-5">
            <img class="w-100 rounded border" src="<?php echo (isset($user->image))? asset('storage/images/'.$user->image) : '/img/avatar.jpg'; ?>" />
            
                
                    
                    
                        
                            
                            
                            
                        
                        
                            
                            
                            
                        
                    
                
            
        </div>
        <div class="col-md-8">
            <div class="d-flex align-items-center">
                <h2 class="font-weight-bold m-0">
                    <?php echo ($user->fullname)? : 'null'; ?>

                </h2>
                
                    
                    
                
            </div>
            <p class="h5 text-primary mt-2 d-block font-weight-light">
                <?php if(isset($user->experience)): ?>
                    <?php if(isset($user->experience[0]['position'])): ?>
                        <?php echo $user->experience[0]['position']; ?>

                    <?php endif; ?>
                <?php endif; ?>
            </p>
            
            
                
                
                    
                    
                        
                    
                
            
            
                
                    
                    
                
                
                    
                    
                
                
                    
                    
                
            
            <section class="mt-4">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                            About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="skills-tab" data-toggle="tab" href="#skills" role="tab" aria-controls="skills" aria-selected="false">
                            Skills
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="education-tab" data-toggle="tab" href="#education" role="tab" aria-controls="education" aria-selected="false">
                            Education
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="experience-tab" data-toggle="tab" href="#experience" role="tab" aria-controls="experience" aria-selected="false">
                            Experience
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="languages-tab" data-toggle="tab" href="#languages" role="tab" aria-controls="languages" aria-selected="false">
                            Languages
                        </a>
                    </li>
                </ul>
                <div class="tab-content py-4" id="myTabContent">
                    <div class="tab-pane py-3 fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <h6 class="text-uppercase font-weight-light text-secondary">
                            Contact Information
                        </h6>
                        <dl class="row mt-4 mb-4 pb-3">

                            <?php if(isset($user->email)): ?>
                                <dt class="col-sm-3">Email address</dt>
                                <dd class="col-sm-9">
                                    <a href="<?php echo 'mailto:'.$user->email; ?>"><?php echo $user->email; ?></a>
                                </dd>
                            <?php endif; ?>

                            <?php if(isset($user->phone)): ?>
                                <dt class="col-sm-3">Phone</dt>
                                <dd class="col-sm-9"><?php echo $user->phone; ?></dd>
                            <?php endif; ?>

                            <?php if(isset($user->address)): ?>
                                <dt class="col-sm-3">Home address</dt>
                                <dd class="col-sm-9">
                                    <address class="mb-0"><?php echo $user->address; ?></address>
                                </dd>
                            <?php endif; ?>

                            <?php if(isset($user->linkedin)): ?>
                                <dt class="col-sm-3">LinkedIn</dt>
                                <dd class="col-sm-9">
                                    <a href="<?php echo $user->linkedin; ?>"><?php echo $user->linkedin; ?></a>
                                </dd>
                            <?php endif; ?>

                            <?php if(isset($user->github)): ?>
                                <dt class="col-sm-3">Github</dt>
                                <dd class="col-sm-9">
                                    <a href="<?php echo $user->github; ?>"><?php echo $user->github; ?></a>
                                </dd>
                            <?php endif; ?>
                        </dl>

                        <h6 class="text-uppercase font-weight-light text-secondary">
                            Basic Information
                        </h6>
                        <dl class="row mt-4 mb-4 pb-3">

                            <?php if(isset($user->birthday)): ?>
                                <dt class="col-sm-3">Birthday</dt>
                                <dd class="col-sm-9"><?php echo $user->birthday; ?></dd>
                            <?php endif; ?>

                            <?php if(isset($user->gender)): ?>
                                <dt class="col-sm-3">Gender</dt>
                                <dd class="col-sm-9"><?php echo $user->gender; ?></dd>
                            <?php endif; ?>

                            <?php if(isset($user->nationality)): ?>
                                <dt class="col-sm-3">Nationality</dt>
                                <dd class="col-sm-9"><?php echo $user->nationality; ?></dd>
                            <?php endif; ?>
                        </dl>
                    </div>
                    <div class="tab-pane fade" id="skills" role="tabpanel" aria-labelledby="profile-tab">
                        <div>
                            <?php if(isset($user->skills)): ?>
                                <?php $__currentLoopData = $user->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <h5 class="skill"><span class="badge badge-pill badge-success"><?php echo $skill; ?></span></h5>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="education" role="tabpanel" aria-labelledby="profile-tab">
                        <section class="mb-4 pb-1">
                            <div class="work-experience pt-2">
                                <?php if(isset($user->education)): ?>
                                    <?php $__currentLoopData = $user->education; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $education): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="work mb-4">
                                            <strong class="h5 d-block text-secondary font-weight-bold mb-1"><span class="label">School:</span> <?php echo $education['university']; ?></strong>
                                            <strong class="h6 d-block text-warning mb-1"><span class="label">Degree:</span> <?php echo $education['degree']; ?></strong>
                                            <p class="text-secondary"><span class="label">Period:</span> <?php echo $education['date']; ?></p>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>
                    <div class="tab-pane fade" id="experience" role="tabpanel" aria-labelledby="contact-tab">
                        <section class="mb-4 pb-1">
                            <div class="work-experience pt-2">
                                <?php if(isset($user->experience)): ?>
                                    <?php $__currentLoopData = $user->experience; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $experience): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="work mb-4">
                                            <strong class="h5 d-block text-secondary font-weight-bold mb-1"><span class="label">Company:</span> <?php echo $experience['company']; ?></strong>
                                            <strong class="h6 d-block text-warning mb-1"><span class="label">Position:</span> <?php echo $experience['position']; ?></strong>
                                            <p class="text-secondary"><span class="label">Period:</span> <?php echo $experience['date']; ?></p>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>
                    <div class="tab-pane fade" id="languages" role="tabpanel" aria-labelledby="profile-tab">
                        <div>
                            <?php if(isset($user->languages)): ?>
                                <?php $__currentLoopData = $user->languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <h5 class="skill"><span class="badge badge-pill badge-success"><?php echo $language; ?></span></h5>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<?php endif; ?>



<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php echo \Illuminate\View\Factory::parentPlaceholder('scripts'); ?>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rating/1.5.0/bootstrap-rating.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-pie-chart/2.1.6/jquery.easypiechart.min.js"></script>


<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
// <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role_delete')): ?>
//   let deleteButtonTrans = '<?php echo e(trans('global.datatables.delete')); ?>'
//   let deleteButton = {
//     text: deleteButtonTrans,
//     url: "<?php echo e(route('admin.roles.massDestroy')); ?>",
//     className: 'btn-danger',
//     action: function (e, dt, node, config) {
//       var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
//           return $(entry).data('entry-id')
//       });

//       if (ids.length === 0) {
//         alert('<?php echo e(trans('global.datatables.zero_selected')); ?>')

//         return
//       }

//       if (confirm('<?php echo e(trans('global.areYouSure')); ?>')) {
//         $.ajax({
//           headers: {'x-csrf-token': _token},
//           method: 'POST',
//           url: config.url,
//           data: { ids: ids, _method: 'DELETE' }})
//           .done(function () { location.reload() })
//       }
//     }
//   }
//   dtButtons.push(deleteButton)
// <?php endif; ?>

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 0, 'asc' ]],
    pageLength: 10,
  });
  $('.datatable-Role:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>

<!-- delete Data script -->
<script>
    $(document).ready(function() {
        $('.delete-btn').click(function() {
            var itemId = $(this).data('id');
            $('#delete-model').modal('show');
            $('.delete-form').attr('action', "<?php echo e(url('admin/contracts/destroy')); ?>?id=" + itemId);
        });

        $('.cancel-btn').click(function() {
            $(this).closest('.modal').modal('hide');
        });
    });
</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/laravel/bod/resources/views/admin/resumeFetch/index.blade.php ENDPATH**/ ?>