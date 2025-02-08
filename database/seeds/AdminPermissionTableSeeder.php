<?php
use App\CmsPage;
use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class AdminPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $admin_permissions = Permission::all();
        Role::findOrFail(2)->permissions()->sync($admin_permissions->pluck('id'));
        $user_permissions = $admin_permissions->filter(function ($permission) {
            return
            substr($permission->title, 0, 5) == 'user_'
            || (substr($permission->title, 0, 4) == 'job_' && $permission->title != 'job_applied_create')
            || $permission->title == 'candidate_access'
            ||  $permission->title == 'candidate_show'
            || substr($permission->title, 0, 7) == 'my_job_'
            || $permission->title == 'job_pending'
            ||  $permission->title == 'employer_reports_access'
            ||  $permission->title == 'recruiter_reports_access'
            || $permission->title == 'bod_candidate_reports_access'
            || substr($permission->title, 0, 9) == 'contracts_'
            || substr($permission->title, 0, 4) == 'bod_'
            || $permission->title == 'recent_status_updates'
            || substr($permission->title, 0, 14) == 'notifications_';

        });
        Role::findOrFail(2)->permissions()->sync($user_permissions);
    }
}
