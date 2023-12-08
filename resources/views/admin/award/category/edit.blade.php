@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.services.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.award-category.update', $awardCategory->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <input class="form-control" type="hidden" name="category_id" value="{{ $awardCategory->id }}" readonly>
            </div>
            
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.services.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $awardCategory->title) }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.services.fields.service_helper') }}</span>
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