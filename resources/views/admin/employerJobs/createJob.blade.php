@extends('layouts.admin')
@section('content')

@push('css')


@endpush
<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.employerJobs.title_singular') }}
    </div>

    <div class=" mt-5">

            <div class="card-body text-center">
                <a href="{{ route('admin.employerJobs.create') }}" class="btn btn-xl btn-success ">New Job</a>

                <a href="javascript:void();" data-toggle="modal" data-target="#existing-job-model" class="btn btn-xl btn-info existing-job">Use Existing Job</a>
            </div>

    </div>


</div>

<!-- START - EDIT MODEL -->
<div class="modal fade" id="existing-job-modal" role="dialog">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Existing Jobs</h5>
                <button type="button" class="close cancel-btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

<style>
    .select2-results__option {
    padding-left: 8px;
    padding-right: 0px;
}
</style>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="edit_status">Select Job</label>
                      
                        <select class="form-control select2" name="exsiting_job" id="exsiting_job">

                            <option disabled selected>Select job</option>

                           

                            @foreach ($savedJobTemplates as $template)


                            <option value="{{ encrypt_data($template->employerJob->id) }}">{{ $template->employerJob->job_title }}</option>
                            @endforeach


                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END - EDIT MODEL -->
@endsection


@push('js')

<!-- jquery-validation -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>

@include('admin.customJs.employerJobs.createJob')
@endpush

