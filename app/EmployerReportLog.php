<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployerReportLog extends Model
{
    use HasFactory;

    public $table = 'employer_report_logs';

    protected $guarded = [];

}
