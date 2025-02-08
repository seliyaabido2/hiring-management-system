<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodCandidateReportLog extends Model
{
    use HasFactory;

    public $table = 'bod_candidate_report_logs';

    protected $guarded = [];

}
