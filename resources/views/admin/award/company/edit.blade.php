@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} Award Company
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.award-company.update', $awardCompany->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <input class="form-control" type="hidden" name="company_id" value="{{ $awardCompany->id }}" readonly>
            </div>
			
			
			
			
			<div class="form-group">
                <label class="required" for="title">{{ trans('cruds.services.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $awardCompany->title) }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.services.fields.service_helper') }}</span>
            </div>
			
			<div class="form-group">
                <label class="required" for="title">Categories</label>
				<select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="categories[]" multiple>
					@foreach($categories as $category)
						<option value="{{ $category->id }}" {{ in_array($category->id, $selected_categories) ? 'selected' : '' }}>{{ $category->title }}</option>
					@endforeach
				</select>
                @if($errors->has('category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('category') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.services.fields.service_helper') }}</span>
            </div>
			
			<div class="form-group">
                <label class="required" for="title">URL</label>
                <input class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" type="text" name="url" id="url" value="{{ old('url', $awardCompany->url) }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('url') }}
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