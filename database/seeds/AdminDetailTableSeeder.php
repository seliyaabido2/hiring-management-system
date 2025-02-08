<?php

use App\AdminDetail;
use Illuminate\Database\Seeder;

class AdminDetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin_detail = [
            [
                'id'            => 1,
                'user_id'       => 2,
                'company_name'  => 'ais',
                'company_type'      => 'IT',
                'designation'   => 'BDM',
                'phone_no'      => '0909090909',
                'location'      => 'New York, NY, USA',
                'latitude'      => '40.7127753',
                'longitude'      => '-74.0059728',
                'created_at'    => '2019-09-19 12:08:28',
                'updated_at'    => '2019-09-19 12:08:28',
            ]
        ];

        AdminDetail::insert($admin_detail);

    }
}
