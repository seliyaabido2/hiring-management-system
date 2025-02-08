@extends('layouts.admin')
@section('content')
@section('styles')

@endsection

<style>
.loader-main {
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    z-index: 9999999;
    background-color: #000;
    opacity: 0.5;
    width: 100%;
    height: 100%;
}

.loader {
    position: absolute;
    z-index: 9;
    top: 50%;
    transform: translate(50%, 50%);
    left: 50%;
    color: red;
    bottom: 50%;
}

</style>

<div class="card">
    <div class="card-header">
        BOD Bulk Candidate

    </div>

    <div class="card-body col-md-6">
        <form action="{{ route('admin.bodCandidate.storeBODBulkCandidate') }}" id="add-bodbulk-form" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group {{ $errors->has('bodbulkcan') ? 'has-error' : '' }}">
                <label for="bodbulkcan">File upload*</label>
                <div class="input-group">
                    <input type="file" id="bodbulkcan" name="bodbulkcan" class="form-control">
                </div>
                @if ($errors->has('bodbulkcan'))
                    <span class="help-block">{{ $errors->first('bodbulkcan') }}</span>
                @endif
            </div>

            <div class="form-group">
                <a href="{{ url('sample/BOD_Bulk_can.xlsx') }}" download="BOD_Bulk_can_sample.xlsx">
                    Download Sample format
                </a>
            </div>

            <div>
                <button class="btn btn-danger" type="submit" value="">{{ trans('global.save') }}</button>
            </div>
        </form>
    </div>

</div>
<div class="card">
<div class="card-body">
    <div class="table-responsive">
        <table class=" table table-bordered table-striped table-hover datatable datatable-Role datatable-User">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($SheetStatus as $key => $SheetStatusone)
                    <tr data-entry-id="{{ $SheetStatusone->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ $SheetStatusone->sheet_name ?? '' }}</td>
                        <td>{{ $SheetStatusone->status ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>



@endsection
@section('scripts')
<!-- <script src="https://cdn.ckeditor.com/ckeditor5/38.0.1/classic/ckeditor.js"></script> -->


<!-- jquery-validation -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>


@include('admin.customJs.bodCandidates.bodBulkCandidates')

@endsection
