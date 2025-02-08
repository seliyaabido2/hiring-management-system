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

        Schema::create('bod_candidates', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('candidate_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->change();
            $table->string('name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Male','Female','Other'])->nullable();
            $table->string('email')->nullable();
            $table->string('contact_no')->nullable();
            
            // $table->enum('presenting_experience_SF', ['Yes','No'])->nullable();
            $table->string('location')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            $table->enum('presenting_experience_SF', ['Yes','No'])->nullable();
            $table->string('last_month_year_in_sf')->nullable();
    
            $table->string('how_many_experience')->nullable();
            // $table->enum('new_presenting_experience_sf', ['Yes','No'])->nullable();
            
            $table->enum('presently_working_in_sf', ['Yes', 'No'])->nullable();

            $table->enum('job_preference', ['Service Focused', 'Sales Focused', 'Both'])->nullable();
            $table->bigInteger('expected_pay_per_hour')->nullable();
            $table->bigInteger('current_pay_per_hour')->nullable();

            $table->enum('job_type', ['Full Time', 'Part Time'])->nullable();
            
            // $table->string('pincode')->nullable();
            $table->enum('experience_sf', ['Yes', 'No']);
            // $table->enum('experience_without_sf', ['Yes', 'No']);
            $table->enum('license_candidate_basic_training', ['Yes', 'No'])->nullable();
            // $table->enum('license_candidate_no_experience', ['Yes', 'No']);
            $table->enum('license_candidate_banking_finance', ['Yes', 'No'])->nullable();
            $table->string('any_other_langauge')->nullable();
            $table->string('other_any_other_langauge')->nullable(); 
            $table->string('license_requirement')->nullable();
            // $table->string('other_license_requirement')->nullable();
            $table->enum('reference_check', ['Yes', 'No'])->nullable();
            $table->text('additional_information')->nullable();
            $table->text('resume')->nullable();
            $table->enum('status', ['Active', 'Deactive']);
            $table->integer('is_saved')->default(0);

            $table->timestamps();
            $table->softDeletesTz($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bod_candidates');
    }
};
