@extends('layouts.admin')
@section('content')
@section('styles')

@endsection

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.cmsPages.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.cmsPages.store") }}"  id="add-cms-form" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="title">{{ trans('cruds.cmsPages.fields.title') }}*</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="Enter Title" value="{{ old('title', isset($cmsPages) ? $cmsPages->title : '') }}" >
                @if($errors->has('title'))
                <label id="title-error" class="error" for="title">{{ $errors->first('title') }}</label>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.cmsPages.fields.title_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                <label for="content">{{ trans('cruds.cmsPages.fields.content') }}*</label>
                <div class="input-item">
                    <textarea id="content"  name="content" ></textarea>
                </div>



                @if($errors->has('content'))
                    <em class="invalid-feedback">
                        {{ $errors->first('content') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.cmsPages.fields.content_helper') }}
                </p>
            </div>


            <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                <label for="slug">{{ trans('cruds.cmsPages.fields.slug') }}*</label>
                <input type="text" id="slug"  placeholder="Enter slug" class="form-control" value="{{ old('slug', isset($cmsPages) ? $cmsPages->slug : '') }}" disabled>

                <input type="hidden" name="slug" id="hiddenSlugId">
                @if($errors->has('slug'))
                    <em class="invalid-feedback">
                        {{ $errors->first('slug') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.cmsPages.fields.slug_helper') }}
                </p>
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


@include('admin.customJs.cmsPages.create')

@endsection
