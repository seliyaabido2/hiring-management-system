<?php
   $userId = auth()->user()->id;
   $roleName = getUserRole($userId);
?>

<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            

            <li class="nav-item">
                <a href="<?php echo e(route('admin.home')); ?>" class="nav-link">
                    <i class="nav-icon fas fa-fw fa-tachometer-alt">

                    </i>
                    <?php echo e(trans('global.dashboard')); ?>

                </a>
            </li>

            

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user_management_access')): ?>
             <li class="nav-item nav-dropdown <?php echo e(request()->is('admin/employer/*') || request()->is('admin/RecruiterPartner/*') || request()->is('admin/SubAdmin/*')  ? 'open' : ''); ?>">
                <a class="nav-link  nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users nav-icon"></i>
                    <?php echo e(trans('cruds.userManagement.title')); ?>

                </a>
                <ul class="nav-dropdown-items dropdown-menu-closed">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user_access')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.employer.index')); ?>" class="nav-link <?php echo e(request()->is('admin/employer') || request()->is('admin/employer/*') ? 'active' : ''); ?>">
                        <i class="fa-fw fas fa-user nav-icon"></i>
                            <?php echo e(trans('cruds.userManagement.employer')); ?>

                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.RecruiterPartner.index')); ?>" class="nav-link <?php echo e(request()->is('admin/RecruiterPartner') || request()->is('admin/RecruiterPartner/*') ? 'active' : ''); ?>">
                            <i class="fa-fw fas fa-user-tie nav-icon"></i>
                                <?php echo e(trans('cruds.userManagement.recruitment_partner')); ?>

                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.SubAdmin.index')); ?>" class="nav-link <?php echo e(request()->is('admin/SubAdmin') || request()->is('admin/SubAdmin/*') ? 'active' : ''); ?>">
                                <i class="fa-fw fas fa-user-cog nav-icon"></i>

                                <?php echo e(trans('cruds.userManagement.sub_admin')); ?>

                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
             </li>
            <?php endif; ?>

            

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('job_access')): ?>
             <li class="nav-item nav-dropdown <?php echo e(request()->is('admin/appliedJobs/*') || request()->is('admin/employerJobs/*') ? 'open' : ''); ?>">
                <a class="nav-link  nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users-cog nav-icon">

                    </i>
                    Employer Management
                </a>
                <ul class="nav-dropdown-items">
                    <?php if($roleName == 'Employer'): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.appliedJobs.recentStatusUpdates')); ?>" class="nav-link <?php echo e(request()->is('admin/recentStatusUpdates/appliedJobs')  ? 'active' : ''); ?>">
                            <i class="fa-fw fas fa-file-export nav-icon">

                            </i>
                            Recent Updated Status
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('job_applied_access')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/appliedJobs')); ?>" class="nav-link <?php echo e(request()->is('admin/appliedJobs/*') ? 'active' : ''); ?>">
                            <i class="fa-fw fas fa-user-check nav-icon">

                            </i>
                            Applied candidates list <br> (based on Jobs)
                        </a>
                    </li>
                    <?php endif; ?>

                    <li class="nav-item nav-dropdown <?php echo e(request()->is('admin/employerJobs/*') && !request()->is('admin/employerJobs/mySavedJobTemplates') ? 'open' : ''); ?>">

                        <a class="nav-link  nav-dropdown-toggle" href="#">
                            <i class="fa-fw fas fa-briefcase nav-icon"></i>
                            Jobs DB
                        </a>


                        <ul class="nav-dropdown-items">

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('job_create')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('admin.employerJobs.createJob')); ?>" class="nav-link <?php echo e(request()->is('admin/employerJobs/create') ? 'active' : ''); ?> ">
                                    <i class="fa-fw fas fa-user-edit nav-icon">
                                    </i>
                                    Create New Job
                                </a>
                            </li>
                            <?php endif; ?>


                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('job_active')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(url('admin/employerJobs/status/activeJobs')); ?>" class="nav-link <?php echo e(request()->is('admin/employerJobs/status/activeJobs') || Route::currentRouteName() == 'admin.employerJobs.show' || Route::currentRouteName() == 'admin.employerJobs.edit' ? 'active' : ''); ?>

                                    ? 'active' : ''  }}">
                                    <i class="fa-fw fas fa-user-plus nav-icon">

                                    </i>
                                    Active job
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('job_pending')): ?>

                            <li class="nav-item">

                                <a href="<?php echo e(route('admin.employerJobs.requestJobs')); ?>" class="nav-link <?php echo e(request()->is('admin/employerJobs/requestJobs') ? 'active' : ''); ?>">
                                    <i class="fa-fw fas fa-user-clock nav-icon">

                                    </i>
                                    Pending Jobs
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('job_deactive')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(url('admin/employerJobs/status/DeActiveJobs')); ?>" class="nav-link <?php echo e(request()->is('admin/employerJobs/status/DeActive') ? 'active' : ''); ?>">
                                    <i class="fa-fw fas fa-user-minus nav-icon">
                                    </i>
                                    <?php echo e(trans('global.deactive')); ?> job
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('job_closed')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(url('admin/employerJobs/status/closedJobs')); ?>" class="nav-link <?php echo e(request()->is('admin/employerJobs/status/closedJobs') ? 'active' : ''); ?>">
                                    <i class="fa-fw fas fa-user-alt-slash nav-icon">

                                    </i>
                                    Closed job
                                </a>
                            </li>
                            <?php endif; ?>


                        </ul>
                    </li>
                    <?php if($roleName =='Recruiter' ||  $roleName =='Employer'): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('saved_candidate_access')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.candidate.savedCandidates')); ?>" class="nav-link <?php echo e(request()->is('admin/appliedJobs') || request()->is('admin/appliedJobs/*') ? 'active' : ''); ?>">
                            <i class="fa-fw fas fa-user-shield nav-icon">

                            </i>
                            <?php echo e(trans('cruds.savedCandidates.title')); ?>


                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php if($roleName =='Employer'): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('saved_template_access')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.employerJobs.mySavedJobTemplates')); ?>" class="nav-link <?php echo e(request()->is('admin/employerJobs/mySavedJobTemplates') ? 'active' : ''); ?>">
                            <i class="fa-fw fas fa-bookmark nav-icon">

                            </i>
                            <?php echo e(trans('cruds.mySavedJobTeamplates.title')); ?>


                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>

                </ul>
             </li>
            <?php endif; ?>

            

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('candidate_access')): ?>
             <li class="nav-item nav-dropdown <?php echo e(request()->is('admin/candidate/*') || request()->is('admin/appliedJobs/*') || request()->is('admin/employerJobs/apply-job/*')  ? 'open' : ''); ?>">
                <a class="nav-link  nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fas fa-handshake nav-icon">

                    </i>
                    Recruitment Partner Management
                </a>
                <ul class="nav-dropdown-items">

                    <?php if($roleName == 'Recruiter'): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('recent_status_updates')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.appliedJobs.recentStatusUpdates')); ?>" class="nav-link">
                            <i class="fa-fw fas fa-user-plus nav-icon">

                            </i>
                            Recent Applied candidates
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>


                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.candidate.index')); ?>" class="nav-link <?php echo e(request()->is('admin/candidate/*') && !request()->is('admin/candidate/savedCandidates') ? 'active' : ''); ?>">

                            <i class="fa-fw fas fa-user-alt nav-icon">

                            </i>
                            <?php echo e(trans('cruds.candidates.title')); ?>

                        </a>
                    </li>

                    <?php if($roleName =='Recruiter'): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('job_applied_create')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(url('admin/employerJobs/status/activeJobs')); ?>" class="nav-link <?php echo e(request()->is('admin/employerJobs/status/activeJobs') ||  request()->is('admin/employerJobs/apply-job/*')  ? 'active' : ''); ?>">

                                <i class="fa-fw fas fa-suitcase nav-icon">

                                </i>
                                <?php echo e(trans('global.applyforjob')); ?>

                            </a>
                        </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('job_applied_access')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/appliedJobs/')); ?>" class="nav-link <?php echo e(request()->is('admin/appliedJobs') || request()->is('admin/appliedJobs/*') ? 'active' : ''); ?>">
                            <i class="fa-fw fas fa-user-check nav-icon">

                            </i>
                            <?php echo e(trans('cruds.appliedJobs.fields.appliedCandidatesList')); ?>

                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('saved_candidate_access')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.candidate.savedCandidates')); ?>" class="nav-link <?php echo e(request()->is('admin/candidate/savedCandidates/')  ? 'active' : ''); ?>">
                            <i class="fa-fw fas fa-user-shield nav-icon">

                            </i>
                            <?php echo e(trans('cruds.savedCandidates.title')); ?>


                        </a>
                    </li>
                    <?php endif; ?>


                </ul>
             </li>
            <?php endif; ?>

            

            <?php if($roleName == 'Super Admin' || $roleName == 'Admin'): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('recent_status_updates')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.appliedJobs.recentStatusUpdates')); ?>" class="nav-link <?php echo e(request()->is('admin/appliedJobs/recentStatusUpdates')  ? 'active' : ''); ?>">
                            <i class="fa-fw fas fa-file-export nav-icon">

                            </i>
                            Recent Updated Status
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>


            

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bod_candidate_access')): ?>
                <li class="nav-item nav-dropdown <?php echo e(request()->is('admin/bodCandidate/*')  ? 'open' : ''); ?>">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-server nav-icon">

                        </i>
                        Bod Management
                    </a>
                    <ul class="nav-dropdown-items">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bod_candidate_access')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('admin.bodCandidate.index')); ?>" class="nav-link <?php echo e(request()->is('admin/bodCandidate') || request()->is('admin/bodCandidate/*') ? 'active' : ''); ?>">
                                    <i class="fa-fw fas fa-user-alt nav-icon">

                                    </i>
                                    <?php echo e(trans('cruds.bodCandidates.title')); ?>

                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bod_job_applied_access')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.bodAppliedJobs.index')); ?>" class="nav-link <?php echo e(request()->is('admin/bodAppliedJobs') || request()->is('admin/bodAppliedJobs/*') ? 'active' : ''); ?>">
                                <i class="fa-fw fas fa-user-plus nav-icon">

                                </i>

                                <?php echo e(trans('cruds.bodAppliedCandidatesList.title')); ?>

                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('job_create')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.employerJobs.bodcreateJob')); ?>" class="nav-link <?php echo e(request()->is('admin/employerJobs/bodcreateJob') ? 'active' : ''); ?> ">
                                <i class="fa-fw fas fa-user-edit nav-icon">
                                </i>
                                Create New Job
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bod_job_applied_create')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(url('admin/employerJobs/status/bodactiveJobs')); ?>" class="nav-link <?php echo e(request()->is('admin/employerJobs/status/bodactiveJobs') ? 'active' : ''); ?>">
                                    <i class="fa-fw fas fa-user-check nav-icon">
                                    </i>
                                <?php echo e(trans('global.applyforjob')); ?>

                                </a>
                            </li>
                        <?php endif; ?>


                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bod_saved_candidate_access')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('admin.bodCandidate.savedCandidates')); ?>" class="nav-link <?php echo e(request()->is('admin/bodCandidate/savedCandidates')  ? 'active' : ''); ?>">
                                    <i class="fa-fw fas fa-user-shield nav-icon">

                                    </i>
                                    <?php echo e(trans('cruds.bodSavedCandidates.title')); ?>


                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bod_saved_template_access')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.employerJobs.mySavedJobTemplates')); ?>" class="nav-link <?php echo e(request()->is('admin/employerJobs') || request()->is('admin/employerJobs/*') ? 'active' : ''); ?>">
                                <i class="fa-fw fas fa-bookmark nav-icon">
                                </i>
                                <?php echo e(trans('cruds.mySavedJobTeamplates.title')); ?>

                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employer_reports_access')): ?>
              <li class="nav-item">
                 <a href="<?php echo e(route('admin.employerReports.index')); ?>" class="nav-link <?php echo e(request()->is('admin/employerReports') || request()->is('admin/employerReports/*') ? 'active' : ''); ?>">
                     <i class="fa-fw fas fa-file-alt  nav-icon">

                     </i>
                     <?php echo e(trans('cruds.employerReports.title')); ?>

                 </a>
              </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('recruiter_reports_access')): ?>
              <li class="nav-item">
                 <a href="<?php echo e(route('admin.recruiterReports.index')); ?>" class="nav-link <?php echo e(request()->is('admin/recruiterReports') || request()->is('admin/recruiterReports/*') ? 'active' : ''); ?>">
                     <i class="fa-fw fas fa-file-alt  nav-icon">

                     </i>
                     <?php echo e(trans('cruds.recruiterReports.title')); ?>

                 </a>
              </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bod_candidate_reports_access')): ?>
              <li class="nav-item">
                 <a href="<?php echo e(route('admin.bodCandidateReports.index')); ?>" class="nav-link <?php echo e(request()->is('admin/bodCandidateReports') || request()->is('admin/bodCandidateReports/*') ? 'active' : ''); ?>">
                     <i class="fa-fw fas fa-file-alt  nav-icon">

                     </i>
                     <?php echo e(trans('cruds.bodCandidateReports.title')); ?>

                 </a>
              </li>
            <?php endif; ?>


            

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role_access')): ?>
                <li class="nav-item nav-dropdown  <?php echo e(request()->is('admin/contracts/*') || request()->is('admin/roles/*')   ? 'open' : ''); ?>">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        <i class="fa-fw  fas fa-user-cog nav-icon">

                        </i>
                        Settings
                    </a>
                    <ul class="nav-dropdown-items">
                        
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('contracts_access')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.contracts.index')); ?>" class="nav-link <?php echo e(request()->is('admin/contracts') || request()->is('admin/contracts/*') ? 'active' : ''); ?>">
                                <i class="fa-fw fas fa-cog nav-icon">

                                </i>
                                Contract Management

                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cms_pages_access')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.cmsPages.index')); ?>" class="nav-link <?php echo e(request()->is('admin/cmsPages') || request()->is('admin/cmsPages/*') ? 'active' : ''); ?>">
                                <i class="fa-fw fas fa-cogs nav-icon">

                                </i>
                                <?php echo e(trans('cruds.cmsPages.title')); ?>

                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('notifications_access')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.notifications.index')); ?>" class="nav-link <?php echo e(request()->is('admin/notifications') || request()->is('admin/notifications/*') ? 'active' : ''); ?>">
                        <i class="fa-fw fas fa-bell nav-icon">

                        </i>
                        Notifications

                    </a>
                </li>
            <?php endif; ?>

                
            <?php if(in_array(getUserRole(Auth::user()->id), ['Employer', 'Recruiter'])): ?>

            <li class="nav-item" >
                <a href="<?php echo e(route('admin.users.viewContract')); ?>" class="nav-link <?php echo e(request()->is('admin/users/viewContract') || request()->is('admin/users/viewContract*') ? 'active' : ''); ?>">
                    <i class="fa-fw fa-solid fa-file nav-icon">

                    </i>
                    Contract View
                </a>
                <a href="<?php echo e(route('admin.users.viewContract')); ?>"></a></li>
            <?php endif; ?>

        </ul>

    </nav>
    <button class="sidebar-minimizer brand-minimizer sidearrow" type="button"></button>
</div>

<style>
 ::-webkit-scrollbar {
    width: 5px;

  }

   /* Track */
   ::-webkit-scrollbar-track {
    background: #f1f1f1;
   }

   /* Handle */
   ::-webkit-scrollbar-thumb {
    background: #888;
   }

  /* Handle on hover */
   ::-webkit-scrollbar-thumb:hover {
    background: #555;
   }
</style>
<?php /**PATH /var/www/html/laravel/bod/resources/views/partials/menu.blade.php ENDPATH**/ ?>