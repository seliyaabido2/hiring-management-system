<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'candidates';

    protected $guarded = [];


    public function remaingApplyJob()
    {
        return $this->hasOne(AppliedJob::class,'candidate_id' ,'candidate_id');
    }

    public function appliedJobs()
    {
        return $this->hasMany(AppliedJob::class, 'candidate_id', 'candidate_id');
    }

    public function savedCandidate()
    {
        return $this->hasOne(SavedCandidate::class,'candidate_id','candidate_id')->where('user_id',auth()->user()->id);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($parentModel) {
            $parentModel->appliedJobs()->delete(); // Soft delete related records

        });
    }

}
