<div>
    <?php $y=1;?>
    @foreach($videoAttributes as $levelIndex => $level)
    <div class="form-group">
        <label class="required" for="travel_option">Video Type</label>
        <div class="d-block custom-control custom-radio mr-2">
            <input type="radio" class="custom-control-input videoType_1" name="type_{{$levelIndex}}" id="youtube100{{$levelIndex}}" value="Youtube" required>
            <label class="custom-control-label" for="youtube100{{$levelIndex}}">You Tube</label>
        </div>
        <div class="d-block custom-control custom-radio mr-2">
            <input type="radio" class="custom-control-input videoType_1" name="type_{{$levelIndex}}" id="vimeo{{$levelIndex}}" value="Vimeo">
            <label class="custom-control-label" for="vimeo{{$levelIndex}}">Vimeo</label>
        </div>
    </div>

    <div class="form-group">
        <label class="required" for="travel_option">Video Position</label>
        <div class="d-block custom-control custom-radio mr-2">
            <input type="radio" class="custom-control-input" name="position_{{$levelIndex}}" id="position_{{$levelIndex}}" value="Main" required>
            <label class="custom-control-label" for="position_{{$levelIndex}}">Main</label>
        </div>
        <div class="d-block custom-control custom-radio mr-2">
            <input type="radio" class="custom-control-input" name="position_{{$levelIndex}}" id="position2_{{$levelIndex}}" value="Gallery">
            <label class="custom-control-label" for="position2_{{$levelIndex}}">Gallery</label>
        </div>
    </div>

    <div class="form-group">
        <label class="required" for="video_{{$levelIndex}}">Enter Video Url</label>
        <input class="form-control" type="text" name="video[]" id="video_{{$levelIndex}}" value="" required>
    </div>
     @endforeach   
    <div class="form-group pull-right">
        <button wire:click.prevent="addLevelAttribute()" class="btn btn-success" id="btn_add_more" type="button">Add More</button>
    </div>
</div>
