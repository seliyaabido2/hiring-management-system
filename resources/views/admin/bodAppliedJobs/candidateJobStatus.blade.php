@extends('layouts.admin')

@section('styles')
<style>
    .navigation_menu {

        margin-top: 5px;
    }

    .action_status a {
        color: skyblue !important;
    }

    .tab_inactive-title {
        font-weight: 800;
        font-size: 16px;
        margin-bottom: 4px
    }

    .navigation_tabs {
        counter-reset: step;
        position: relative;
        padding-left: 45px;
        list-style: none;
    }

    .navigation_tabs::before {
        display: inline-block;
        content: '';
        position: absolute;
        top: 0;
        left: 18px;
        width: 10px;
        height: 100%;
        border-left: 2px solid #CCC;
    }

    .navigation_menu ul {
        list-style-type: none;
        padding-right: 0;
        margin-top: 0;
        margin-left: 0;
        margin-right: 0px;
        margin-bottom: 0;
    }

    .navigation_menu li {
        position: relative;
        counter-increment: list;
    }

    .navigation_menu li:before {
        display: inline-block;
        content: '';
        position: absolute;
        left: -30px;
        height: 100%;
        width: 10px;
    }

    .navigation_menu li:after {
        content: counter(step);
        counter-increment: step;
        display: inline-block;
        position: absolute;
        top: 0;
        left: -39px;
        width: 25px;
        height: 25px;
        line-height: 26px;
        border: 1px solid #DDD;
        border-radius: 50%;
        background-color: #FFF;
        display: block;
        text-align: center;
        margin: 0 auto 10px auto;
    }

    .navigation_menu li:not(:last-child) {
        padding-bottom: 25px;
    }

    .navigation_menu li.tab_inactive:before {
        border-left: 3px solid green;
        margin-left: 3px;
    }

    .navigation_menu li.tab_active:after {
        border: 1px solid green;
    }

    .navigation_menu li.tab_inactive:after {
        content: "\2713";
        font-size: 20px;
        color: #FFF;
        text-align: center;
        border: 1px solid green;
        background-color: green;
    }


    .navigation_menu li.tab_rejected:before {
        border-left: 3px solid red;
        margin-left: 3px;
    }



    .navigation_menu li.tab_rejected:after {
        content: "\f00d";
        font-size: 15px;
        color: #FFF;
        text-align: center;
        border: 1px solid red;
        background-color: red;
        font-family: 'Font Awesome 5 Free';
        font-weight: 600;
    }

    .navigation_tabs li a,
    .navigation_tabs li a {
        display: block;
        padding-top: 4px;
        text-decoration: none;
        color: #000;
    }

    .navigation_tabs li.tab_inactive a {
        color: #000;
    }

    .navigation_tabs li.tab_disabled a {
        pointer-events: none;
        cursor: default;
        text-decoration: none;
    }

    /* .navigation_tabs li.tab_active a:hover,
                                    .navigation_tabs li.tab_inactive a:hover {
                                    font-weight: bold;
                                    } */
    #regularAccordionRobots .accordion-button::after {
        flex-shrink: 0;
        width: 1rem;
        height: 1rem;
        background-size: 1rem;
    }

    #regularAccordionRobots .accordion-button:focus {

        box-shadow: none;
    }

    #regularAccordionRobots .accordion-body {
        height: 100px;
        overflow-y: scroll;
        border: none;
    }

    #regularAccordionRobots ::-webkit-scrollbar {
        width: 3px;
    }

    /* Track */
    #regularAccordionRobots ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    #regularAccordionRobots ::-webkit-scrollbar-thumb {
        background: #888;
    }

    /* Handle on hover */
    #regularAccordionRobots ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    #regularAccordionRobots div#regularCollapseSecond {
        padding: 10px;
        padding-: 10px;
    }
</style>


<link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">
<script src="https://assets.calendly.com/assets/external/widget.js" type="text/javascript" async></script>
@endsection

