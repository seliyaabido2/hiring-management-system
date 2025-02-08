<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('employer_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->change();
            $table->string('job_title');
            $table->text('job_description');
            $table->string('location')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            // $table->string('job_qualification');
            // $table->string('other_job_qualification')->nullable();
            $table->enum('job_requirement_experience',['Freshers','1+ year','2+ years','3+ years','4+ years','5+ years','More 5+ years']);
            $table->enum('experience_sf', ['Yes', 'No']);
            // $table->enum('experience_without_sf', ['Yes', 'No']);
            $table->enum('license_candidate_basic_training', ['Yes', 'No'])->nullable();
            // $table->enum('license_candidate_no_experience', ['Yes', 'No']);
            $table->enum('job_type', ['Full Time','Part Time']);
            // $table->integer('number_of_days');
            $table->enum('job_shift', ['Work from office', 'Work from home']);
            // $table->integer('working_days_per_week');
            $table->integer('total_number_of_working_days');
            // $table->integer('working_day');
            // $table->enum('bonus_commission', ['Yes', 'No']);

            // $table->enum('salary_type', ['Hourly Pay', 'Monthly Pay'])->nullable();
            $table->decimal('minimum_pay_per_hour', 8, 2);
            $table->decimal('maximum_pay_per_hour', 8, 2);
            // $table->string('pay_day');
            $table->text('job_benefits')->nullable();
            $table->string('any_other_langauge');
            $table->string('other_any_other_langauge')->nullable();
            $table->string('how_many_years_of_experience')->nullable();
            $table->enum('job_role', ['Service Focused', 'Sales Focused', 'Both']);
            // $table->text('job_field_role');
            // $table->decimal('parking_fee', 8, 2)->nullable();
            $table->string('license_requirement')->nullable();
            // $table->string('other_license_requirement')->nullable();
            $table->date('job_start_date');
            $table->string('job_recruiment_duration');
            $table->enum('license_candidate_banking_finance', ['Yes', 'No']);
            // $table->enum('parking_free', ['Yes', 'No']);
            $table->enum('status', ['Active', 'Deactive','Hold','Closed']);
            $table->integer('number_of_vacancies');
            $table->text('additional_information')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->softDeletesTz($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employer_jobs');
    }
};

