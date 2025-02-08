@extends('layouts.admin')
@section('content')
@section('styles')

@endsection

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.contracts.title_singular') }}
    </div>

    <div class="card-body col-md-6">
        <form action="{{ route("admin.contracts.update", [$contract->id]) }}"  id="edit-contracts-form" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="contract_id" value="{{ encrypt_data($contract->id) }}">
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.contracts.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Enter Name" value="{{ $contract->name }}">
                @if($errors->has('name'))
                <label id="name-error" class="error" for="name">{{ $errors->first('name') }}</label>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.contracts.fields.name_helper') }}
                </p>
            </div>

            <div class="form-group" >
                <label for="related_to">{{ trans('cruds.contracts.fields.related_to') }}*</label>
                <div class="input-item">
                    <select id="related_to" name="related_to" class="form-control select2bs4" >
                        <option value="">Select</option>
                        <option value="Employer" @if($contract->related_to === 'Employer') selected @endif>Employer</option>
                        <option value="Recruiter" @if($contract->related_to === 'Recruiter') selected @endif>Recruiter</option>
                    </select>
                </div>
            </div>

            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                <label for="description">{{ trans('cruds.contracts.fields.description') }}</label>
                <div class="input-item">
                    <textarea id="description" name="description" style="min-width: 100%" placeholder="Contract Description">{{ $contract->description }}</textarea>
                </div>
                @if($errors->has('description'))
                <em class="invalid-feedback">
                    {{ $errors->first('description') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.contracts.fields.description_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('expire_alert') ? 'has-error' : '' }}">
                <label for="expire_alert">{{ trans('cruds.contracts.fields.expire_alert') }} (Days)</label>
                <input type="number" id="expire_alert" name="expire_alert" class="form-control" placeholder="Enter digit days" value="{{ $contract->expire_alert }}">
                @if($errors->has('expire_alert'))
                <label id="expire_alert-error" class="error" for="expire_alert">{{ $errors->first('expire_alert') }}</label>
                @endif
            </div>

            <div>
                <button class="btn btn-danger" type="submit" value="">{{ trans('global.save') }}</button>
            </div>
        </form>


    </div>
</div>
@endsection
@section('scripts')
<!-- <script src="https://cdn.ckeditor.com/ckeditor5/38.0.1/classic/ckeditor.js"></script> -->


<!-- jquery-validation -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/additional-methods.min.js') }}"></script>
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>


@include('admin.customJs.contracts.edit')

@endsection
