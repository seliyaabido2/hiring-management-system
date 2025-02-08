<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidateJobStatusComment extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'candidate_job_status_comments';

    public $timestamps = false;

    protected $guarded = [];

    public function getJobDetail()
    {
        return $this->belongsTo(EmployerJob::class,'job_id','id');

    }
    public function assessment_links()
    {
        return $this->hasMany(AssessmentLink::class,'candidate_id','candidate_id');
    }


    public function candidate()
    {
        return $this->hasMany(Candidate::class,'candidate_id','candidate_id')->whereNull('deleted_at');
    }

    public function singleCandidate()
    {
        return $this->hasOne(Candidate::class,'candidate_id','candidate_id')->whereNull('deleted_at');
    }

    public function getEmployerDetail()
    {
        return $this->belongsTo(User::class,'job_creator_id','id');

    }


    public function singlebodCandidate()
    {
        return $this->hasOne(BodCandidate::class,'candidate_id','candidate_id')->whereNull('deleted_at');
    }



}
