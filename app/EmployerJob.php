<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\SavedJobTemplate;

class EmployerJob extends Model
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;
    public $table = 'employer_jobs';
    public $timestamps = true;

    protected $guarded = [];


    public function appliedJobs()
    {
        return $this->hasMany(AppliedJob::class, 'job_id', 'id');
    }
    public function hiredCandidates()
    {

        return $this->hasMany(CandidateJobStatusComment::class, 'job_id')
        ->where(['status' => 'Final Selection', 'field_status' => 'Selected']);

    }

    public function AppliedJosCandidates()
    {
        return $this->hasMany(AppliedJob::class, 'job_id');
    }



    public function savedJobTemplate()
    {
        return $this->belongsTo(SavedJobTemplate::class, 'id', 'job_id')->where('user_id',auth()->user()->id);
    }

    public function candidateJobStatusComment()
    {
        return $this->hasMany(CandidateJobStatusComment::class, 'job_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($parentModel) {
            $parentModel->savedJobTemplate()->delete();
            $parentModel->appliedJobs()->delete(); // Soft delete related records
            $parentModel->candidateJobStatusComment()->delete();
        });
    }


}
