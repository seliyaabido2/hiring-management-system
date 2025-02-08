<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            ContactTableSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,
            AssignContactTableSeeder::class,
            CMSPageSeeder::class,
            CandidateTableSeeder::class,
            AdminPermissionTableSeeder::class,
            EmployerPermissionTableSeeder::class,
            RecruiterPermissionTableSeeder::class,
            EmployerDetailTableSeeder::class,
            RecruiterDetailTableSeeder::class,
            AdminDetailTableSeeder::class,
            CreateJobTableSeeder::class,


        ]);
    }
}

