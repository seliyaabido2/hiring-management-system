@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.contracts.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.contracts.fields.name') }}
                        </th>
                        <td>
                            {{ $contract->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contracts.fields.related_to') }}
                        </th>
                        <td>
                            {{ $contract->related_to }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contracts.fields.description') }}
                        </th>
                        <td>
                            {{ $contract->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contracts.fields.expire_alert') }}
                        </th>
                        <td>
                            {{ $contract->expire_alert }} Days
                        </td>
                    </tr>
                    <!-- Add more fields as needed -->
                </tbody>
            </table>

            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        <nav class="mb-3">
            <div class="nav nav-tabs">

            </div>
        </nav>
        <div class="tab-content">

        </div>
    </div>
</div>
@endsection
