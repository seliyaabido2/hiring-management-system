<?php

use App\Contract;
use Illuminate\Database\Seeder;

class ContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contract = [
            [
                'id'            => 1,
                'name'       => 'BOD Contract',
                'description'  => '',
                'related_to'      => 'Employer',
                'expire_alert'       => '2',
                'created_at'    => '2019-09-19 12:08:28',
                'updated_at'    => '2019-09-19 12:08:28',
            ],
            [
                'id'            => 2,
                'name'       => 'Recruitment Contract',
                'description'  => '',
                'related_to'      => 'Employer',
                'expire_alert'       => '2',
                'created_at'    => '2019-09-19 12:08:28',
                'updated_at'    => '2019-09-19 12:08:28',
            ],[
                'id'            => 3,
                'name'       => 'Training Contract',
                'description'  => '',
                'related_to'      => 'Employer',
                'expire_alert'       => '2',
                'created_at'    => '2019-09-19 12:08:28',
                'updated_at'    => '2019-09-19 12:08:28',
            ],[
                'id'            => 4,
                'name'       => 'Recruitment Contract',
                'description'  => '',
                'related_to'      => 'Recruiter',
                'expire_alert'       => '2',
                'created_at'    => '2019-09-19 12:08:28',
                'updated_at'    => '2019-09-19 12:08:28',
            ]
        ];

        Contract::insert($contract);

    }
}
