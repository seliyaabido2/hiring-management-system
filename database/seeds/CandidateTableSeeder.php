<?php

use Illuminate\Database\Seeder;
use App\Candidate;

class CandidateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Adjust the loop limit based on the number of entries you want (e.g., 10,000)
        for ($i = 1; $i <= 10; $i++) {
            DB::table('candidates')->insert([
                'id' => $i,
                'candidate_id' => 18768868767878 + $i, // Adjust the starting candidate_id as needed
                'name' => 'Aaric' . $i,
                'email' => 'test' . $i . '@gmail.com',
                'contact_no' => 1111111111 + $i,
                'gender' => 'Male',
                'date_of_birth' => '1999-06-06',
                'experience_sf' => 'No',
                'any_other_langauge' => 'spanish',
                'license_candidate_banking_finance' => 'Yes',
                'job_preference' => 'Both',
                'license_candidate_basic_training' => 'Yes',
                'license_requirement' => 'Property & Casualty (P&C)',
                'expected_pay_per_hour' => 10,
                'job_type' => 'Full Time',
                'resume' => 'test.cv',
                'location' => 'New York, NY, USA',
                'latitude' => '40.7127753',
                'longitude' => '-74.0059728',
                'updated_at' => '2019-09-19 12:08:28',
                'user_id' => 4,
                'candidate_job_slug' => createSlug('test' . $i . '@gmail.com' . (1111111111 + $i)),
                'created_at' => '2019-09-19 12:08:28',
                'updated_at' => '2019-09-19 12:08:28',
            ]);
        }
    }
}
