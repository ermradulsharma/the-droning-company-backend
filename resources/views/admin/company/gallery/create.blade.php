@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
       Add Company Profile Gallery
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.company-galleries.store") }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="company_id" value="{{$company_id}}">
             <div class="form-group">
             <div class="custom-file">
                <input type="file" class="custom-file-input {{ $errors->has('image') ? 'is-invalid' : '' }}" id="customFile" name="image[]" value="{{ old('image', '') }}" required multiple>
                <label class="custom-file-label" for="customFile">Upload Multiple Gallery Images</label>

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
