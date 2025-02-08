<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentLink extends Model
{
    use HasFactory;

    public $table = 'assessment_links';

    protected $guarded = [];

}
