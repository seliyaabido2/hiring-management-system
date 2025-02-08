<?php

use App\EmployerDetail;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployerDetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employer_detail = [
            [
                'id'            => 1,
                'user_id'       => 3,
                'company_name'  => 'ais',
                'authorized_name' => 'ais',
                'phone_no'      => '9898989898',
                'location'      => 'New York, NY, USA',
                'latitude'      => '40.7127753',
                'longitude'      => '-74.0059728',
                'created_at'    => '2019-09-19 12:08:28',
                'updated_at'    => '2019-09-19 12:08:28',
            ]
        ];

        EmployerDetail::insert($employer_detail);

    }


}
