<div>
    <?php $y=1;?>
    @foreach($videoAttributes as $levelIndex => $level)
    <div class="form-group">
        <label class="required" for="travel_option">Video Type</label>                
        <div class="d-inline-block custom-control custom-radio mr-2">
            <input type="radio" class="custom-control-input videoType_1" 
                name="type_{{$levelIndex}}" id="youtube100{{$levelIndex}}" value="Youtube" required>
            <label class="custom-control-label" for="youtube100{{$levelIndex}}">You Tube</label>
        </div>
        <div class="d-inline-block custom-control custom-radio mr-2">
            <input type="radio" class="custom-control-input videoType_1" name="type_{{$levelIndex}}" id="vimeo{{$levelIndex}}" value="Vimeo">
            <label class="custom-control-label" for="vimeo{{$levelIndex}}">Vimeo</label>
        </div>
       
    </div>
    <div class="form-group">
        <label class="required" for="video_{{$levelIndex}}">Enter Video Url</label>
        <input class="form-control" type="text" name="video[]" id="video_{{$levelIndex}}" value="" required>
    </div>
     @endforeach   
    <div class="form-group pull-right">
        <button wire:click.prevent="addLevelAttribute()" class="btn btn-success" id="btn_add_more" type="button">
            Add More
        </button>
        
    </div>
</div>
