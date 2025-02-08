<?php

use App\RecruiterDetail;
use Illuminate\Database\Seeder;

class RecruiterDetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recruiter_detail = [
            [
                'id'            => 1,
                'user_id'       => 4,
                'company_name'  => 'ais',
                'authorized_name'  => 'Jackson',
                'phone_no'      => '9898989898',
                'location'      => 'New York, NY, USA',
                'latitude'      => '40.7127753',
                'longitude'      => '-74.0059728',
                'created_at'    => '2019-09-19 12:08:28',
                'updated_at'    => '2019-09-19 12:08:28',
            ]
        ];

        RecruiterDetail::insert($recruiter_detail);

    }
}
