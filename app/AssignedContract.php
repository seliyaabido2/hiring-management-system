<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class AssignedContract extends Model
{
    use SoftDeletes,HasFactory;

    public $table = 'assign_contracts';

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'user_id',
        'contract_id',
        'contract_upload',
        'checklist_upload',
        'recurring_contracts',
        'start_date',
        'end_date',
        'status',
    ];


    public function ContractDetail()
    {
        return $this->belongsTo(Contract::class,'contract_id','id');
    }


}
