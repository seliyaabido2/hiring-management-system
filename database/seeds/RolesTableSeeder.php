<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id'         => 1,
                'title'      => 'Super Admin',
                'created_at' => '2019-09-19 12:08:28',
                'updated_at' => '2019-09-19 12:08:28',
            ],
            [
                'id'         => 2,
                'title'      => 'Admin',
                'created_at' => '2019-09-19 12:08:28',
                'updated_at' => '2019-09-19 12:08:28',
            ],
            [
                'id'         => 3,
                'title'      => 'Employer',
                'created_at' => '2019-09-19 12:08:28',
                'updated_at' => '2019-09-19 12:08:28',
            ],
            [
                'id'         => 4,
                'title'      => 'Recruiter',
                'created_at' => '2019-09-19 12:08:28',
                'updated_at' => '2019-09-19 12:08:28',
            ],
        ];

        Role::insert($roles);
    }
}
