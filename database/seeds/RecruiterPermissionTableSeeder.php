<?php
use App\CmsPage;
use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class RecruiterPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $admin_permissions = Permission::all();
        Role::findOrFail(4)->permissions()->sync($admin_permissions->pluck('id'));
        $user_permissions = $admin_permissions->filter(function ($permission) {
            return  ($permission->title == 'job_applied_create' || $permission->title == 'job_applied_access' || $permission->title == 'job_active') || substr($permission->title, 0, 10) == 'candidate_' ||  $permission->title == 'recruiter_reports_access' ||  $permission->title == 'recent_status_updates' ||  $permission->title == 'saved_candidate_access' || $permission->title == 'job_show' || substr($permission->title, 0, 14) == 'notifications_';


        });
        Role::findOrFail(4)->permissions()->sync($user_permissions);
    }
}
