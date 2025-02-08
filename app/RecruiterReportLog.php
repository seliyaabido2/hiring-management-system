<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruiterReportLog extends Model
{
    use HasFactory;

    public $table = 'recruiter_report_logs';

    protected $guarded = [];

}
