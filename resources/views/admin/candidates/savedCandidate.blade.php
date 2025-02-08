@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.savedCandidates.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Role datatable-User">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.candidates.fields.id') }}
                        </th>
                        <!-- <th>
                            {{ trans('cruds.candidates.fields.candidate_id') }}
                        </th> -->
                        <th>
                            {{ trans('cruds.candidates.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.candidates.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.candidates.fields.status') }}
                        </th>
                        <th>
                            Action&nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($candidates as $key => $candidate)
                        @if(!empty($candidate->candidate))
                        <tr data-entry-id="{{ $candidate->candidate->id }}">
                            <td>
                                {{ $candidate->candidate->id ?? '' }}
                            </td>
                            <!-- <td>
                                {{ $candidate->candidate->candidate_id ?? '' }}
                            </td> -->
                            <td>
                                {{ $candidate->candidate->name ?? '' }}
                            </td>
                            <td>
                                {{ $candidate->candidate->email ?? '' }}
                            </td>
                            <td>
                                @if ($candidate->candidate->status == 'Active')
                                    <button class="btn btn-xs btn-success disabled">Active</button>
                                @elseif ($candidate->candidate->status == 'Deactive')
                                    <button class="btn btn-xs btn-danger disabled">{{ trans('global.deactive') }}</button>
                                @endif
                            </td>
                            <td>
                                @can('candidate_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.candidate.show', encrypt_data($candidate->candidate->id)) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('candidate_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.candidate.edit', encrypt_data($candidate->candidate->id)) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('candidate_delete')

                                    <button class="btn btn-xs btn-danger delete-btn" data-id="{{$candidate->candidate->candidate_id}}">
                                        {{ trans('global.delete') }}
                                    </button>
                                @endcan
                                <a class="btn btn-xs btn-warning" href="{{ route('admin.candidate.unSavedCandidate', ['id' =>encrypt_data($candidate->candidate->candidate_id) ]) }}">
                                  un-save
                                </a>

                            </td>

                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
// @can('role_delete')
//   let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
//   let deleteButton = {
//     text: deleteButtonTrans,
//     url: "{{ route('admin.roles.massDestroy') }}",
//     className: 'btn-danger',
//     action: function (e, dt, node, config) {
//       var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
//           return $(entry).data('entry-id')
//       });

//       if (ids.length === 0) {
//         alert('{{ trans('global.datatables.zero_selected') }}')

//         return
//       }

//       if (confirm('{{ trans('global.areYouSure') }}')) {
//         $.ajax({
//           headers: {'x-csrf-token': _token},
//           method: 'POST',
//           url: config.url,
//           data: { ids: ids, _method: 'DELETE' }})
//           .done(function () { location.reload() })
//       }
//     }
//   }
//   dtButtons.push(deleteButton)
// @endcan

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  });
//   $('.datatable-Role:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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

            $('.modal-body').html('Are you sure you want to delete this candidate? Deleting this candidate will also remove any applied jobs associated with them.');
            $('#delete-model').modal('show');
            $('.delete-form').attr('action', "{{ url('admin/bodCandidate/destroy') }}?id=" + itemId);
        });

        $('.cancel-btn').click(function() {
            $(this).closest('.modal').modal('hide');
        });


    });
</script>


@endsection
