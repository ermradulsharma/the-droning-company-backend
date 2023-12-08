@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
       Add Photo Gallery
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.photo_gallery.store") }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
             <div class="custom-file">
                <input type="file" class="custom-file-input {{ $errors->has('image') ? 'is-invalid' : '' }}" id="customFile" name="image[]" value="{{ old('image', '') }}" required>
                <label class="custom-file-label" for="customFile">Upload Gallery</label>

                @if($errors->has('image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('image') }}
                    </div>
                @endif
              </div>
          </div>
          <div class="form-group">
                <label class="required" for="image_text">Image Text</label>
                <input class="form-control {{ $errors->has('image_text') ? 'is-invalid' : '' }}" type="text" name="image_text" id="image_text" value="{{ old('image_text','') }}">
                @if($errors->has('image_text'))
                    <div class="invalid-feedback">
                        {{ $errors->first('image_text') }}
                    </div>
                @endif
                
            </div>

            <div class="form-group">
                <label class="required" for="image_link">Image Link</label>
                <input class="form-control {{ $errors->has('image_link') ? 'is-invalid' : '' }}" type="text" name="image_link" id="image_link" value="{{ old('image_link','') }}">
                @if($errors->has('image_link'))
                    <div class="invalid-feedback">
                        {{ $errors->first('image_link') }}
                    </div>
                @endif
                
            </div>

            <div class="form-group">
                <button class="btn btn-primary" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
