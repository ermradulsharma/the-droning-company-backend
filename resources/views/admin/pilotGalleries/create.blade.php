@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
       Add Pilot Profile Gallery
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.pilot-galleries.store") }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="pilot_profile_id" value="{{$pilot_profile_id}}">
             <div class="form-group">
             <div class="custom-file">
                <input type="file" class="custom-file-input {{ $errors->has('image') ? 'is-invalid' : '' }}" id="customFile" name="image[]" value="{{ old('image', '') }}" required multiple>
                <label class="custom-file-label" for="customFile">Upload Multiple Pilot Profile Gallery</label>

                @if($errors->has('image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('image') }}
                    </div>
                @endif
              </div>
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
