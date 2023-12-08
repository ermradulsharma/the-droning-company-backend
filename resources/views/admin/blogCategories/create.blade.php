@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.blogCategory.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.blog-categories.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.blogCategory.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.blogCategory.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.blogCategory.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\BlogCategory::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', '1') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.blogCategory.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="slug">{{ trans('cruds.blogCategory.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', '') }}">
                @if($errors->has('slug'))
                    <div class="invalid-feedback">
                        {{ $errors->first('slug') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.blogCategory.fields.slug_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="meta_keyword">{{ trans('cruds.blogCategory.fields.meta_keyword') }}</label>
                <input class="form-control {{ $errors->has('meta_keyword') ? 'is-invalid' : '' }}" type="text" name="meta_keyword" id="meta_keyword" value="{{ old('meta_keyword', '') }}">
                @if($errors->has('meta_keyword'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_keyword') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.blogCategory.fields.meta_keyword_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="meta_title">{{ trans('cruds.blogCategory.fields.meta_title') }}</label>
                <input class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', '') }}">
                @if($errors->has('meta_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.blogCategory.fields.meta_title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="meta_description">{{ trans('cruds.blogCategory.fields.meta_description') }}</label>
                <textarea class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" name="meta_description" id="meta_description">{{ old('meta_description') }}</textarea>
                @if($errors->has('meta_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.blogCategory.fields.meta_description_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection