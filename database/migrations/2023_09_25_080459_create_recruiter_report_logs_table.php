<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruiterReportLogsTable extends Migration
{
    public function up()
    {
        Schema::create('recruiter_report_logs', function (Blueprint $table) {
            $table->id();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('job_status')->nullable();
            $table->string('job_title')->nullable();
            $table->string('recruiter_name')->nullable();
            $table->string('candidate_name')->nullable();
            $table->string('round_name')->nullable();
            $table->string('round_status')->nullable();
            $table->text('link')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->change();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recruiter_report_logs');
    }
}
