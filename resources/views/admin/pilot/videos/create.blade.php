@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Build Pilot Video
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.pilot_videos.store") }}" enctype="multipart/form-data" onsubmit="return checkValidation();">
            @csrf
           
            <input class="form-control" type="hidden" name="user_id" id="user_id" value="{{ $userId }}">
            <input class="form-control" type="hidden" name="profile_id" id="profile_id" value="{{ $profileId }}">
            <input class="form-control" type="hidden" name="count" id="total_count" value="1">

             @livewire('pilot-video-add-more')
            <div class="form-group">
                <label class="required" for="travel_option">Video Type</label>                
                <div class="d-inline-block custom-control custom-radio mr-2">
                    <input type="radio" class="custom-control-input videoType_1" name="type_1" id="video_type1" value="Youtube" required>
                    <label class="custom-control-label" for="video_type1">You Tube</label>
                </div>
                <div class="d-inline-block custom-control custom-radio mr-2">
                    <input type="radio" class="custom-control-input videoType_1" name="type_1" id="video_type11" value="Vimeo">
                    <label class="custom-control-label" for="video_type11">Vimeo</label>
                </div>
                <span class="type-block-1"></span>
            </div>
            <div class="form-group">
                <label class="required" for="video_1">Enter Video Url</label>
                <input class="form-control {{ $errors->has('video_1') ? 'is-invalid' : '' }}" type="text" name="video_1" id="video_1" value="{{ old('video_1', '') }}" required>
                @if($errors->has('video_1'))
                    <div class="invalid-feedback">
                        {{ $errors->first('video_1') }}
                    </div>
                @endif
                <span class="help-block-1"></span>
            </div>
            
            <div id="more_result"></div>
            
            
            <div class="form-group pull-right">
                <button class="btn btn-success" id="btn_add_more" type="button">
                    Add More
                </button>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" id="btnVideoSave" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
@section('scripts')
@parent


<script type="text/javascript">
    $(document).ready(function (){
        $("#btn_add_more").click(function(){
           var _token = $("input[name='_token']").val();
           var count = $("#total_count").val();
            $.ajax({
                url : "{{ route('admin.pilot_videos.add_more') }}",
                type:'GET',
                dataType: 'html',
                data: {_token:_token,'count':count},
                success: function(responseText)
                { 
                    // Get the result and asign to each cases
                    var result = $.parseJSON(responseText);
                    var id = result.countId;
                    if(result.status == 1)
                    {
                        $("#more_result").append(result.result);
                        $("#total_count").val('');
                        $("#total_count").val(id);
                    }
                    else
                    {
                        alert(result.message);
                    }
                },
                error: function(data)
                {
                    alert("fail---->");
                }    
            });
        });  
    });
    
function checkValidation()
{
    var count = $('#total_count').val();
    var videoUrl = $('#video_'+count).val();

    if (!ValidateURL(videoUrl))
    {
        $('#video_'+count).focus();
        $('.help-block-'+count).html("Please enter a valid URL");
        return false;
    } 
    else 
    {
        $('.help-block-'+count).html("");
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
@endsection
