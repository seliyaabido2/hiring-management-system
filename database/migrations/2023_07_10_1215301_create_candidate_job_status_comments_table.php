<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class CreateCandidateJobStatusCommentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('candidate_job_status_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('job_id')->nullable();
            $table->foreign('job_id')->references('id')->on('employer_jobs')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('candidate_id')->nullable();
            $table->enum('status', ['None','Shortlist', 'Assessment','Phone Interview','In person Interview','Background Check','Final Selection','Selected','Rejected','Stand By','Candidate not responding (No Response)']);
            $table->enum('field_status', ['None','Selected','Rejected','Stand By','No Response','Skip']);
            $table->enum('is_active_status',['0','1'])->default('0');
            $table->text('additional_note')->nullable();
            $table->text('links')->nullable();

            $table->unsignedBigInteger('applied_job_id')->nullable();
            $table->foreign('applied_job_id')->references('id')->on('applied_jobs')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('job_creator_id')->nullable();
            $table->foreign('job_creator_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->enum('is_bod_candidate_id',['0','1'])->default('0');
            $table->text('Invitee_created_payload')->nullable();
            $table->text('Invitee_canceled_payload')->nullable();
            $table->timestamp('created_at')
            ->useCurrent();


            $table->timestamp('updated_at')
            ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->softDeletes(); // Use softDeletes for deleted_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_job_status_comments');
    }
}
