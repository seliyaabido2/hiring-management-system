<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'first_name'           => 'Super',
                'last_name'           => 'Admin',
                'email'          => 'superadmin@yopmail.com',
                'image'         => 'default-user.jpg',
                'password'       =>Hash::make('Admin@123'),
                'remember_token' => null,
                'created_at'     => '2019-09-19 12:08:28',
                'updated_at'     => '2019-09-19 12:08:28',
            ],
            [
                'id'             => 2,
                'first_name'           => 'admin',
                'last_name'           => 'thomas',
                'email'          => 'admin@yopmail.com',
                'image'         => 'default-user.jpg',
                'password'       =>Hash::make('Admin@123'),
                'remember_token' => null,
                'created_at'     => '2019-09-19 12:08:28',
                'updated_at'     => '2019-09-19 12:08:28',
            ],
            [
                'id'             => 3,
                'first_name'           => 'aaric',
                'last_name'           => 'roy',
                'email'          => 'employer@yopmail.com',
                'image'         => 'default-user.jpg',
                'password'       =>Hash::make('Admin@123'),
                'remember_token' => null,
                'created_at'     => '2019-09-19 12:08:28',
                'updated_at'     => '2019-09-19 12:08:28',
            ],
            [
                'id'             => 4,
                'first_name'           => 'peterson',
                'last_name'           => 'roy',
                'email'          => 'recruiter@yopmail.com',
                'image'         => 'default-user.jpg',
                'password'       =>Hash::make('Admin@123'),
                'remember_token' => null,
                'created_at'     => '2019-09-19 12:08:28',
                'updated_at'     => '2019-09-19 12:08:28',
            ],
        ];

        User::insert($users);


    }
}