@section('content')


    <div class="card mb-3">

        {{-- candidate information header --}}
        <div class="row bg-green mx-0">
            <div class="col-6">
                <div class="p-2 text-center text-white text-lg  rounded-top"><span class="text-uppercase">Job Title -
                    </span><span class="text-medium">{{ $appliedJobData->getJobDetail->job_title }}</span>
                </div>
                <div class="p-2 text-center text-white text-lg bg-green rounded-top"><span class="text-uppercase">Candidate Name -
                    </span><span class="text-medium candidateName">{{ $appliedJobData->bodSingleCandidate->name }}</span>
                </div>
            </div>
            <div class="col-6">
                <div class="p-2 text-center text-white text-lg bg-green rounded-top">
                    <span class="text-uppercase">Candidate Id -</span><span class="text-medium">{{ $appliedJobData->candidate_id }}</span>
                </div>
                <div class="p-2 text-center text-white text-lg bg-green rounded-top">
                    <span class="text-uppercase">Email Id -</span>
                    <span class="text-medium">{{ $appliedJobData->bodSingleCandidate->email }}</span>
                </div>
            </div>
        </div>

        {{-- ATS system --}}


        <div class="card-body">
            <div class="navigation_menu" id="navigation_menu">
                <ul class="navigation_tabs" id="navigation_tabs">
                    @php
                        $userId = auth()->user()->id;
                        $getUserRole = getUserRole(auth()->user()->id);
                        $employer_calendly =false;
                        $employer_schedule_url=null;

                        if(!empty($employerdetails) && !empty($employerdetails->calendly_invitation)){
                            $calendly_invitation = json_decode($employerdetails->calendly_invitation);
                            if($calendly_invitation->status == 'accepted'){
                                $employer_calendly =true;
                            }

                             if(!empty($employerdetails->calendly_details)){
                                $calendly_details = json_decode($employerdetails->calendly_details);
                                $employer_schedule_url=$calendly_details->user->scheduling_url;
                            }

                        }


                        $statuses = ['None', 'Assessment','Shortlist', 'Phone Interview', 'In person Interview', 'Background Check', 'Final Selection'];

                        $activestatuses = $candidateJobStatusComment->whereNotIn('field_status',['None','Stand By','No Response'])->pluck('status')->toArray();
                        $candidateJobStatusCommentCnt=$candidateJobStatusComment->count();
                        $inviteeCreatedPayload = null;
                        $inviteeCancelPayload = null;

                    @endphp

                    @foreach ($candidateJobStatusComment as $key => $statusitem)

                        @php
                            $inviteeCreatedPayload = null;
                            $inviteeCancelPayload = null;

                            if(!empty($statusitem->Invitee_created_payload) && ($statusitem->status == 'Assessment' || $statusitem->status == 'Phone Interview' || $statusitem->status == 'In person Interview')){
                            $inviteeCreatedPayload = json_decode($statusitem->Invitee_created_payload,true);


                            }
                            if(!empty($statusitem->Invitee_canceled_payload) && ($statusitem->status == 'Assessment' || $statusitem->status == 'Phone Interview' || $statusitem->status == 'In person Interview')){

                            $inviteeCancelPayload = json_decode($statusitem->Invitee_canceled_payload,true);

                            }



                            // $InviteeCreatedPayload $appliedJobData->Invitee_created_payload
                        @endphp

                        {{-- none OR Profile Received --}}

                        @if($statusitem->status =='None')
                            <li class="tab_inactive">
                                <h6><b>Profile Received</b> <button class="btn btn-xs btn-success" onclick="window.open('{{ url('admin/candidate/'.encrypt_data($appliedJobData->bodSingleCandidate->id)) }}');">View Data</button></h6>
                                <p>Last Updated: <b>{{ $statusitem->updated_at }}</b></p>
                                <p>Received from Recruiter Partner ID : <b>{{ $appliedJobData->user_id }}</b></p>
                            </li>
                        @else
                            @php
                                if($statusitem->field_status =='Rejected'){
                                $fieldclass="tab_rejected";
                                }else{
                                $fieldclass="tab_inactive";
                                }


                            @endphp

                            @if(!in_array($statusitem->field_status,['None','Stand By','No Response']))

                                <li class="{{ $fieldclass }}">
                                    <h6><b>{{ ($statusitem->status == 'Final Selection' && $statusitem->field_status == 'Selected') ? 'Selected' : $statusitem->status }}</b>

                                        @if(in_array($getUserRole,['Employer','Admin','Super Admin']) && $appliedJobData->getJobDetail->status == 'Active' )

                                        @if(in_array($statusitem->status,['Assessment','Phone Interview','In person Interview']))

                                            @if(empty($inviteeCreatedPayload) && ($employer_calendly == true) && !empty($employer_schedule_url))
                                                    @if ($statusitem->status == 'Assessment')
                                                        <a href="" onclick="Calendly.initPopupWidget({url: '{{ $employer_schedule_url }}/assessment?name={{ $appliedJobData->bodSingleCandidate->name }}&email={{ $appliedJobData->bodSingleCandidate->email }}',utm: {
                                                        utmCampaign: {{ $appliedJobData->job_id }},
                                                        utmSource: {{ $appliedJobData->candidate_id }},
                                                        utmMedium: 'Assessment',
                                                        // utmContent: 'Shoe and Shirts',
                                                        // utmTerm: 'spring'
                                                        }});return false;">Schedule</a></h6>
                                                    @elseif($statusitem->status == 'Phone Interview')
                                                    <a href="" onclick="Calendly.initPopupWidget({url: '{{ $employer_schedule_url }}/phone-interview?name={{ $appliedJobData->bodSingleCandidate->name }}&email={{ $appliedJobData->bodSingleCandidate->email }}',utm: {
                                                        utmCampaign: {{ $appliedJobData->job_id }},
                                                        utmSource: {{ $appliedJobData->candidate_id }},
                                                        utmMedium: 'Phone Interview',
                                                        // utmContent: 'Shoe and Shirts',
                                                        // utmTerm: 'spring'
                                                        }});return false;">Schedule</a></h6>

                                                    @elseif($statusitem->status == 'In person Interview')

                                                    <a href="" onclick="Calendly.initPopupWidget({url: '{{ $employer_schedule_url }}/in-person-interview?name={{ $appliedJobData->bodSingleCandidate->name }}&email={{ $appliedJobData->bodSingleCandidate->email }}',utm: {
                                                        utmCampaign: {{ $appliedJobData->job_id }},
                                                        utmSource: {{ $appliedJobData->candidate_id }},
                                                        utmMedium: 'In person Interview',
                                                        // utmContent: 'Shoe and Shirts',
                                                        // utmTerm: 'spring'
                                                        }});return false;">Schedule</a></h6>

                                                    @else
                                                    </h6>
                                                    @endif
                                            @else
                                                    @php
                                                    //echo "<pre>";print_r($inviteeCreatedPayload);
                                                    $schedule_created_at ="";
                                                    $schedule_start_time ="";
                                                    $schedule_end_time ="";
                                                     if(!empty($inviteeCreatedPayload)){
                                                        $schedule_created_at =getConvertedDateTime($inviteeCreatedPayload['payload']['scheduled_event']['created_at']);
                                                        $schedule_start_time=getConvertedDateTime($inviteeCreatedPayload['payload']['scheduled_event']['start_time']);
                                                        $schedule_end_time=getConvertedDateTime($inviteeCreatedPayload['payload']['scheduled_event']['end_time']);
                                                     }
                                                    @endphp
                                                    @if(!empty($inviteeCreatedPayload) && !empty($inviteeCreatedPayload) && empty($inviteeCancelPayload) )
                                                        <p><strong>Schedule Created At: </strong> {{ $schedule_created_at }}</p>
                                                        <p><strong>Schedule Start Time:</strong> {{ $schedule_start_time }}</p>
                                                        <p><strong>Schedule End TIme:</strong> {{ $schedule_end_time }}</p>
                                                        <p><strong>Schedule Timezone:</strong> {{ $inviteeCreatedPayload['payload']['timezone'] }}</p>
                                                    @elseif(!empty($inviteeCreatedPayload) && !empty($inviteeCancelPayload) && $inviteeCancelPayload['payload']['rescheduled'] == true)
                                                        <p><strong>Reschedule Created At: </strong> {{ $schedule_created_at }}</p>
                                                        <p><strong>Reschedule Start Time:</strong> {{ $schedule_start_time }}</p>
                                                        <p><strong>Reschedule End TIme:</strong> {{ $schedule_end_time }}</p>
                                                        <p><strong>Reschedule Timezone:</strong> {{ $inviteeCreatedPayload['payload']['timezone'] }}</p>
                                                    @else
                                                        @if(!empty($inviteeCancelPayload) && isset($inviteeCancelPayload['payload']['cancellation']))
                                                            <p><strong>Schedule Status:</strong> {{ $inviteeCancelPayload['payload']['scheduled_event']['status'] }}</p>
                                                            <p><strong>Schedule Canceled By:</strong>{{ $inviteeCancelPayload['payload']['cancellation']['canceled_by'] }} </p>
                                                            <p><strong>Schedule Cancel Reason:</strong>{{ $inviteeCancelPayload['payload']['cancellation']['reason'] }} </p>
                                                        @endif
                                                    @endif

                                                    @if(!empty($inviteeCancelPayload) && $inviteeCancelPayload['payload']['rescheduled'] == true)
                                                        <a href="" onclick="Calendly.initPopupWidget({url: '{{$inviteeCreatedPayload['payload']['reschedule_url']}}',utm: {
                                                                                utmCampaign: {{ $appliedJobData->job_id }},
                                                                                utmSource: {{ $appliedJobData->candidate_id }},
                                                                                utmMedium: 'Assessment',
                                                                                // utmContent: 'Shoe and Shirts',
                                                                                // utmTerm: 'spring'
                                                                        }});return false;">re Schedule</a></h6>

                                                        <a href="" onclick="Calendly.initPopupWidget({url: '{{$inviteeCreatedPayload['payload']['cancel_url']}}',utm: {
                                                                                utmCampaign: {{ $appliedJobData->job_id }},
                                                                                utmSource: {{ $appliedJobData->candidate_id }},
                                                                                utmMedium: 'Assessment',
                                                                                // utmContent: 'Shoe and Shirts',
                                                                                // utmTerm: 'spring'
                                                                        }});return false;">Cancel Schedule</a></h6>
                                                    @endif
                                                    {{-- <a target="_blank" href="" >re Schedule</a></h6> --}}
                                            @endif
                                        @endif
                                    @endif

                                    <div class="col-md-6 p-0">
                                        @if(in_array($getUserRole,['Employer','Admin','Super Admin']) && $appliedJobData->getJobDetail->status == 'Active' )
                                        <div class="accordion" id="regularAccordionRobots">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="regularHeadingSecond">
                                                    <button class="accordion-button btn-sm collapsed btn-collapsed-header" type="button" data-bs-toggle="collapse" data-bs-target="#regularCollapseSecond2_{{ $key }}" aria-expanded="false" aria-controls="regularCollapseSecond">
                                                        Notes
                                                    </button>
                                                </h2>
                                                <form action="{{ route("admin.appliedJobs.singleUpdate") }}" method="POST" id="EditAppliedJobForm" autocomplete="off" enctype="multipart/form-data">
                                                    @csrf

                                                    <input type="hidden" id="job_id" name="job_id" value="{{$appliedJobData->job_id}}">
                                                    <input type="hidden" id="candidate_id" name="candidate_id" value="{{$appliedJobData->candidate_id}}">
                                                    <input type="hidden" id="status" name="status" value="{{$statusitem->status}}">

                                                    <div id="regularCollapseSecond2_{{ $key }}" class="accordion-collapse collapse" aria-labelledby="regularHeadingSecond" data-bs-parent="#regularAccordionRobots">
                                                        <div class="text-end"><button type="button" class="btn btn-xs btn-info btn-edit-notes" onclick="BtnEditNotesClick({{ $key }});"><i class="fas fa-edit"></i></button></div>

                                                        <textarea id="additional_note_{{ $key }}" name="additional_note" class="w-100 CustomNotesClass accordion-body">{{ (!empty($statusitem->additional_note) && $statusitem->additional_note != 'skip_additional_note') ?''.$statusitem->additional_note : '' }}</textarea>
                                                        <div class="text-end"><button type="submit" class="btn-primary CustomSaveNotesClass btn-xs btn-save_{{ $key }}" style="display: none;">Save</button></div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        @endif
                                    </div><br>
                                    <p>Last Updated: <b>{{ $statusitem->updated_at }}</b></p>

                                    @if($statusitem->field_status == 'Stand By' || $statusitem->field_status == 'No Response')
                                     @if(in_array($getUserRole,['Employer','Admin','Super Admin']) && $appliedJobData->getJobDetail->status == 'Active' )
                                            <select name="field_status select2bs4" onchange="changeStatus('{{$statusitem->candidate_id}}','{{$statusitem->job_id}}',this.value,'{{$statusitem->status}}');">
                                                <option value="">Select status</option>
                                                <option value="Selected" {{ ($statusitem->field_status == 'Selected') ? 'Selected': '' }}>Selected</option>
                                                <option value="Rejected" {{ ($statusitem->field_status == 'Rejected') ? 'Selected' :'' }}>Rejected</option>
                                                <option value="Stand By" {{ ($statusitem->field_status == 'Stand By') ? 'Selected':'' }}>Stand By</option>
                                                <option value="No Response" {{ ($statusitem->field_status == 'No Response') ? 'Selected':'' }}>No Response</option>
                                            </select>
                                        @endif
                                    @elseif($statusitem->field_status == 'Rejected')
                                    <p style="color:red;"> Rejected From {{ $statusitem->status }}</p>
                                    
                                    @elseif($statusitem->field_status == 'Skip')
                                    <b style="color:red;">You Skip This Step</b>
                                    @elseif($statusitem->field_status == 'Selected' && $statusitem->status != 'Final Selection')
                                    <b style="color:green;">Profile Proceed for The Next Round</b>
                                    @else
                                    <b style="color:green;">Profile Shortlisted</b>
                                    @endif
                                </li>
                            @endif
                        @endif

                        @if ($key==($candidateJobStatusCommentCnt-1) )
                            @php
                                $next_status =true;

                            @endphp

                            @foreach ($statuses as $key1 => $sItem )
                                @php

                                    $inviteeCreatedPayload = null;
                                    $inviteeCancelPayload = null;
                                    $statusItem2 = inviteePayloadCreateCancel($statusitem->candidate_id,$statusitem->job_id,$sItem);
                                   if(!empty($statusItem2) && !empty($statusItem2->Invitee_created_payload)){
                                      $inviteeCreatedPayload = json_decode($statusItem2->Invitee_created_payload,true);

                                   }
                                   if(!empty($statusItem2) && !empty($statusItem2->Invitee_canceled_payload)){
                                        $inviteeCancelPayload = json_decode($statusItem2->Invitee_canceled_payload,true);
                                   }

                                @endphp
                                @if (!in_array($sItem ,$activestatuses) )

                                    @if($next_status ==true && $statusitem->field_status !='Rejected')
                                        <li class="tab_active">

                                            <h6><b>{{ $sItem }}</b>

                                            @if(in_array($getUserRole,['Employer','Admin','Super Admin']) && $appliedJobData->getJobDetail->status == 'Active' )

                                                @if(in_array($sItem,['Assessment','Phone Interview','In person Interview']))

                                                    @if(empty($inviteeCreatedPayload) && ($employer_calendly == true) && !empty($employer_schedule_url))

                                                        @if ($sItem == 'Assessment')
                                                            <a href="" onclick="Calendly.initPopupWidget({url: '{{ $employer_schedule_url }}/assessment?name={{ $appliedJobData->bodSingleCandidate->name }}&email={{ $appliedJobData->bodSingleCandidate->email }}',utm: {
                                                            utmCampaign: {{ $appliedJobData->job_id }},
                                                            utmSource: {{ $appliedJobData->candidate_id }},
                                                            utmMedium: 'Assessment',
                                                            // utmContent: 'Shoe and Shirts',
                                                            // utmTerm: 'spring'
                                                            }});return false;">Schedule</a></h6>
                                                        @elseif($sItem == 'Phone Interview')
                                                            <a href="" onclick="Calendly.initPopupWidget({url: '{{ $employer_schedule_url }}/phone-interview?name={{ $appliedJobData->bodSingleCandidate->name }}&email={{ $appliedJobData->bodSingleCandidate->email }}',utm: {
                                                            utmCampaign: {{ $appliedJobData->job_id }},
                                                            utmSource: {{ $appliedJobData->candidate_id }},
                                                            utmMedium: 'Phone Interview',
                                                            // utmContent: 'Shoe and Shirts',
                                                            // utmTerm: 'spring'
                                                            }});return false;">Schedule</a></h6>

                                                        @elseif($sItem == 'In person Interview')

                                                            <a href="" onclick="Calendly.initPopupWidget({url: '{{ $employer_schedule_url }}/in-person-interview?name={{ $appliedJobData->bodSingleCandidate->name }}&email={{ $appliedJobData->bodSingleCandidate->email }}',utm: {
                                                            utmCampaign: {{ $appliedJobData->job_id }},
                                                            utmSource: {{ $appliedJobData->candidate_id }},
                                                            utmMedium: 'In person Interview',
                                                            // utmContent: 'Shoe and Shirts',
                                                            // utmTerm: 'spring'
                                                            }});return false;">Schedule</a></h6>

                                                        @else
                                                        </h6>
                                                        @endif
                                                    @else

                                                    @php

                                                      if(!empty($inviteeCreatedPayload)){

                                                            $schedule_created_at =getConvertedDateTime($inviteeCreatedPayload['payload']['scheduled_event']['created_at']);
                                                            $schedule_start_time=getConvertedDateTime($inviteeCreatedPayload['payload']['scheduled_event']['start_time']);
                                                            $schedule_end_time=getConvertedDateTime($inviteeCreatedPayload['payload']['scheduled_event']['end_time']);
                                                      }


                                                    @endphp


                                                        @if(!empty($inviteeCreatedPayload) && empty($inviteeCancelPayload))

                                                            <p><strong>Schedule Created At: </strong> {{ $schedule_created_at }}</p>
                                                            <p><strong>Schedule Start Time:</strong> {{ $schedule_start_time }}</p>
                                                            <p><strong>Schedule End TIme:</strong> {{ $schedule_end_time }}</p>
                                                            <p><strong>Schedule Timezone:</strong> {{ $inviteeCreatedPayload['payload']['timezone'] }}</p>
                                                        @elseif(isset($inviteeCancelPayload['payload']) && isset($inviteeCancelPayload['payload']['rescheduled']) && $inviteeCancelPayload['payload']['rescheduled'] == true)


                                                            <p><strong>Reschedule Created At: </strong> {{ $schedule_created_at }}</p>
                                                            <p><strong>Reschedule Start Time:</strong> {{ $schedule_start_time }}</p>
                                                            <p><strong>Reschedule End TIme:</strong> {{ $schedule_end_time }}</p>
                                                            <p><strong>Reschedule Timezone:</strong> {{ $inviteeCreatedPayload['payload']['timezone'] }}</p>
                                                         @elseif(isset($inviteeCancelPayload['payload']) && isset($inviteeCancelPayload['payload']['rescheduled']) && $inviteeCancelPayload['payload']['rescheduled'] == false)

                                                            @if(isset($inviteeCancelPayload['payload']) && isset($inviteeCancelPayload['payload']['rescheduled']))
                                                                <p><strong>Schedule Status:</strong> {{ $inviteeCancelPayload['payload']['scheduled_event']['status'] }}</p>
                                                                <p><strong>Schedule Canceled By:</strong>{{ $inviteeCancelPayload['payload']['cancellation']['canceled_by'] }} </p>
                                                                <p><strong>Schedule Cancel Reason:</strong>{{ $inviteeCancelPayload['payload']['cancellation']['reason'] }} </p>
                                                            @endif

                                                        @endif
                                                        @if(!empty($schedule_created_at) && (empty($inviteeCancelPayload) || $inviteeCancelPayload['payload']['rescheduled'] == true))

                                                            <a href="" onclick="Calendly.initPopupWidget({url: '{{$inviteeCreatedPayload['payload']['reschedule_url']}}',utm: {
                                                                                    utmCampaign: {{ $appliedJobData->job_id }},
                                                                                    utmSource: {{ $appliedJobData->candidate_id }},
                                                                                    utmMedium: 'Assessment',
                                                                                    // utmContent: 'Shoe and Shirts',
                                                                                    // utmTerm: 'spring'
                                                                            }});return false;">re Schedule</a></h6>

                                                            <a href="" onclick="Calendly.initPopupWidget({url: '{{$inviteeCreatedPayload['payload']['cancel_url']}}',utm: {
                                                                                    utmCampaign: {{ $appliedJobData->job_id }},
                                                                                    utmSource: {{ $appliedJobData->candidate_id }},
                                                                                    utmMedium: 'Assessment',
                                                                                    // utmContent: 'Shoe and Shirts',
                                                                                    // utmTerm: 'spring'
                                                                            }});return false;">Cancel Schedule</a></h6>
                                                        @endif
                                                        {{-- <a target="_blank" href="" >re Schedule</a></h6> --}}
                                                    @endif
                                                @endif
                                            @endif
                                            <div class="col-md-6 p-0">
                                                @if(in_array($getUserRole,['Employer','Admin','Super Admin']) && $appliedJobData->getJobDetail->status == 'Active' )
                                                <div class="accordion" id="regularAccordionRobots">

                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="regularHeadingSecond">
                                                            <button class="accordion-button btn-sm collapsed btn-collapsed-header " type="button" data-bs-toggle="collapse" data-bs-target="#regularCollapseSecond2" aria-expanded="false" aria-controls="regularCollapseSecond">
                                                                Notes
                                                            </button>

                                                        </h2>
                                                        <form action="{{ route("admin.appliedJobs.singleUpdate") }}" method="POST" id="EditAppliedJobForm" autocomplete="off" enctype="multipart/form-data">
                                                            @csrf

                                                            <input type="hidden" id="job_id" name="job_id" value="{{$appliedJobData->job_id}}">
                                                            <input type="hidden" id="candidate_id" name="candidate_id" value="{{$appliedJobData->candidate_id}}">
                                                            <input type="hidden" id="status" name="status" value="{{$sItem}}">

                                                            <div id="regularCollapseSecond2" class="accordion-collapse collapse" aria-labelledby="regularHeadingSecond" data-bs-parent="#regularAccordionRobots">
                                                                <div class="text-end"><button type="button" class="btn btn-xs btn-info btn-edit-notes" onclick="BtnEditNotesClick({{ $key1 }});"><i class="fas fa-edit"></i></button></div>

                                                                <textarea id="additional_note_{{ $key1 }}" name="additional_note" class="w-100 CustomNotesClass accordion-body">{{ (!empty($statusItem2->additional_note) && $statusItem2->additional_note != 'skip_additional_note') ?''.$statusItem2->additional_note : '' }}</textarea>
                                                                <div class="text-end"><button type="submit" class="btn-primary CustomSaveNotesClass btn-xs btn-save_{{ $key1 }}" style="display: none;">Save</button></div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                @endif
                                            </div><br>

                                            @if(!in_array($statusitem->field_status,['Stand By','No Response']))
                                                @if(in_array($getUserRole,['Employer','Admin','Super Admin']) && $appliedJobData->getJobDetail->status == 'Active' )
                                                    <select name="field_status select2bs4" onchange="changeStatus('{{$statusitem->candidate_id}}','{{$statusitem->job_id}}',this.value,'{{$sItem}}');">
                                                        <option value="">Select status</option>
                                                        <option value="Selected">Selected</option>
                                                        <option value="Rejected">Rejected</option>
                                                        <option value="Stand By">Stand By</option>
                                                        <option value="No Response">No Response</option>
                                                    </select>
                                                    @if ($statusitem->status != 'Background Check')
                                                         <button class="btn btn-xs btn-warning skip-status-update" onclick="SkipFieldStatus('{{$appliedJobData->bodSingleCandidate->name}}','{{$statusitem->candidate_id}}','{{$statusitem->job_id}}','{{$sItem}}','Skip');">skip Round</button>

                                                    @endif
                                                @endif
                                            @else
                                                <p>Last Updated: <b>{{ $statusitem->updated_at }}</b></p>
                                                @if ($statusitem->field_status == 'Stand By')
                                                    <p><b>Profile is on Stand By</b></p>
                                                @else
                                                    <p><b>Candidate is No Responce</b></p>
                                                @endif
                                                @if(in_array($getUserRole,['Employer','Admin','Super Admin']) && $appliedJobData->getJobDetail->status == 'Active' )
                                                    <select name="field_status select2bs4" onchange="changeStatus('{{$statusitem->candidate_id}}','{{$statusitem->job_id}}',this.value,'{{$statusitem->status}}');">
                                                        <option value="">Select status</option>
                                                        <option value="Selected" {{ ($statusitem->field_status == 'Selected') ? 'Selected': '' }}>Selected</option>
                                                        <option value="Rejected" {{ ($statusitem->field_status == 'Rejected') ? 'Selected' :'' }}>Rejected</option>
                                                        <option value="Stand By" {{ ($statusitem->field_status == 'Stand By') ? 'Selected':'' }}>Stand By</option>
                                                        <option value="No Response" {{ ($statusitem->field_status == 'No Response') ? 'Selected':'' }}>No Response</option>
                                                    </select>
                                                @endif
                                            @endif
                                        </li>
                                    @else
                                        <li class="tab_active">
                                            <h6><b>{{ $sItem }}</b></h6><br>
                                        </li>
                                    @endif
                                    @php
                                    $next_status =false;
                                    @endphp
                                @endif
                            @endforeach
                        @endif

                    @endforeach


                </ul>
            </div>
        </div>
        <div class="d-flex flex-wrap flex-md-nowrap justify-content-center justify-content-sm-between align-items-center">

            <div class="text-left text-sm-right">
                <a style="margin-top:20px;" class="btn btn-default" href="{{ url($backPagePath) }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <!-- START- EDIT MODEL -->
    <div class="modal fade" id="candidate-edit-status-model" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Status update</h5>
                    <button type="button" class="close cancel-btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route("admin.appliedJobs.singleUpdate", [$appliedJobData->id]) }}" method="POST" id="EditAppliedJobForm" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="status" name="status" value="">
                        <input type="hidden" id="candidateStatus_id" name="candidateStatus_id">


                        <div class="row stand_by-div">
                            <div class="form-group col-md-12 {{ $errors->has('status') ? 'has-error' : '' }}">
                                <label for="status">{{ trans('cruds.appliedJobs.fields.status') }}*</label>
                                <select class="form-control" name="edit_status" id="edit_status">
                                    <option disabled selected>Select Status</option>
                                    <option>Selected</option>
                                    <option>Rejected</option>
                                </select>
                                @if($errors->has('status'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.appliedJobs.fields.status_helper') }}
                                </p>
                            </div>
                        </div>


                        <div class="row assessment_link-div">
                            <div class="form-group  {{ $errors->has('assessment_link') ? 'has-error' : '' }}">
                                <label for="assessment_link">{{ trans('cruds.appliedJobs.fields.assessment_link') }}* <a href="#" data-placement="right" data-bs-toggle="tooltip" title='1. Go to your Calendly account and navigate to the "Event Types" section. You can access it through the following link: Calendly Event Types. &#10;

                                    2. Find the event you want to share and click on the "Share" button associated with that event.

                                    3. In the sharing options, locate the "Share a link" tab and ensure that the "Make link single-use" option is checked.

                                    4. Copy the generated link provided in the "Share a link" tab. This link is now configured as a single-use link, meaning it can only be accessed once by the recipient.'><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
                                <input type="text" id="assessment_link" name="assessment_link" class="form-control" placeholder="Link url" value="">
                                @if($errors->has('assessment_link'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('assessment_link') }}
                                </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.appliedJobs.fields.assessment_link_helper') }}
                                </p>
                            </div>
                        </div>


                        <div class="row">
                            <div class="">
                                <div class="form-group {{ $errors->has('additional_note') ? 'has-error' : '' }}">
                                    <label for="additional_note">{{ trans('cruds.appliedJobs.fields.additional_note') }}*</label>
                                    <textarea placeholder="Additional note" class="form-control" id="additional_note" name="additional_note" rows="3"></textarea>


                                    @if($errors->has('additional_note'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('additional_note') }}
                                    </em>
                                    @endif
                                    <p class="helper-block">
                                        {{ trans('cruds.appliedJobs.fields.additional_note_helper') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div>
                            <button class="btn btn-info" type="submit">{{ trans('global.save') }}</button>
                            <button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal">Cancel</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END- EDIT MODEL -->

    <!-- START- DELETE MODEL -->
    <div class="modal fade" id="candidate-status-model" tabindex="-1" role="dialog" aria-hidden="true">

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Status update</h5>
                    <button type="button" class="close cancel-btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route("admin.appliedJobs.update", [$appliedJobData->id]) }}" method="POST" id="EditAppliedJobForm" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="job_id" name="job_id" value="{{$appliedJobData->job_id}}">
                        <input type="hidden" id="candidate_id" name="candidate_id" value="{{$appliedJobData->candidate_id}}">
                        <input type="hidden" id="applied_job_id" name="applied_job_id" value="{{$appliedJobData->id}}">
                        <input type="hidden" id="job_status" name="job_status" value="">


                        <div class="form-group selectedJobStatus">
                            @php
                            $roleName =getUserRole(auth()->user()->id);
                            $disableItem ="";
                            if($roleName == 'Recruiter'){
                            $disableItem ="disabled";
                            }
                            @endphp
                            <label for="license_candidate_banking_finance">{{ trans('cruds.appliedJobs.fields.status') }}*</label>
                            <select class="select2 CandidateJobStatus job_status" data-id="{{encrypt_data($appliedJobData->id)}}" name="selected_job_status" {{ $disableItem }}>

                                <option value="Selected">Selected</option>

                                <option value="Rejected">Rejected</option>

                                <option value="Stand By">Stand By</option>

                                <option value="Candidate not responding (No Response)">Candidate not responding (No Response)</option>

                            </select>
                            @if($errors->has('license_candidate_banking_finance'))
                            <em class="invalid-feedback">
                                {{ $errors->first('license_candidate_banking_finance') }}
                            </em>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.employerJobs.fields.license_candidate_banking_finance_helper') }}
                            </p>
                        </div>


                        <div class="row assessment_link-div">
                            <div class="form-group  {{ $errors->has('assessment_link') ? 'has-error' : '' }}">
                                <label for="assessment_link">{{ trans('cruds.appliedJobs.fields.assessment_link') }}* <a href="#" data-placement="right" data-bs-toggle="tooltip" title='1. Go to your Calendly account and navigate to the "Event Types" section. You can access it through the following link: Calendly Event Types. &#10;

                                    2. Find the event you want to share and click on the "Share" button associated with that event.

                                    3. In the sharing options, locate the "Share a link" tab and ensure that the "Make link single-use" option is checked.

                                    4. Copy the generated link provided in the "Share a link" tab. This link is now configured as a single-use link, meaning it can only be accessed once by the recipient.'><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
                                <input type="text" id="assessment_link" name="assessment_link" class="form-control" placeholder="Link url" value="">
                                @if($errors->has('assessment_link'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('assessment_link') }}
                                </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.appliedJobs.fields.assessment_link_helper') }}
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="">
                                <div class="form-group {{ $errors->has('additional_note') ? 'has-error' : '' }}">
                                    <label for="additional_note">{{ trans('cruds.appliedJobs.fields.additional_note') }}*</label>
                                    <textarea placeholder="Additional note" class="form-control" id="additional_note" name="additional_note" rows="3"></textarea>


                                    @if($errors->has('additional_note'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('additional_note') }}
                                    </em>
                                    @endif
                                    <p class="helper-block">
                                        {{ trans('cruds.appliedJobs.fields.additional_note_helper') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div>
                            <button class="btn btn-info" type="submit">{{ trans('global.save') }}</button>
                            <button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal">Cancel</button>
                            {{-- <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}"> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END- DELETE MODEL -->

    <!-- START- SKIP MODEL -->
    <div class="modal fade" id="skip-status-model" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="close cancel-btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route("admin.appliedJobs.candidateSkipFieldChangeStatus", [$appliedJobData->id]) }}" method="POST" id="EditAppliedJobForm" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="skip_candidate_name" name="skip_candidate_name" value="">
                        <input type="hidden" id="skip_candidate_id" name="skip_candidate_id" value="">
                        <input type="hidden" id="skip_job_id" name="skip_job_id" value="">
                        <input type="hidden" id="skip_status" name="skip_status" value="">
                        <input type="hidden" id="skip_field_status" name="skip_field_status" value="">

                        <p>Are you Sure want to skip this round?</p>

                        <br>
                        <div>
                            <button class="btn btn-info" type="submit">Yes</button>
                            <button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal">No</button>
                            {{-- <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}"> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END- SKIP MODEL -->
@endsection
@push('js')

<!-- jquery-validation -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>

@include('admin.customJs.bodAppliedJobs.candidateJobStatus')
@endpush
