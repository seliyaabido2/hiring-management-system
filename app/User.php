<?php

namespace App;

use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\EmployeeDetail;
use PhpParser\Node\Expr\Assign;
use App\Notifications\CustomResetPasswordNotification;


class User extends Authenticatable
{
    use SoftDeletes, Notifiable, HasApiTokens, HasFactory;

    public $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'email_verified_at',
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'image',
        'email',
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'access_token',
        'email_verified_at',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        
        $this->notify(new CustomResetPasswordNotification($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function EmployerDetail()
    {

        return $this->belongsTo(EmployerDetail::class,'id','user_id');
    }

    public function RecruiterDetail()
    {
        return $this->belongsTo(RecruiterDetail::class,'id','user_id');
    }

    public function AdminDetail()
    {
        return $this->belongsTo(AdminDetail::class,'id','user_id');
    }

    public function AssignedContractDetail()
    {
        return $this->hasMany(AssignedContract::class, 'user_id', 'id');
    }

    public function AssignedOneContractDetail()
    {
        return $this->belongsTo(AssignedContract::class,'id','user_id');
    }


    // public function jobPosts(){

    //     return $this->belongsTo(EmployerJob::class,'id','user_id');

    // }

    public function RecruiterCandidates()  {

        return $this->hasMany(Candidate::class, 'user_id');
    }


    public function jobPosts()
    {
        return $this->hasMany(EmployerJob::class, 'user_id');
    }

    public function HiredCandidatesCount()
    {

        // dd($this->id);
      return  CandidateJobStatusComment::join('employer_jobs', 'employer_jobs.id', '=', 'candidate_job_status_comments.job_id')
            ->where('employer_jobs.user_id', 1)
            ->where(['candidate_job_status_comments.status' => 'Final Selection', 'candidate_job_status_comments.field_status' => 'Selected'])->count();
    }

    public function candidateJobStatusComments()
    {
        return $this->hasMany(CandidateJobStatusComment::class, 'user_id', 'id');
    }
}


