@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.services.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.services.update', $service->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <input class="form-control" type="hidden" name="service_id" value="{{ $service->id }}" readonly>
            </div>
            
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.services.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $service->title) }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.services.fields.service_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="is_featured">Status</label>                
                <div class="d-inline-block custom-control custom-radio mr-3">
                    <input type="radio" class="custom-control-input" name="status" id="radio7" value="1" {{ ($service->status) == "1" ? "checked" : "" }}>
                    <label class="custom-control-label" for="radio7">Active</label>
                </div>
                <div class="d-inline-block custom-control custom-radio mr-3">
                    <input type="radio" class="custom-control-input" name="status" id="radio8" value="0" {{ ($service->status) == "0" ? "checked" : "" }}>
                    <label class="custom-control-label" for="radio8">In Active</label>
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