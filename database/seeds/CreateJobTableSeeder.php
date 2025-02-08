<?php

use App\EmployerJob;
use Illuminate\Database\Seeder;

class CreateJobTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $job = [
            [
                'id'            => 1,
                'user_id'       => 3,
                'job_title'  => 'Laravel',
                'job_description'      => 'Laravel',
                'location'      => 'New York, NY, USA',
                'latitude'      => '40.7127753',
                'longitude'      => '-74.0059728',
                // 'job_qualification'    => 'BCA',
                // 'other_job_qualification'    => NULL,
                'job_requirement_experience'       => 'Freshers',
                'experience_sf'  => 'Yes',
                // 'experience_without_sf'      => 'Yes',
                'license_candidate_basic_training'       => 'Yes',
                // 'license_candidate_no_experience'    => 'Yes',
                'job_type'    => 'Full Time',
                'job_shift'       => 'Work from office',
                // 'working_days_per_week'  => 5,
                'how_many_years_of_experience' => '0-2 years',
                'total_number_of_working_days'      => 5,
                // 'working_day'       => 5,
                // 'bonus_commission'    => 100,
                // 'salary_type'    => 'Hourly Pay',
                'minimum_pay_per_hour'       => 500.00,
                'maximum_pay_per_hour'       => 1000.00,

                // 'pay_day'  => 1000.00,
                'job_benefits'      => '5 days working',
                'any_other_langauge'       => 'Other',
                'other_any_other_langauge'       => 'Hindi',
                'job_role'    => 'Service Focused',
                // 'job_field_role'    => 'Service Focused',
                // 'parking_fee'    => NULL,
                'license_requirement'       => 'Property & Casualty (P&C)',
                // 'other_license_requirement'  => 'PUC',
                'job_start_date'      => '2023-11-17',
                'job_recruiment_duration'       => 100,
                'license_candidate_banking_finance'    => 'Yes',
                // 'parking_free'    => 'Yes',
                'status'       => 'Active',
                'number_of_vacancies'    => 4,
                'additional_information'    => 'this job is testing perpose',
                'created_at'    => '2019-09-19 12:08:28',
                'updated_at'       => '2019-09-19 12:08:28',
            ],
        ];

        EmployerJob::insert($job);
    }
}
