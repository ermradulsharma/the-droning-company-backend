@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.pilotGallery.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.company-galleries.update", [$companyGallery->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <input type="hidden" name="pilot_profile_id" value="{{$companyGallery->pilot_profile_id}}">
            <div class="form-group">
                <label>Uploaded Gallery</label><br>
             <img class="rounded-circle" src="{{ asset($companyGallery->image) }}" alt="" width="100">
            </div>

            <div class="form-group card-data">
                <label>Preview Image</label><br>
                <div class="custom-file">
                    <img id="output" title="Preview Image" src="{{ asset('images/no_image.jpg') }}" class="rounded-circle"  alt="Preview Image" width="100">
                 </div>
            </div>
            <br>
            <br>
            
            <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input {{ $errors->has('image') ? 'is-invalid' : '' }}" id="customFile" name="image" value="{{ old('image', '') }}" required onchange="loadFile(event)">
                    <label class="custom-file-label" for="customFile">Upload Pilot Profile Gallery</label>

                    @if($errors->has('image'))
                        <div class="invalid-feedback">
                            {{ $errors->first('image') }}
                        </div>
                    @endif
                </div>
            </div>

            
            <div class="form-group">
                <label>{{ trans('cruds.pilotGallery.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\CompanyGallery::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $companyGallery->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotGallery.fields.status_helper') }}</span>
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

<script>
var loadFile = function(event) 
{
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() 
    {
        URL.revokeObjectURL(output.src) // free memory
    }
};

</script>
