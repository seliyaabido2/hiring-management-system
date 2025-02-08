<?php

use App\AssignedContract;
use Illuminate\Database\Seeder;

class AssignContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assignedContract = [
            [
                'id'            => 1,
                'contract_id'       => 1,
                'user_id'  => 3,
                'contract_upload'      =>  'ContractSample.pdf',
                'checklist_upload'      =>  'ContractSample.pdf',
                'start_date'       => '2023-01-01',
                'end_date'       => '2023-12-31',
                'status'       => 'Active',
                'created_at'    => '2019-09-19 12:08:28',
                'updated_at'    => '2019-09-19 12:08:28',
            ],
            [
                'id'            => 2,
                'contract_id'       => 2,
                'user_id'  => 4,
                'contract_upload'      =>  'ContractSample.pdf',
                'checklist_upload'      =>  null,
                'start_date'       => '2023-01-01',
                'end_date'       => '2023-12-31',
                'status'       => 'Active',
                'created_at'    => '2019-09-19 12:08:28',
                'updated_at'    => '2019-09-19 12:08:28',
            ]
        ];

        AssignedContract::insert($assignedContract);

    }
}
