<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class City extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getStateDetails()
    {
        return $this->belongsTo(State::class,'state_id','id');
    }

}
