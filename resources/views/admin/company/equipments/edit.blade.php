@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Build Pilot Equipments
    </div>

    @if (\Session::has('success'))
        <div class="alert alert-success">
            {!! \Session::get('success') !!}
        </div>
    @endif
    <div class="card-body">
        <form method="POST" action="{{ route("admin.pilot_equipments.update", $equipments->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            
            <input class="form-control" type="hidden" name="id" id="id" value="{{ $equipments->id }}" readonly>
            
            <div class="form-group">
                <label class="required" for="title">Title</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('video', $equipments->title) }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.title_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label>Uploaded Image</label><br>
             <img src="{{ asset($equipments->image) ?? asset('images/no_image.jpg') }}" alt="" width="100"  class="rounded-circle" >
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
                   <input type="file" class="custom-file-input {{ $errors->has('image') ? 'is-invalid' : '' }}" id="customFile" name="image" value="{{ old('image', '') }}" onchange="loadFile(event)">
                   <label class="custom-file-label" for="customFile">Upload Pilot Equipment Photo</label>

                   @if($errors->has('image'))
                       <div class="invalid-feedback">
                           {{ $errors->first('image') }}
                       </div>
                   @endif
                </div>
            </div>
            
            <div class="form-group">
                <label class="required" for="title">Manufacturer</label>
                <input class="form-control {{ $errors->has('manufacturer') ? 'is-invalid' : '' }}" type="text" name="manufacturer" id="manufacturer" value="{{ old('video', $equipments->manufacturer) }}" required>
                @if($errors->has('manufacturer'))
                    <div class="invalid-feedback">
                        {{ $errors->first('manufacturer') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.manufacturer_helper') }}</span>
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