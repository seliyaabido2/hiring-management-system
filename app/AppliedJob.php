<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Twilio\TwiML\Messaging\Body;

class AppliedJob extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'applied_jobs';
    public $timestamps = true;

    protected $guarded = [];



    // public function AppliedJobActivestatus(){

    //     return $this->hasOne(CandidateJobStatusComment::class,['candidate_id','candidate_id'])->whereNull('');

    // }

    public function employerJob()
    {
        return $this->belongsTo(EmployerJob::class, 'job_id', 'id');
    }
    public function employerJobs()
    {
        return $this->hasMany(EmployerJob::class, 'id', 'job_id');
    }

    public function SelectedCandidates()
    {
        return $this->hasMany(CandidateJobStatusComment::class, 'candidate_id', 'candidate_id');
    }

    public function AppliedJobActivestatus()
    {

        return $this->hasOne(CandidateJobStatusComment::class, 'candidate_id', 'candidate_id')
            ->where('is_active_status', '1');
    }


    public function getJobDetail()
    {
        return $this->belongsTo(EmployerJob::class, 'job_id', 'id');
    }
    public function assessment_links()
    {
        return $this->hasMany(AssessmentLink::class, 'candidate_id', 'candidate_id');
    }

    public function candidate()
    {

        return $this->belongsTo(Candidate::class, 'candidate_id', 'candidate_id');
    }

    public function bod_candidate()
    {

        return $this->belongsTo(BodCandidate::class,  'candidate_id', 'candidate_id');
    }

    public function singleCandidate()
    {
        return $this->hasOne(Candidate::class, 'candidate_id', 'candidate_id')->whereNull('deleted_at');
    }

    public function bodSingleCandidate()
    {
        return $this->hasOne(BodCandidate::class, 'candidate_id', 'candidate_id')->whereNull('deleted_at');
    }

    public function savedCandidate()
    {
        return $this->hasOne(SavedCandidate::class, 'candidate_id', 'candidate_id')->where('user_id', auth()->user()->id);
    }
}
