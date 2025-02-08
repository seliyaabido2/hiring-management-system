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


        Schema::create('applied_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id')->nullable();
            $table->foreign('job_id')->references('id')->on('employer_jobs')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('candidate_id')->nullable();
            $table->unsignedBigInteger('job_creator_id')->nullable();
            $table->foreign('job_creator_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', ['None','Shortlist', 'Assessment','Phone Interview','In person Interview','Background Check','Selected','Rejected','Stand By','Candidate not responding (No Response)']);
            $table->integer('is_bod_candidate_id')->default(0);
            $table->timestamps();
            $table->softDeletesTz($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applied_jobs');
    }
};
