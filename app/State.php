<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class State extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function getcountryDetails()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }
}
