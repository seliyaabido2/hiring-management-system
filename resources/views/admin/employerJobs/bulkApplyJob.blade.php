@extends('layouts.admin')
@section('content')
@section('styles')
@endsection

<div class="card">
    <div class="card-header">
        {{ trans('global.applyjob') }}
    </div>

    <div class="card-body">

        <form action="{{ route('admin.employerJobs.bulkPostApplyJob') }}" id="bulkApplyJobForm" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="checkedJobIdsArr" name="checkedJobIdsArr">
            <input type="hidden" id="checkedCandidateArr" name="checkedCandidateArr">
            <div class="row">
                <div class="col-md-8">

                    <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                        <label for="location">Select location*</label>

                        <input type="text" name="location" id="location" class="form-control"
                            placeholder="Choose Location">
                        <input type="hidden" id="latitude" name="latitude" value="">
                        <input type="hidden" name="longitude" id="longitude" value="">
                        <label class="location-error error"></label>
                        @if ($errors->has('location'))
                            <em class="invalid-feedback">
                                {{ $errors->first('location') }}
                            </em>
                        @endif
                    </div>

                </div>
            </div>


            <div class="row">
                <div class="col-md-8">

                    <div class="form-group">
                        <label for="formControlRange" id ="miles-label" >Find the nearest job based on the location and distance :</label>
                        <input type="range" max="200" class="form-control-range range-value" value="0"
                            id="formControlRange" onInput="$('#rangeval').html($(this).val())" data-lat =""
                            data-long ="">
                        <span id="rangeval">0<!-- Default value --></span><span> Miles</span>
                    </div>

                </div>
            </div>



            <div class="row">
                <div class="col-md-12 JobDataTable">
                    <div class="table-responsive ">
                        <p><b>Job List</b></p>
                        <table id="tblApplyjob"
                            class="table table-bordered table-striped table-hover datatable-apply-selected"
                            style="width: 100%">
                            <thead>
                                <tr>
                                    <th width="10">
                                        <input type="checkbox" class ="select-all-checkbox" >
                                    </th>

                                    <th>
                                        {{ trans('cruds.employerJobs.fields.job_title') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employerJobs.fields.job_recruiment_duration') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employerJobs.fields.job_start_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employerJobs.fields.job_expire_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employerJobs.fields.job_type') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employerJobs.fields.location') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employerJobs.fields.status') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employerJobs.fields.addedBy') }}
                                    </th>

                                </tr>

                            </thead>
                            <tbody>


                            </tbody>

                        </table>

                    </div>
                    <div class="mt-2">
                        <button class="btn btn-success" id="getJobCheckedIds" type="button" value="">Next</button>
                    </div>
                </div>
                <div class="col-md-12 CandidateDataTable" style="display: none;">

                        <div class="alert alert-danger alert-dismissible manual-hide d-none">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong class="duplicate-candidate-error"></strong>
                        </div>

                    <div class="table-responsive " >
                        <p><b>Candidate List</b></p>

                            <table id="tblCandidateJob"
                                class="table table-bordered table-striped table-hover datatable-apply-selected"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th width="10">
                                            <input type="checkbox" class="select-all-checkbox">
                                        </th>


                                        <th>
                                            {{ trans('cruds.candidates.fields.name') }}
                                        </th>
                                        <th>
                                            {{ trans('cruds.candidates.fields.email') }}
                                        </th>
                                        <th>
                                            {{ trans('cruds.candidates.fields.experirnce_sf') }}
                                        </th>
                                        <th>
                                            Location
                                        </th>
                                        <th>
                                            {{ trans('cruds.candidates.fields.status') }}
                                        </th>
                                    </tr>

                                </thead>
                                <tbody>


                                </tbody>

                            </table>


                    </div>
                    <div class="mt-2">
                        <button class="btn btn-success " id="submitformBtn" type="button" value="" >Submit</button>
                    </div>
                </div>
            </div>


        </form>


    </div>
</div>
@endsection
@section('scripts')
<!-- jquery-validation -->


<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>


@include('admin.customJs.employerJobs.bulkApplyJob')
@endsection
