@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Edit Setting
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.setting.update", [$setting->uuid]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="key_1">Title</label>
                <input class="form-control {{ $errors->has('key_1') ? 'is-invalid' : '' }}" type="text" name="key_1" id="key_1" value="{{ old('key_1', $setting->key_1) }}">
            </div>
            <div class="form-group">
                <label class="required" for="key_2">Sub Title 1</label>
                <input class="form-control {{ $errors->has('key_2') ? 'is-invalid' : '' }}" type="text" name="key_2" id="key_2" value="{{ old('key_2', $setting->key_2) }}">
            </div>
            <div class="form-group">
                <label class="required" for="key_3">Sub Title 2</label>
                <input class="form-control {{ $errors->has('key_3') ? 'is-invalid' : '' }}" type="text" name="key_3" id="key_3" value="{{ old('key_3', $setting->key_3) }}">
            </div>
            <div class="form-group">
                <label class="required" for="value">Description</label>
                <input class="form-control {{ $errors->has('value') ? 'is-invalid' : '' }}" type="text" name="value" id="value" value="{{ old('value', $setting->value) }}">
            </div>
            <div class="form-group">
                <label class="required" for="key_link">Button Link</label>
                <input class="form-control {{ $errors->has('key_link') ? 'is-invalid' : '' }}" type="text" name="key_link" id="key_link" value="{{ old('key_link', $setting->key_link) }}">
            </div>
             <div class="form-group">
                @if($setting->block_image)
                <img src="{{$setting->block_image}}" width="50%" height="50%">
                @endif
             <div class="custom-file">
                <input type="file" class="custom-file-input {{ $errors->has('block_image') ? 'is-invalid' : '' }}" id="customFile" name="block_image" value="{{ old('block_image', '') }}">
                <label class="custom-file-label" for="customFile">Upload Image</label>

                @if($errors->has('block_image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('block_image') }}
                    </div>
                @endif
              </div>
          </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.update') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
