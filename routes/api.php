<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\AuthApiController;
use App\Http\Controllers\Api\V1\Admin\EmployerJobController;
use App\Http\Controllers\Api\V1\Admin\AppliedJobsController;
use App\Http\Controllers\Api\V1\Admin\CandidateController;
use App\Http\Controllers\Api\V1\Admin\PageController;



Route::post('calendly-schedule-payload','Api\V1\Admin\CalendlyController@calendlySchedulePayload');
Route::post('calendlycreate','Api\V1\Admin\CalendlyController@createEvent');
Route::post('/login', 'Api\V1\Admin\AuthApiController@login');
Route::post('forget-password','Api\V1\Admin\AuthApiController@forgetPasswordMailsend')->name('forget-password-api');


Route::middleware(['checkAccess','auth:api'])->group(function () {

    Route::controller(AuthApiController::class)->group(function () {

        Route::post('/logout', 'logout')->name('logout-api');
        Route::get('/profile', 'getProfile')->name('profile');
        Route::post('/profile-update', 'profileUpdate')->name('profile-update');
        Route::post('/change-password', 'passwordChange')->name('change-password');


    });

    Route::controller(EmployerJobController::class)->group(function () {

        Route::post('employerJobs/apply-job', 'applyJob')->name('employerJobs.apply-job');

        Route::post('employerJobs/create', 'create')->name('employerJobs.create');
        Route::get('employerJobs/my-job', 'myJob')->name('employerJobs.my-job');
        Route::get('employerJobs/job-list', 'jobList')->name('employerJobs.job-list');
        Route::post('employerJobs/update', 'update')->name('employerJobs.update');
        Route::post('employerJobs/delete', 'delete')->name('employerJobs.delete');

    });

    Route::controller(AppliedJobsController::class)->group(function () {

        Route::post('appliedJobs','index')->name('appliedJobs.view');
        Route::post('appliedCandidates','show')->name('appliedCandidates.view');


    });

    Route::controller(CandidateController::class)->group(function () {

        Route::get('candidate-list','index')->name('candidate.view');
        Route::post('create-candidate','store')->name('candidate.create');
        Route::post('update-candidate','update')->name('candidate.update');
        Route::post('delete-candidate','destroy')->name('candidate.delete');

    });

    Route::controller(PageController::class)->group(function () {

        Route::get('cmsPages-list','index')->name('cmsPages.view');
        // Route::post('create-cmsPage','store')->name('cmsPage.create');
        // Route::post('update-cmsPage','update')->name('cmsPage.update');
    });

    


});
