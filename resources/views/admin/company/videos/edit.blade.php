@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} Pilot Video
    </div>
    @if (\Session::has('success'))
        <div class="alert alert-success">
            {!! \Session::get('success') !!}
        </div>
    @endif
    <div class="card-body">
        <form method="POST" action="{{ route("admin.company-videos.update", $companyVideo->id) }}" enctype="multipart/form-data" onsubmit="return checkValidation();">
            @method('PUT')
            @csrf
            <div class="form-group">
                <input class="form-control" type="hidden" name="id" value="{{ $companyVideo->id }}" readonly>
            </div>
            
            <div class="form-group">
                <label class="required" for="travel_option">Video Type</label>
                <div class="d-block custom-control custom-radio mr-2">
                    <input type="radio" class="custom-control-input" name="type" id="video_type1" value="Youtube" {{ ($companyVideo->type=="Youtube")? "checked" : "" }}  required>
                    <label class="custom-control-label" for="video_type1">You Tube</label>
                </div>
                <div class="d-block custom-control custom-radio mr-2">
                    <input type="radio" class="custom-control-input" name="type" id="video_type2" value="Vimeo" {{ ($companyVideo->type=="Vimeo")? "checked" : "" }} >
                    <label class="custom-control-label" for="video_type2">Vimeo</label>
                </div>
            </div>

            <div class="form-group">
                <label class="required" for="travel_option">Video Position</label>
                <div class="d-block custom-control custom-radio mr-2">
                    <input type="radio" class="custom-control-input" name="position" id="position1" value="Main" {{ ($companyVideo->position=="Main")? "checked" : "" }}  required>
                    <label class="custom-control-label" for="position1">Main</label>
                </div>
                <div class="d-block custom-control custom-radio mr-2">
                    <input type="radio" class="custom-control-input" name="position" id="position2" value="Gallery" {{ ($companyVideo->position=="Gallery")? "checked" : "" }} >
                    <label class="custom-control-label" for="position2">Gallery</label>
                </div>
            </div>

            <div class="form-group">
                <label class="required" for="title">Enter Video Url</label>
                <input class="form-control {{ $errors->has('video') ? 'is-invalid' : '' }}" type="text" name="video" id="video" value="{{ old('video', $companyVideo->video) }}" required>
                @if($errors->has('video'))
                    <div class="invalid-feedback">
                        {{ $errors->first('video') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.video_helper') }}</span>
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