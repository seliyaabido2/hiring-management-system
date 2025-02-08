<?php
use App\CmsPage;
use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class EmployerPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $admin_permissions = Permission::all();
        Role::findOrFail(3)->permissions()->sync($admin_permissions->pluck('id'));
        $user_permissions = $admin_permissions->filter(function ($permission) {

            return  (substr($permission->title, 0, 4) == 'job_' && $permission->title != 'job_applied_create' || $permission->title == 'candidate_show') || (substr($permission->title, 0, 7) == 'my_job_') ||
            $permission->title == 'recent_status_updates'  ||  $permission->title == 'employer_reports_access' ||  $permission->title == 'saved_template_access' || $permission->title  == 'saved_candidate_access'  || substr($permission->title, 0, 14) == 'notifications_';

        });
        Role::findOrFail(3)->permissions()->sync($user_permissions);
    }
}
