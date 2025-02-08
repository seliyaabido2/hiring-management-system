@extends('layouts.admin')
@section('content')

@php
if($status =='Assessment'){
    $slug ='assessment-1';
}else if($status =='Phone Interview'){
    $slug ='phone-interview';
}else if($status =='In person Interview'){
$slug ='in-person-interview';
}else{

}

@endphp
<!-- Calendly inline widget begin -->
<div class="calendly-inline-widget" data-url="{{ $calendly_user_data['user']['scheduling_url'] }}/{{ $slug }}?name={{ $candidateData->name }}&email={{ $candidateData->email }}" style="min-width:320px;height:700px;"></div>
<script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js" async></script>
<!-- Calendly inline widget end -->

@endsection
