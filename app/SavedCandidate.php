<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedCandidate extends Model
{
    use HasFactory;

    public $table = 'saved_candidates';

    protected $guarded = [];


    public function candidate()
    {

        return $this->belongsTo(Candidate::class , 'candidate_id', 'candidate_id');

    }

    public function bod_candidate()
    {

        return $this->belongsTo(BodCandidate::class,  'candidate_id', 'candidate_id');

    }
}
