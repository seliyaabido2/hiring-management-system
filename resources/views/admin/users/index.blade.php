@extends('layouts.admin')
@section('content')
@can('user_create')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route("admin.users.create") }}">
            {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
        </a>
    </div>
</div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th width="10">
                            No
                        </th>
                        {{-- <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th> --}}
                        <th>
                            {{ trans('cruds.user.fields.first_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.last_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>

                        <th>
                            {{ trans('cruds.user.fields.roles') }}
                        </th>
                        <th>
                            created At
                        </th>
                        <th>
                            Action&nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                    <tr data-entry-id="{{ $user->id }}">
                        <td>
                            {{ $key + 1 }}
                        </td>
                        {{-- <td>
                                {{ $user->id ?? '' }}
                        </td> --}}
                        <td>
                            {{ $user->first_name ?? '' }}
                        </td>
                        <td>
                            {{ $user->last_name ?? '' }}
                        </td>
                        <td>
                            {{ $user->email ?? '' }}
                        </td>

                        <td>
                            @foreach($user->roles as $key => $item)
                            <span class="badge badge-info">{{ $item->title }}</span>
                            @endforeach
                        </td>
                        <td>
                            {{ date("d-m-Y", strtotime($user->created_at)) ?? '' }}
                        </td>
                        <td>
                            @can('user_show')
                            <a class="btn btn-xs btn-primary" href="{{ route('admin.users.show', $user->id) }}">
                                {{ trans('global.view') }}
                            </a>
                            @endcan

                            @can('user_edit')
                            <a class="btn btn-xs btn-info" href="{{ route('admin.users.edit', $user->id) }}">
                                {{ trans('global.edit') }}
                            </a>
                            @endcan

                            @can('user_delete')
                            <!-- <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form> -->
                            <button class="btn btn-xs btn-danger delete-btn" data-id="{{$user->id}}">
                                {{ trans('global.delete') }}
                            </button>
                            @endcan

                            @if(getUserRole($user->id) =='Employer' )
                            <button class="btn btn-xs btn-success add-calendly-btn" data-id="{{$user->id}}">
                                Add to calendly
                            </button>
                            @endif
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>

@endsection


@section('scripts')
@parent


<!-- delete Data script -->
<script>
    $(document).ready(function() {


        $('.cancel-btn').click(function() {
            $(this).closest('.modal').modal('hide');
        });


        $('.add-calendly-btn').click(function() {
            var itemId = $(this).data('id');
            $('.modal-body').html('Are you sure you want to add in calendly');
            $('.delete-form .btn').removeClass('btn-danger');
            $('.delete-form .btn').addClass('btn-success');
            $('.delete-form .btn').html('Add');
            $('#delete-model').modal('show');

            $('.delete-form').attr('action', "{{ url('admin/users/add-calendly-user') }}?id=" + itemId);
            $('input[name="_method"]').val('POST');
        });
    });
</script>
@endsection
