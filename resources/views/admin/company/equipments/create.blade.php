@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Build Pilot Equipments
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.pilot_equipments.store") }}" enctype="multipart/form-data">
            @csrf
            
            <input class="form-control" type="hidden" name="user_id" id="user_id" value="{{ $userId }}" readonly>
            <input class="form-control" type="hidden" name="profile_id" id="profile_id" value="{{ $profileId }}" readonly>
            
            <div class="form-group">
                <label class="required" for="title">Title</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.title_helper') }}</span>
            </div>
            
            <div class="form-group custom-file">
                <input type="file" class="custom-file-input {{ $errors->has('image') ? 'is-invalid' : '' }}" id="customFile" name="image" value="{{ old('image', '') }}" required onchange="loadFile(event)">
                <label class="custom-file-label" for="customFile">Upload Pilot Equipment Photo</label>
                @if($errors->has('image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('image') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.image_helper') }}</span>
            </div>
            <div class="form-group card-data">
                <div class="custom-file">
                    <img id="output" title="Preview Image" src="{{ asset('pilotNoImage.png') ?? '' }}" class="rounded-circle"  alt="Preview Image" width="100">
                 </div>
             </div>
            <br>
            <div class="form-group">
                <label class="required" for="title">Manufacturer</label>
                <input class="form-control {{ $errors->has('manufacturer') ? 'is-invalid' : '' }}" type="text" name="manufacturer" id="manufacturer" required>
                @if($errors->has('manufacturer'))
                    <div class="invalid-feedback">
                        {{ $errors->first('manufacturer') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.manufacturer_helper') }}</span>
            </div>
            
            
            
            <div class="form-group">
                <button class="btn btn-success" type="submit">
                    {{ trans('global.add') }}
                </button>
                <a href="{{ route('admin.pilot.index') }}">
                    <button class="btn btn-danger" type="button">
                        Cancel
                    </button>
                </a>
            </div>
        </form>
    </div>
</div>
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


@endsection
