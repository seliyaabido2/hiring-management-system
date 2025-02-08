<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Route::get('add_city', function (){


   $states = DB::table('states')->where('country_id',233)->get();

   foreach($states as $state){

      $cities = DB::table('cities')->where('state_id',$state->id)->get();

      if($cities->count() > 0)
      {
        foreach($cities as $city){
             $county_state_cities = [
                'country' => 'United States',
                'state' => $state->name,
                'city' => $city->name
             ];

         DB::table('county_state_cities')->insert($county_state_cities);

        }
      }
      else{
        $county_state_cities = [
            'country' => 'United States',
            'state' => $state->name,
         ];

        DB::table('county_state_cities')->insert($county_state_cities);


      }



   }

   echo 'Done';
   die;

});


Route::redirect('/', '/login');
Route::redirect('/home', '/admin');
Auth::routes(['register' => false]);

Route::post('forget-mail-send','Admin\ForgotPasswordController@sendResetLinkEmail')->name('forget-mail-send');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth','checkUserStatus']], function () {


    Route::get('/test', 'ReportController@test')->name('test');

    Route::get('/send/mail', 'SendMailController@send_mail')->name('send_mail');

    Route::get('/', 'HomeController@index')->name('home');


    // Permissions
    Route::delete('permissions/massDestroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/massDestroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::get('users/profile-update', 'UsersController@profileEdit')->name('users.profile.edit');
    Route::post('users/profile-update','UsersController@profileUpdate')->name('users.profile.update');
    Route::get('users/change-password', 'UsersController@showChangePasswordForm')->name('users.password.change');
    Route::post('users/change-password', 'UsersController@changePassword')->name('users.password.update');

    // state and city
    Route::post('users/getState','UsersController@getState')->name('users.getState');
    Route::post('users/getCity','UsersController@getCity')->name('users.getCity');

    Route::get('google-autocomplete','UsersController@GoogleAutocomplete')->name('users.google-autocomplete');


    Route::post('users/add-calendly-user', 'CalendlyController@addCalendlyUser')->name('users.addCalendly');
    Route::get('users/get-calendly-url/{appliedJobId}/{status}', 'CalendlyController@getCalendlyUrl')->name('users.getCalendlyUrl');


    Route::delete('users/massDestroy', 'UsersController@massDestroy')->name('users.massDestroy');

    Route::get('users/viewContract', 'UsersController@viewContract')->name('users.viewContract');

    Route::resource('users', 'UsersController');

    // employer
    
    Route::get('employer/getEmployerDatatable','EmployerController@getEmployerDatatable')->name('employer.getEmployerDatatable');

    Route::resource('employer', 'EmployerController');

    // Recruiter
    Route::get('recruiter/getRecruiterPartnerDatatable','RecruiterPartnerController@getRecruiterPartnerDatatable')->name('recruiter.getRecruiterPartnerDatatable');

    Route::resource('RecruiterPartner', 'RecruiterPartnerController');

     // Sub Admin
    Route::get('SubAdmin/getSubAdminDatatable','SubAdminController@getSubAdminDatatable')->name('SubAdmin.getSubAdminDatatable');
     Route::resource('SubAdmin', 'SubAdminController');

    Route::post('/check-email-unique-employer', 'EmployerController@checkEmailUniqueEmployer')->name('check-email-unique-employer');
    Route::post('/check-email-unique-recruiter', 'RecruiterPartnerController@checkEmailUniqueRecruiter')->name('check-email-unique-recruiter');

    Route::post('/check-email-unique-subAdmin', 'SubAdminController@checkEmailUniqueAdmin')->name('check-email-unique-subAdmin');

    Route::post('/check-email-unique-candidate', 'SubAdminController@checkEmailUniqueCandidate')->name('check-email-unique-candidate');

    // employerjobs

    Route::post('employerJobs/checkUniqueEmailOrContact', 'EmployerJobController@checkUniqueEmailOrContact')->name('employerJobs.checkUniqueEmailOrContact');



    Route::get('candidate/redirect-to-candidate-view/{id}', 'BodCandidateController@RedirectCandidateView')->name('candidate.redirect-to-candidate-view');

    Route::get('employerJobs/bodcreateJob', 'EmployerJobController@createJob')->name('employerJobs.bodcreateJob');
    Route::get('employerJobs/createJob', 'EmployerJobController@createJob')->name('employerJobs.createJob');
    Route::get('employerJobs/myjob', 'EmployerJobController@myjob')->name('employerJobs.myjob');
    Route::get('employerJobs/requestJobs', 'EmployerJobController@requestJobs')->name('employerJobs.requestJobs');
    Route::get('employerJobs/status/activeJobs', 'EmployerJobController@activeJobs')->name('employerJobs.activeJobs');
    Route::get('employerJobs/status/bodactiveJobs', 'EmployerJobController@activeJobs')->name('employerJobs.bodactiveJobs');

    Route::get('employerJobs/status/DeActiveJobs', 'EmployerJobController@DeActiveJobs')->name('employerJobs.DeActiveJobs');
    Route::get('employerJobs/status/closedJobs', 'EmployerJobController@closedJobs')->name('employerJobs.closedJobs');

    Route::get('employerJobs/createExitJob', 'EmployerJobController@createExitJob')->name('employerJobs.createExitJob');
    Route::get('employerJobs/mySavedJobTemplates', 'EmployerJobController@mySavedJobTemplates')->name('employerJobs.mySavedJobTemplates');
    Route::get('employerJobs/alljobs', 'EmployerJobController@alljobs')->name('employerJobs.alljobs');
    Route::get('employerJobs/hiredJobsCandidates', 'EmployerJobController@hiredJobsCandidates')->name('employerJobs.hiredJobsCandidates');
    Route::get('employerJobs/hiredCandidates', 'EmployerJobController@hiredCandidates')->name('employerJobs.hiredCandidates');

    Route::get('employerJobs/chageJobRequestStatus/{jobId}/{status}', 'EmployerJobController@chageJobRequestStatus')->name('employerJobs.chageJobRequestStatus');
    Route::get('employerJobs/candidateDatatabes','EmployerJobController@candidateDatatabes')->name('employerJobs.candidateDatatabes');
    Route::get('employerJobs/bulk-apply-job', 'EmployerJobController@bulkApplyJob')->name('employerJobs.bulkApplyJob');
    Route::get('employerJobs/bulk-apply-job-with-candidate', 'EmployerJobController@bulkApplyJobByCandidate')->name('employerJobs.bulkApplyJobByCandidate');
    Route::post('employerJobs/bulk-post-apply-job', 'EmployerJobController@bulkPostApplyJob')->name('employerJobs.bulkPostApplyJob');

    Route::get('employerJobs/ActiveJobDatatable','EmployerJobController@ActiveJobDatatable')->name('employerJobs.ActiveJobDatatable');

    Route::get('employerJobs/apply-job/{id}', 'EmployerJobController@applyJob')->name('employerJobs.applyJob');
    Route::post('employerJobs/post-apply-job', 'EmployerJobController@postApplyJob')->name('employerJobs.postApplyJob');
    Route::resource('employerJobs', 'EmployerJobController');
    Route::get('appliedJobs/candidatesViewJobStatus/{id}', 'AppliedJobsController@candidatesViewJobStatus')->name('appliedJobs.candidatesViewJobStatus');
    Route::post('appliedJobs/candidatesChangeJobStatus', 'AppliedJobsController@candidatesChangeJobStatus')->name('appliedJobs.candidatesChangeJobStatus');
    Route::post('appliedJobs/candidatesChangeJobStatusAction', 'AppliedJobsController@candidatesChangeJobStatusAction')->name('appliedJobs.candidatesChangeJobStatusAction');
    Route::post('appliedJobs/singleUpdate', 'AppliedJobsController@singleUpdate')->name('appliedJobs.singleUpdate');
    Route::get('appliedJobs/savedCandidate', 'AppliedJobsController@savedCandidate')->name('appliedJobs.savedCandidate');

    Route::get('recentStatusUpdates/appliedJobs', 'AppliedJobsController@recentStatusUpdates')->name('appliedJobs.recentStatusUpdates');
    Route::get('recentStatusUpdates/getRecentStatusUpdatesDatatable','AppliedJobsController@getRecentStatusUpdatesDatatable')->name('appliedJobs.getRecentStatusUpdatesDatatable');

    
    Route::get('appliedJobs/savedJobTemplate', 'AppliedJobsController@savedJobTemplate')->name('appliedJobs.savedJobTemplate');
    Route::get('appliedJobs/unSavedJobTemplate', 'AppliedJobsController@unSavedJobTemplate')->name('appliedJobs.unSavedJobTemplate');

    Route::post('appliedJobs/rangeFilter', 'AppliedJobsController@rangeFilter')->name('appliedJobs.rangeFilter');

    Route::resource('appliedJobs', 'AppliedJobsController');

    Route::delete('appliedJobs/massDestroy', 'AppliedJobsController@massDestroy')->name('appliedJobs.massDestroy');


    Route::post('appliedJobs/candidateFieldChangeStatus', 'AppliedJobsController@candidateFieldChangeStatus')->name('appliedJobs.candidateFieldChangeStatus');

    Route::post('appliedJobs/candidateSkipFieldChangeStatus', 'AppliedJobsController@candidateSkipFieldChangeStatus')->name('appliedJobs.candidateSkipFieldChangeStatus');

    Route::get('bodAppliedJobs/candidatesViewJobStatus/{id}', 'BodAppliedJobsController@candidatesViewJobStatus')->name('bodAppliedJobs.candidatesViewJobStatus');
    Route::post('bodAppliedJobs/candidatesChangeJobStatus', 'BodAppliedJobsController@candidatesChangeJobStatus')->name('bodAppliedJobs.candidatesChangeJobStatus');
    Route::post('bodAppliedJobs/candidatesChangeJobStatusAction', 'BodAppliedJobsController@candidatesChangeJobStatusAction')->name('bodAppliedJobs.candidatesChangeJobStatusAction');
    Route::post('bodAppliedJobs/singleUpdate', 'BodAppliedJobsController@singleUpdate')->name('bodAppliedJobs.singleUpdate');
    Route::resource('bodAppliedJobs', 'BodAppliedJobsController');
    Route::delete('bodAppliedJobs/massDestroy', 'BodAppliedJobsController@massDestroy')->name('bodAppliedJobs.massDestroy');

    Route::post('bodAppliedJobs/candidateFieldChangeStatus', 'BodAppliedJobsController@candidateFieldChangeStatus')->name('bodAppliedJobs.candidateFieldChangeStatus');

    Route::post('bodAppliedJobs/candidateSkipFieldChangeStatus', 'BodAppliedJobsController@candidateSkipFieldChangeStatus')->name('bodAppliedJobs.candidateSkipFieldChangeStatus');

    Route::get('candidate/savedCandidates', 'CandidateController@savedCandidates')->name('candidate.savedCandidates');

    Route::get('candidate/savedCandidate', 'CandidateController@savedCandidate')->name('candidate.savedCandidate');
    Route::get('candidate/unSavedCandidate', 'CandidateController@unSavedCandidate')->name('candidate.unSavedCandidate');

    Route::get('candidate/getCandidateDatatable','CandidateController@getCandidateDatatable')->name('candidate.getCandidateDatatable');

    Route::delete('candidate/massDestroy', 'CandidateController@massDestroy')->name('candidate.massDestroy');
    Route::resource('candidate', 'CandidateController');
    
    
    Route::get('candidateSelected', 'CandidateController@CandidateSelected')->name('candidate.candidateSelected');

    Route::post('validate-unique-item', 'CommonController@ValidateUniqueItem')->name('validate-unique-item');


    Route::resource('cmsPages', 'PageController');

    Route::resource('contracts', 'ContractController');

    Route::post('employerReports/getJobs', 'EmployerReportController@getJobs')->name('employerReports.getJobs');
    Route::resource('employerReports', 'EmployerReportController');
    Route::post('recruiterReports/getJobs', 'RecruiterReportController@getJobs')->name('recruiterReports.getJobs');
    Route::resource('recruiterReports', 'RecruiterReportController');

    Route::post('employerJobs/apply-jobReports/getJobs', 'BodCandidateReportController@getJobs')->name('bodCandidateReports.getJobs');
    Route::resource('bodCandidateReports', 'BodCandidateReportController');

    Route::get('bodCandidate/savedCandidate', 'BodCandidateController@savedCandidate')->name('bodCandidate.savedCandidate');
    Route::get('bodCandidate/unSavedCandidate', 'BodCandidateController@unSavedCandidate')->name('bodCandidate.unSavedCandidate');

    Route::get('bodCandidate/savedCandidates', 'BodCandidateController@savedCandidates')->name('bodCandidate.savedCandidates');

    Route::get('bodCandidate/bod_bulk_candidate', 'BodCandidateController@bodBulkCandidate')->name('bodCandidate.bod_bulk_candidate');
    Route::post('bodCandidate/storeBODBulkCandidate', 'BodCandidateController@storeBODBulkCandidate')->name('bodCandidate.storeBODBulkCandidate');


    Route::post('bodCandidate/edit/{bodCandidate}', 'BodCandidateController@edit')->name('admin.bodCandidate.edit');

    Route::get('bodCandidate/getBodCandidateDatatable','BodCandidateController@getBodCandidateDatatable')->name('bodCandidate.getBodCandidateDatatable');

    Route::resource('bodCandidate', 'BodCandidateController');

    
    Route::get('notifications/getNotificationDatatable','NotificationController@getNotificationDatatable')->name('notifications.getNotificationDatatable');


    Route::get('notifications/markAsRead/{id}', 'NotificationController@markAsRead')->name('notifications.markAsRead');
    Route::delete('notifications/massDestroy', 'NotificationController@massDestroy')->name('notifications.massDestroy');
    Route::resource('notifications', 'NotificationController');

    // Route::get('resumeFetchData/markAsRead/{id}', 'NotificationController@markAsRead')->name('resumeFetchData.markAsRead');
    // Route::delete('resumeFetchData/massDestroy', 'NotificationController@massDestroy')->name('resumeFetchData.massDestroy');
    Route::post('resumeFetchData/upload', 'ResumeFetchController@upload')->name('resumeFetchData.upload');
    Route::resource('resumeFetchData', 'ResumeFetchController');


});
