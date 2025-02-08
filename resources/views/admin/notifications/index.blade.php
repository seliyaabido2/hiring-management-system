@extends('layouts.admin')



@section('content')
@can('notifications_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.notifications.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.notifications.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.notifications.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <div class="row mb-4">
                <div class="col-md-2 ">
                    <button type="button" class="btn btn-danger" id="Bulkdelete">Delete All</button>
                </div>
            </div>
            <table id="notificationTbl" class=" table table-bordered table-striped table-hover datatable datatable-notification">
                <thead>
                    <tr>
                        <th width="10">
                            <input type="checkbox" id="select-all-checkbox">
                        </th>
                        {{-- <th>
                            {{ trans('cruds.notifications.fields.id') }}
                        </th> --}}
                        <th>
                            {{ trans('cruds.notifications.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.notifications.fields.message') }}
                        </th>
                        <th>
                            Created at
                        </th>
                    </tr>
                </thead>
                <tbody>

                   
                </tbody>
            </table>
        </div>


    </div>
</div>


@endsection
@section('scripts')
@parent


@push('js')

<!-- jquery-validation -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>

@include('admin.customJs.notifications.index')
@endpush

@endsection

