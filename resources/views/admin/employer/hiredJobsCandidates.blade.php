@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
       Hired  {{ trans('cruds.employerJobs.title_singular') .' '. trans('cruds.candidates.title') }} {{ trans('global.list') }}
        
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
                            {{ trans('cruds.employerJobs.fields.TotalNoOfHiredCandidates') }}
                        </th>
                    </tr>
                </thead>
                <tbody>

                    {{-- {{ dd($hiredJobsCandidates)}} --}}

                     @foreach($hiredJobsCandidates as $key => $job)
                        <tr data-entry-id="{{ $job->id }}">
                           
                            <td>
                                {{ ($key + 1) ?? '' }}
                            </td>
                            <td>
                                {{ $job->job_title ?? '' }}
                            </td>
                           
        
                            <td>
                                @if($job->hired_candidates_count != 0)
                                    <a href="{{ route('admin.employerJobs.hiredCandidates',['jobId' => $job->id,'userId' => $job->user_id ]) }}" target="_blank">{{ $job->hired_candidates_count ?? '' }}</a>
        
                                @else
                                    {{ $job->hired_candidates_count }}
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


