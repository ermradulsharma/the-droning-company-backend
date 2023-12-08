@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
       Add Video Gallery
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.gear_review.store") }}" enctype="multipart/form-data" onsubmit="return checkValidation();"> 
            @csrf
            
            <div class="form-group">
                <label class="required" for="title">Enter Youtube Video Name</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label class="required" for="title">Enter Youtube Video Url</label>
                <input class="form-control {{ $errors->has('video') ? 'is-invalid' : '' }}" type="text" name="video" id="video" required>
                @if($errors->has('video'))
                    <div class="invalid-feedback">
                        {{ $errors->first('video') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.video_helper') }}</span>
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

<script type="text/javascript">
function checkValidation()
{

    var videoUrl = $('#video').val();

    if (!ValidateURL(videoUrl))
    {
        $('#video').focus();
        $('.help-block').html("Please enter a valid URL");
        return false;
    } 
    else 
    {
        $('.help-block').html("");
        return  true;
    }
}
    
function ValidateURL(urlToCheck) 
{
    // Below regular expression can validate input URL with or without http:// etc
    var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ //port
            '(\\?[;&amp;a-z\\d%_.~+=-]*)?'+ // query string
            '(\\#[-a-z\\d_]*)?$','i');;
    return pattern.test(urlToCheck);
}    
    
    
</script>
