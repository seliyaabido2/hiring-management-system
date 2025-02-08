@extends('layouts.admin')
@section('content')
@php
    $routename = request()->route()->getName();

@endphp

<div class="card">
    <div class="card-header">

        {{ trans('cruds.appliedJobs.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>

                        <th>
                            {{ trans('cruds.appliedJobs.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.appliedJobs.fields.job_title') }}
                        </th>
                        <th>
                            {{ trans('cruds.appliedJobs.fields.job_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.appliedJobs.fields.job_expiry_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.appliedJobs.fields.total_candidate_applied') }}
                        </th>

                        <th>
                            {{ trans('cruds.appliedJobs.fields.status') }}
                        </th>
                        <!-- <th>
                        Action&nbsp;
                        </th> -->
                    </tr>
                </thead>
                <tbody>

                     @foreach($appliedJobs as $key => $job)
                        <tr data-entry-id="{{ 1 }}">
                            <td>
                                {{ ($key + 1) ?? '' }}
                            </td>
                            <td>
                                {{ $job->job_title ?? '' }}
                            </td>
                            <td>
                                {{ $job->job_type ?? '' }}
                            </td>

                            <td>
                             {{ date('d-m-Y', strtotime(GetJobExpiryDate($job->job_start_date,$job->job_recruiment_duration))) }}
                            </td>
                            <td>
                                 <a href="{{ route('admin.bodAppliedJobs.show',$job->job_id) }}" target="_blank">{{ $job->total_candidates ?? '' }}</a>
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
    //     url: "{{ route('admin.bodAppliedJobs.massDestroy') }}",
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
//     $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
//         $($.fn.dataTable.tables(true)).DataTable()
//             .columns.adjust();
//     });
// })

</script>

<!-- delete Data script -->
<script>
    $(document).ready(function() {
        $('.delete-btn').click(function() {
            var itemId = $(this).data('id');
            $('#delete-model').modal('show');
            $('.delete-form').attr('action', "{{ url('admin/appliedJobs/destroy') }}?id=" + itemId);
        });

        $('.cancel-btn').click(function() {
            $(this).closest('.modal').modal('hide');
        });
    });
</script>

@endsection


