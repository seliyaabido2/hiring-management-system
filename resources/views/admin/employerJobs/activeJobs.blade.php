@extends('layouts.admin')
@section('content')
@php
    $routename = request()->route()->getName();

@endphp
<!-- @can('job_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            @if ($routename == 'admin.employerJobs.myjob')
            <a class="btn btn-success" href="{{ route("admin.employerJobs.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.myJobs.title_singular') }}
            </a>
            @else
            <a class="btn btn-success" href="{{ route("admin.employerJobs.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.employerJobs.title_singular') }}
            </a>
            @endif

        </div>
    </div>
@endcan -->
<div class="card">
    <div class="card-header">
       Active  {{ trans('cruds.employerJobs.title_singular') }} {{ trans('global.list') }}

       @can('job_applied_create')
       <a class="btn btn-success btn-sm ml-2" href="{{route('admin.employerJobs.bulkApplyJob')}}">Bulk Apply job</a>
       @endcan

       @can('bod_job_applied_create')
       <a class="btn btn-success btn-sm ml-2" href="{{route('admin.employerJobs.bulkApplyJob')}}">Bulk Apply job</a>
       @endcan

    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>

                        <th>
                            {{ trans('cruds.employerJobs.fields.id') }}
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
                        <th>
                        Action&nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>

                     @foreach($EmployerJob as $key => $job)
                        <tr data-entry-id="{{ $job->id }}">

                            <td>
                             {{ ($key + 1) ?? '' }}
                            </td>
                            <td>
                                {{ $job->job_title ?? '' }}
                            </td>
                            <td>
                                {{ $job->job_recruiment_duration ?? '' }} Days
                            </td>
                            <td>
                                {{ date("d-m-Y", strtotime($job->job_start_date)) ?? '' }}
                            </td>
                            @php
                            $enddate=calculateEndDate($job->job_start_date,$job->job_recruiment_duration);

                            @endphp
                            <td>
                                {{ date("d-m-Y", strtotime($enddate)) ?? '' }}
                            </td>
                            <td>
                                {{ $job->job_type  ?? '' }}
                            </td>
                            <td>
                                {{ $job->location  ?? '' }}
                            </td>

                            <td>
                                @if ($job->status == 'Active')
                                    <button class="btn btn-xs btn-success disabled">Active</button>
                                @elseif ($job->status == 'Deactive')
                                    <button class="btn btn-xs btn-danger disabled">{{ trans('global.deactive') }}</button>
                                @elseif ($job->status == 'Hold')
                                    <button class="btn btn-xs btn-warning disabled">Hold</button>
                                @endif

                            </td>
                            <td>
                                @php

                                $addedby = getUserName($job->user_id);

                                @endphp
                               {{ isset($addedby) ? $addedby->first_name.' '.$addedby->last_name : "" }}

                            </td>
                            <td>
                                @can('job_applied_create')
                                <a class="btn btn-xs btn-success mt-11" href="{{ route('admin.employerJobs.applyJob', encrypt_data($job->id)) }}">
                                    {{ trans('global.applyjob') }}
                                </a>
                                @endcan

                                @can('bod_job_applied_create')
                                    <a class="btn btn-xs btn-success mt-11" href="{{ route('admin.employerJobs.applyJob', encrypt_data($job->id)) }}">
                                        {{ trans('global.applyjob') }}
                                    </a>
                                @endcan

                                @can('job_show')
                                    <a class="btn btn-xs btn-primary mt-11" href="{{ route('admin.employerJobs.show', encrypt_data($job->id)) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('job_edit')
                                    <a class="btn btn-xs btn-info mt-11" href="{{ route('admin.employerJobs.edit',encrypt_data($job->id)) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('bod_saved_template_access')
                                @if(!empty($job->savedJobTemplate))
                                <a class="btn btn-xs btn-warning mt-11" href="{{ route('admin.appliedJobs.unSavedJobTemplate', ['id' =>encrypt_data($job->id) ]) }}">
                                    un-save Job template
                                </a>
                                @else
                                <a class="btn btn-xs btn-success mt-11" href="{{ route('admin.appliedJobs.savedJobTemplate', ['id' =>encrypt_data($job->id) ]) }}">
                                    Save Job template
                                </a>
                                @endif
                                @endcan


                                @can('saved_template_access')
                                @if(!empty($job->savedJobTemplate))
                                <a class="btn btn-xs btn-warning mt-11" href="{{ route('admin.appliedJobs.unSavedJobTemplate', ['id' =>encrypt_data($job->id) ]) }}">
                                    un-save Job template
                                </a>
                                @else
                                <a class="btn btn-xs btn-success mt-11" href="{{ route('admin.appliedJobs.savedJobTemplate', ['id' =>encrypt_data($job->id) ]) }}">
                                    Save Job template
                                </a>
                                @endif
                                @endcan

                                @can('job_delete')
                                <button class="btn btn-xs btn-danger delete-btn mt-11" data-id="{{encrypt_data($job->id)}}">
                                        {{ trans('global.delete') }}
                                </button>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>

 <!-- Add a modal dialog for the confirmation -->

@endsection
@section('scripts')
@parent
<script>
    $(function () {
     let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

    // @can('user_delete')
        // let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        // let deleteButton = {
        //     text: deleteButtonTrans,
        //     url: "{{ route('admin.users.massDestroy') }}",
        //     className: 'btn-danger',
        //     action: function (e, dt, node, config) {
        //     var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
        //         return $(entry).data('entry-id')
        //     });

        //     if (ids.length === 0) {
        //         alert('{{ trans('global.datatables.zero_selected') }}')

        //         return
        //     }

        //     if (confirm('{{ trans('global.areYouSure') }}')) {
        //         $.ajax({
        //         headers: {'x-csrf-token': _token},
        //         method: 'POST',
        //         url: config.url,
        //         data: { ids: ids, _method: 'DELETE' }})
        //         .done(function () { location.reload() })
        //     }
        //     }
        // }
        // dtButtons.push(deleteButton)
    // @endcan

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 0, 'asc' ]],
    pageLength: 10,
  });

//   $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
//     $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
//         $($.fn.dataTable.tables(true)).DataTable()
//             .columns.adjust();
//     });
})

</script>

<!-- delete Data script -->
<script>
    $(document).ready(function() {
        $('.delete-btn').click(function() {
            var itemId = $(this).data('id');
            $('#delete-model').modal('show');
            $('.delete-form').attr('action', "{{ url('admin/employerJobs/destroy') }}?id=" + itemId);
        });

        $('.cancel-btn').click(function() {
            $(this).closest('.modal').modal('hide');
        });
    });
</script>

@endsection


