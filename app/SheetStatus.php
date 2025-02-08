<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SheetStatus extends Model
{
    use HasFactory;

    public $table = 'candidate_sheet_status';

    protected $fillable = [
        'sheet_name',
        'status',
    ];

}
