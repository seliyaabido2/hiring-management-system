@php
   $userId = auth()->user()->id;
   $roleName = getUserRole($userId);
@endphp

<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            {{-- Dashboard --}}

            <li class="nav-item">
                <a href="{{ route('admin.home') }}" class="nav-link">
                    <i class="nav-icon fas fa-fw fa-tachometer-alt">

                    </i>
                    {{ trans('global.dashboard') }}
                </a>
            </li>

            {{-- User Management --}}

            @can('user_management_access')
             <li class="nav-item nav-dropdown {{ request()->is('admin/employer/*') || request()->is('admin/RecruiterPartner/*') || request()->is('admin/SubAdmin/*')  ? 'open' : '' }}">
                <a class="nav-link  nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users nav-icon"></i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="nav-dropdown-items dropdown-menu-closed">
                    @can('user_access')
                        <li class="nav-item">
                            <a href="{{ route('admin.employer.index') }}" class="nav-link {{ request()->is('admin/employer') || request()->is('admin/employer/*') ? 'active' : '' }}">
                        <i class="fa-fw fas fa-user nav-icon"></i>
                            {{ trans('cruds.userManagement.employer') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.RecruiterPartner.index') }}" class="nav-link {{ request()->is('admin/RecruiterPartner') || request()->is('admin/RecruiterPartner/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-user-tie nav-icon"></i>
                                {{ trans('cruds.userManagement.recruitment_partner') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.SubAdmin.index') }}" class="nav-link {{ request()->is('admin/SubAdmin') || request()->is('admin/SubAdmin/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-user-cog nav-icon"></i>

                                {{ trans('cruds.userManagement.sub_admin') }}
                            </a>
                        </li>
                    @endcan
                </ul>
             </li>
            @endcan

            {{--  Employer Management --}}

            @can('job_access')
             <li class="nav-item nav-dropdown {{ request()->is('admin/appliedJobs/*') || request()->is('admin/employerJobs/*') ? 'open' : '' }}">
                <a class="nav-link  nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users-cog nav-icon">

                    </i>
                    Employer Management
                </a>
                <ul class="nav-dropdown-items">
                    @if($roleName == 'Employer')
                    <li class="nav-item">
                        <a href="{{ route('admin.appliedJobs.recentStatusUpdates') }}" class="nav-link {{ request()->is('admin/recentStatusUpdates/appliedJobs')  ? 'active' : '' }}">
                            <i class="fa-fw fas fa-file-export nav-icon">

                            </i>
                            Recent Updated Status
                        </a>
                    </li>
                    @endif

                    @can('job_applied_access')
                    <li class="nav-item">
                        <a href="{{ url('admin/appliedJobs') }}" class="nav-link {{ request()->is('admin/appliedJobs/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-user-check nav-icon">

                            </i>
                            Applied candidates list <br> (based on Jobs)
                        </a>
                    </li>
                    @endcan

                    <li class="nav-item nav-dropdown {{ request()->is('admin/employerJobs/*') && !request()->is('admin/employerJobs/mySavedJobTemplates') ? 'open' : '' }}">

                        <a class="nav-link  nav-dropdown-toggle" href="#">
                            <i class="fa-fw fas fa-briefcase nav-icon"></i>
                            Jobs DB
                        </a>


                        <ul class="nav-dropdown-items">

                            @can('job_create')
                            <li class="nav-item">
                                <a href="{{ route('admin.employerJobs.createJob') }}" class="nav-link {{ request()->is('admin/employerJobs/create') ? 'active' : '' }} ">
                                    <i class="fa-fw fas fa-user-edit nav-icon">
                                    </i>
                                    Create New Job
                                </a>
                            </li>
                            @endcan


                            @can('job_active')
                            <li class="nav-item">
                                <a href="{{ url('admin/employerJobs/status/activeJobs') }}" class="nav-link {{ request()->is('admin/employerJobs/status/activeJobs') || Route::currentRouteName() == 'admin.employerJobs.show' || Route::currentRouteName() == 'admin.employerJobs.edit' ? 'active' : '' }}
                                    ? 'active' : ''  }}">
                                    <i class="fa-fw fas fa-user-plus nav-icon">

                                    </i>
                                    Active job
                                </a>
                            </li>
                            @endcan

                            @can('job_pending')

                            <li class="nav-item">

                                <a href="{{ route('admin.employerJobs.requestJobs') }}" class="nav-link {{ request()->is('admin/employerJobs/requestJobs') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-user-clock nav-icon">

                                    </i>
                                    Pending Jobs
                                </a>
                            </li>
                            @endcan

                            @can('job_deactive')
                            <li class="nav-item">
                                <a href="{{ url('admin/employerJobs/status/DeActiveJobs') }}" class="nav-link {{ request()->is('admin/employerJobs/status/DeActive') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-user-minus nav-icon">
                                    </i>
                                    {{ trans('global.deactive') }} job
                                </a>
                            </li>
                            @endcan

                            @can('job_closed')
                            <li class="nav-item">
                                <a href="{{ url('admin/employerJobs/status/closedJobs') }}" class="nav-link {{ request()->is('admin/employerJobs/status/closedJobs') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-user-alt-slash nav-icon">

                                    </i>
                                    Closed job
                                </a>
                            </li>
                            @endcan


                        </ul>
                    </li>
                    @if($roleName =='Recruiter' ||  $roleName =='Employer')
                    @can('saved_candidate_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.candidate.savedCandidates') }}" class="nav-link {{ request()->is('admin/appliedJobs') || request()->is('admin/appliedJobs/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-user-shield nav-icon">

                            </i>
                            {{ trans('cruds.savedCandidates.title') }}

                        </a>
                    </li>
                    @endcan
                    @endif
                    @if($roleName =='Employer')
                    @can('saved_template_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.employerJobs.mySavedJobTemplates') }}" class="nav-link {{ request()->is('admin/employerJobs/mySavedJobTemplates') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-bookmark nav-icon">

                            </i>
                            {{ trans('cruds.mySavedJobTeamplates.title') }}

                        </a>
                    </li>
                    @endcan
                    @endif

                </ul>
             </li>
            @endcan

            {{--  Recruiter Management --}}

            @can('candidate_access')
             <li class="nav-item nav-dropdown {{ request()->is('admin/candidate/*') || request()->is('admin/appliedJobs/*') || request()->is('admin/employerJobs/apply-job/*')  ? 'open' : ''  }}">
                <a class="nav-link  nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fas fa-handshake nav-icon">

                    </i>
                    Recruitment Partner Management
                </a>
                <ul class="nav-dropdown-items">

                    @if($roleName == 'Recruiter')
                    @can('recent_status_updates')
                    <li class="nav-item">
                        <a href="{{ route('admin.appliedJobs.recentStatusUpdates') }}" class="nav-link">
                            <i class="fa-fw fas fa-user-plus nav-icon">

                            </i>
                            Recent Applied candidates
                        </a>
                    </li>
                    @endcan
                    @endif


                    <li class="nav-item">
                        <a href="{{ route('admin.candidate.index') }}" class="nav-link {{ request()->is('admin/candidate/*') && !request()->is('admin/candidate/savedCandidates') ? 'active' : '' }}">

                            <i class="fa-fw fas fa-user-alt nav-icon">

                            </i>
                            {{ trans('cruds.candidates.title') }}
                        </a>
                    </li>

                    @if($roleName =='Recruiter')
                        @can('job_applied_create')
                        <li class="nav-item">
                            <a href="{{ url('admin/employerJobs/status/activeJobs') }}" class="nav-link {{ request()->is('admin/employerJobs/status/activeJobs') ||  request()->is('admin/employerJobs/apply-job/*')  ? 'active' : '' }}">

                                <i class="fa-fw fas fa-suitcase nav-icon">

                                </i>
                                {{ trans('global.applyforjob') }}
                            </a>
                        </li>
                        @endcan
                    @endif

                    @can('job_applied_access')
                    <li class="nav-item">
                        <a href="{{ url('admin/appliedJobs/') }}" class="nav-link {{ request()->is('admin/appliedJobs') || request()->is('admin/appliedJobs/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-user-check nav-icon">

                            </i>
                            {{ trans('cruds.appliedJobs.fields.appliedCandidatesList') }}
                        </a>
                    </li>
                    @endcan
                    @can('saved_candidate_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.candidate.savedCandidates') }}" class="nav-link {{ request()->is('admin/candidate/savedCandidates/')  ? 'active' : '' }}">
                            <i class="fa-fw fas fa-user-shield nav-icon">

                            </i>
                            {{ trans('cruds.savedCandidates.title') }}

                        </a>
                    </li>
                    @endcan


                </ul>
             </li>
            @endcan

            {{-- Recent Updated Status --}}

            @if($roleName == 'Super Admin' || $roleName == 'Admin')
                @can('recent_status_updates')
                    <li class="nav-item">
                        <a href="{{ route('admin.appliedJobs.recentStatusUpdates') }}" class="nav-link {{ request()->is('admin/appliedJobs/recentStatusUpdates')  ? 'active' : '' }}">
                            <i class="fa-fw fas fa-file-export nav-icon">

                            </i>
                            Recent Updated Status
                        </a>
                    </li>
                @endcan
            @endif


            {{-- Bod Management --}}

            @can('bod_candidate_access')
                <li class="nav-item nav-dropdown {{ request()->is('admin/bodCandidate/*')  ? 'open' : ''  }}">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-server nav-icon">

                        </i>
                        Bod Management
                    </a>
                    <ul class="nav-dropdown-items">
                        @can('bod_candidate_access')
                            <li class="nav-item">
                                <a href="{{ route('admin.bodCandidate.index') }}" class="nav-link {{ request()->is('admin/bodCandidate') || request()->is('admin/bodCandidate/*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-user-alt nav-icon">

                                    </i>
                                    {{ trans('cruds.bodCandidates.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('bod_job_applied_access')
                        <li class="nav-item">
                            <a href="{{ route('admin.bodAppliedJobs.index') }}" class="nav-link {{ request()->is('admin/bodAppliedJobs') || request()->is('admin/bodAppliedJobs/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-user-plus nav-icon">

                                </i>

                                {{ trans('cruds.bodAppliedCandidatesList.title') }}
                            </a>
                        </li>
                        @endcan
                        @can('job_create')
                        <li class="nav-item">
                            <a href="{{ route('admin.employerJobs.bodcreateJob') }}" class="nav-link {{ request()->is('admin/employerJobs/bodcreateJob') ? 'active' : '' }} ">
                                <i class="fa-fw fas fa-user-edit nav-icon">
                                </i>
                                Create New Job
                            </a>
                        </li>
                        @endcan
                        @can('bod_job_applied_create')
                            <li class="nav-item">
                                <a href="{{ url('admin/employerJobs/status/bodactiveJobs') }}" class="nav-link {{ request()->is('admin/employerJobs/status/bodactiveJobs') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-user-check nav-icon">
                                    </i>
                                {{ trans('global.applyforjob') }}
                                </a>
                            </li>
                        @endcan


                        @can('bod_saved_candidate_access')
                            <li class="nav-item">
                                <a href="{{ route('admin.bodCandidate.savedCandidates') }}" class="nav-link {{ request()->is('admin/bodCandidate/savedCandidates')  ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-user-shield nav-icon">

                                    </i>
                                    {{ trans('cruds.bodSavedCandidates.title') }}

                                </a>
                            </li>
                        @endcan
                        @can('bod_saved_template_access')
                        <li class="nav-item">
                            <a href="{{ route('admin.employerJobs.mySavedJobTemplates') }}" class="nav-link {{ request()->is('admin/employerJobs') || request()->is('admin/employerJobs/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-bookmark nav-icon">
                                </i>
                                {{ trans('cruds.mySavedJobTeamplates.title') }}
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            {{-- Reports --}}

            @can('employer_reports_access')
              <li class="nav-item">
                 <a href="{{ route('admin.employerReports.index') }}" class="nav-link {{ request()->is('admin/employerReports') || request()->is('admin/employerReports/*') ? 'active' : '' }}">
                     <i class="fa-fw fas fa-file-alt  nav-icon">

                     </i>
                     {{ trans('cruds.employerReports.title') }}
                 </a>
              </li>
            @endcan

            @can('recruiter_reports_access')
              <li class="nav-item">
                 <a href="{{ route('admin.recruiterReports.index') }}" class="nav-link {{ request()->is('admin/recruiterReports') || request()->is('admin/recruiterReports/*') ? 'active' : '' }}">
                     <i class="fa-fw fas fa-file-alt  nav-icon">

                     </i>
                     {{ trans('cruds.recruiterReports.title') }}
                 </a>
              </li>
            @endcan

            @can('bod_candidate_reports_access')
              <li class="nav-item">
                 <a href="{{ route('admin.bodCandidateReports.index') }}" class="nav-link {{ request()->is('admin/bodCandidateReports') || request()->is('admin/bodCandidateReports/*') ? 'active' : '' }}">
                     <i class="fa-fw fas fa-file-alt  nav-icon">

                     </i>
                     {{ trans('cruds.bodCandidateReports.title') }}
                 </a>
              </li>
            @endcan


            {{-- settings --}}

            @can('role_access')
                <li class="nav-item nav-dropdown  {{ request()->is('admin/contracts/*') || request()->is('admin/roles/*')   ? 'open' : ''  }}">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        <i class="fa-fw  fas fa-user-cog nav-icon">

                        </i>
                        Settings
                    </a>
                    <ul class="nav-dropdown-items">
                        {{-- @can('role_access')
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-briefcase nav-icon"></i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                        @endcan --}}
                        @can('contracts_access')
                        <li class="nav-item">
                            <a href="{{ route('admin.contracts.index') }}" class="nav-link {{ request()->is('admin/contracts') || request()->is('admin/contracts/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-cog nav-icon">

                                </i>
                                Contract Management

                            </a>
                        </li>
                        @endcan
                        @can('cms_pages_access')
                        <li class="nav-item">
                            <a href="{{ route('admin.cmsPages.index') }}" class="nav-link {{ request()->is('admin/cmsPages') || request()->is('admin/cmsPages/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-cogs nav-icon">

                                </i>
                                {{ trans('cruds.cmsPages.title') }}
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            {{-- Notifications --}}

            @can('notifications_access')
                <li class="nav-item">
                    <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->is('admin/notifications') || request()->is('admin/notifications/*') ? 'active' : '' }}">
                        <i class="fa-fw fas fa-bell nav-icon">

                        </i>
                        Notifications

                    </a>
                </li>
            @endcan

                {{-- <li class="nav-item">
                    <a href="{{ route('admin.resumeFetchData.index') }}" class="nav-link {{ request()->is('admin/notifications') || request()->is('admin/notifications/*') ? 'active' : '' }}">
                        <i class="fa-fw fa-solid fa-file nav-icon">

                        </i>
                        Resume fetch data
                    </a>
                </li> --}}
            @if(in_array(getUserRole(Auth::user()->id), ['Employer', 'Recruiter']))

            <li class="nav-item" >
                <a href="{{ route('admin.users.viewContract') }}" class="nav-link {{ request()->is('admin/users/viewContract') || request()->is('admin/users/viewContract*') ? 'active' : '' }}">
                    <i class="fa-fw fa-solid fa-file nav-icon">

                    </i>
                    Contract View
                </a>
                <a href="{{ route('admin.users.viewContract') }}"></a></li>
            @endif

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
