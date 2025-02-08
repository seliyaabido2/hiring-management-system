@extends('layouts.admin')
@section('content')
@php
    $routename = request()->route()->getName();

@endphp
<div class="card">
    <div class="card-header">
        @if ($routename == 'admin.employerJobs.myjob')
        {{ trans('cruds.myJobs.title_singular') }} {{ trans('global.list') }}
        @else
        {{ trans('cruds.employerJobs.title_singular') }} {{ 'Pendding' }} {{ trans('global.list') }}
        @endif
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <!-- <th width="10">

                        </th> -->
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
                            <!-- <td>

                            </td> -->
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
                                <button class="btn btn-xs btn-warning disabled">{{ ($job->status =='Hold')  ? 'Pending':'' }}</button>
                            </td>
                            <td>
                                @php

                                $addedby = getUserName($job->user_id);

                                @endphp
                               {{ isset($addedby) ? $addedby->first_name.' '.$addedby->last_name : "" }}

                            </td>
                            <td>

                                @can('job_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.employerJobs.show', encrypt_data($job->id)) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('job_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.employerJobs.edit',encrypt_data($job->id)) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @if(!empty($job->savedJobTemplate))
                                <a class="btn btn-xs btn-warning" href="{{ route('admin.appliedJobs.unSavedJobTemplate', ['id' =>encrypt_data($job->id) ]) }}">
                                    un-save Job template
                                </a>
                                @else
                                <a class="btn btn-xs btn-success" href="{{ route('admin.appliedJobs.savedJobTemplate', ['id' =>encrypt_data($job->id) ]) }}">
                                    Save Job template
                                </a>
                                @endif

                                @can('job_delete')
                                <button class="btn btn-xs btn-danger delete-btn" data-id="{{encrypt_data($job->id)}}">
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
    order: [[ 1, 'asc' ]],
    pageLength: 10,
  });

//   $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    // $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
    //     $($.fn.dataTable.tables(true)).DataTable()
    //         .columns.adjust();
    // });
});

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


@push('js')
@include('admin.customJs.employerJobs.jobRequest')
@endpush


