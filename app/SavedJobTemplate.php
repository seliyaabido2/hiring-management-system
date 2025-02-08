<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SavedJobTemplate extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'saved_job_templates';
    public $timestamps = true;

    protected $guarded = [];

    public function employerJob()
    {
        return $this->belongsTo(EmployerJob::class, 'job_id', 'id');
    }

}
