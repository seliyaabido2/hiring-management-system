@extends('layouts.admin')
@section('content')
    @can('bod_candidate_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.bodCandidate.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.bodCandidates.title_singular') }}
                </a>
                <a class="btn btn-success" href="{{ route('admin.bodCandidate.bod_bulk_candidate') }}">
                    BOD Bulk Candidate
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.bodCandidates.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div  class="table-responsive">
                <table id="bodCandidateTbl" class="table table-bordered table-striped table-hover datatable-apply-selected"
                    style="width: 100%">
                    <thead>
                        <tr>
                            <th width="10">
                                <input type="checkbox" id="select-all-checkbox">
                            </th>
                            <th>
                                {{ trans('cruds.bodCandidates.fields.name') }}
                            </th>
                            <th>
                                {{ trans('cruds.bodCandidates.fields.email') }}
                            </th>
                            <th>
                                {{ trans('cruds.bodCandidates.fields.resume') }}
                            </th>
                            <th>
                                {{ trans('cruds.bodCandidates.fields.status') }}
                            </th>
                            <th>
                                {{ trans('cruds.bodCandidates.fields.last_updated_date') }}
                            </th>
                            <th>
                                Action&nbsp;
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
    @include('admin.customJs.bodCandidates.index')


@endsection
