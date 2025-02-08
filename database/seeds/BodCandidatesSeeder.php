<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class BodCandidatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100000; $i++) {
            DB::table('bod_candidates')->insert([
                'candidate_id' => mt_rand(100000000000, 999999999999), // Adjust the range as needed
                'user_id' => 1,
                'name' => $faker->name,
                'date_of_birth' => $faker->date,
                'gender' => 'Male',
                'email' => $faker->unique()->safeEmail,
                'contact_no' => $faker->phoneNumber,
                'location' => $faker->city . ', ' . $faker->state . ', ' . $faker->country,
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'presenting_experience_SF' => null,
                'how_many_experience' => null,
                'presently_working_in_sf' => null,
                'job_preference' => 'Full Time',
                'expected_pay_per_hour' => 10,
                'job_type' => 'Service Focused',
                'experience_sf' =>  'No',
                'license_candidate_basic_training' => 'Yes',
                'license_candidate_banking_finance' => 'Yes',
                'any_other_langauge' => 'Spanish',
                'other_any_other_langauge' => null,
                'license_requirement' => 'Property & Casualty (P&C)',
                'additional_information' => null,
                'resume' => $faker->word . '.pdf',
                'status' => 'Active',
                'is_saved' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'candidate_job_slug' => str_replace('-', '', $faker->slug),
            ]);
        }

    }
}
