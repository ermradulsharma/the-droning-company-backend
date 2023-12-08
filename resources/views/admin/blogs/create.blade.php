@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.blog.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.blogs.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.blog.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title_to_generate_slug" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.blog.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="slug">{{ trans('cruds.blog.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="generated_slug" value="{{ old('slug', '') }}">
                @if($errors->has('slug'))
                    <div class="invalid-feedback">
                        {{ $errors->first('slug') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.blog.fields.slug_helper') }}</span>
            </div>
            <div class="form-group card-data">
                <div class="custom-file">
                    <label class="required" for="image">{{ trans('cruds.blog.fields.image') }}</label>
                    <input type="file" class="custom-file-input {{ $errors->has('image') ? 'is-invalid' : '' }}" id="customFile" name="image" value="{{ old('image', '') }}" onchange="loadFile(event)">
                    <label class="custom-file-label" for="customFile">upload features image</label>

                    @if($errors->has('image'))
                        <div class="invalid-feedback">
                            {{ $errors->first('image') }}
                        </div>
                    @endif
                     <span class="help-block">{{ trans('cruds.blog.fields.image_helper') }}</span>
                    </div>
             </div>
              <div class="form-group">
                <label for="excerpt">Excerpt</label>
                <textarea class="form-control {{ $errors->has('excerpt') ? 'is-invalid' : '' }}" name="excerpt" id="excerpt">{{ old('excerpt') }}</textarea>
                @if($errors->has('excerpt'))
                    <div class="invalid-feedback">
                        {{ $errors->first('excerpt') }}
                    </div>
                @endif
                
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.blog.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.blog.fields.description_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label for="meta_keyword">{{ trans('cruds.blog.fields.meta_keyword') }}</label>
                <input class="form-control {{ $errors->has('meta_keyword') ? 'is-invalid' : '' }}" type="text" name="meta_keyword" id="meta_keyword" value="{{ old('meta_keyword', '') }}">
                @if($errors->has('meta_keyword'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_keyword') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.blog.fields.meta_keyword_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="meta_description">{{ trans('cruds.blog.fields.meta_description') }}</label>
                <textarea class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" name="meta_description" id="meta_description">{{ old('meta_description') }}</textarea>
                @if($errors->has('meta_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.blog.fields.meta_description_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.blog.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Blog::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', '1') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.blog.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="blog_categories">{{ trans('cruds.blog.fields.blog_category') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('blog_categories') ? 'is-invalid' : '' }}" name="blog_categories[]" id="blog_categories" multiple>
                    @foreach($blog_categories as $id => $blog_category)
                        <option value="{{ $id }}" {{ in_array($id, old('blog_categories', [])) ? 'selected' : '' }}>{{ $blog_category }}</option>
                    @endforeach
                </select>
                @if($errors->has('blog_categories'))
                    <div class="invalid-feedback">
                        {{ $errors->first('blog_categories') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.blog.fields.blog_category_helper') }}</span>
            </div>
            <input type="hidden" name="is_featured" value="0">
            <div class="form-group row">
            <label class="col-md-3 col-form-label">Home Featured</label>
            <div class="col-md-9 col-form-label">
            <div class="form-check form-check-inline mr-1">
            <input name="is_featured" class="form-check-input" id="inline-checkbox1" 
            type="checkbox" 
            value="1">
            <label class="form-check-label" for="inline-checkbox1"></label>
            </div>
            </div>
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

