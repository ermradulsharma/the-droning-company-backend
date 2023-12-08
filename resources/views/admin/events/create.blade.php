@extends('layouts.admin')
@section('content')
<style>.profile_pic_box{padding: 15px;border:1px solid #bbb; background: #eee;border-radius: 4px;margin-bottom: 30px;}
.card-data .custom-file img{width:80px; height: 80px;object-fit: contain;border: 1px solid #eee;}</style>
<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.event.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.events.store") }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.CompanyManagement.fields.company_user') }}</label>
                <select class="form-control select2 required" name="user_id" id="user_id" required>
                    <option value="">Select {{ trans('cruds.CompanyManagement.fields.company_user') }}</option>
                    @foreach($users as $id => $user)
                    <option value="{{ $user->id }}">{{ $user->first_name .' '.$user->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.event.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title_to_generate_slug" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                <div class="invalid-feedback">
                    {{ $errors->first('title') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.title_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="slug">{{ trans('cruds.event.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="generated_slug" value="{{ old('slug', '') }}">
                @if($errors->has('slug'))
                <div class="invalid-feedback">
                    {{ $errors->first('slug') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.slug_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="description">{{ trans('cruds.event.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                <div class="invalid-feedback">
                    {{ $errors->first('description') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.description_helper') }}</span>
            </div>

            <div class="row">
                <div class="col-md-3">

                    <div class="form-group">
                        <label>{{ trans('cruds.event.fields.event_type') }}</label>
                        <select class="form-control {{ $errors->has('event_type') ? 'is-invalid' : '' }}" name="event_type" id="event_type">
                            <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\Event::EVENT_TYPES as $key => $label)
                            <option value="{{ $key }}" {{ old('status', '1') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('status'))
                        <div class="invalid-feedback">
                            {{ $errors->first('status') }}
                        </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.event.fields.status_helper') }}</span>
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="event_start">{{ trans('cruds.event.fields.event_start') }}</label>
                        <input class="form-control {{ $errors->has('event_start') ? 'is-invalid' : '' }}" type="datetime-local" name="event_start" id="event_start" value="{{ old('event_start', '') }}">
                        @if($errors->has('event_start'))
                        <div class="invalid-feedback">
                            {{ $errors->first('event_start') }}
                        </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.event.fields.event_start_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="event_end">{{ trans('cruds.event.fields.event_end') }}</label>
                        <input class="form-control {{ $errors->has('event_end') ? 'is-invalid' : '' }}" type="datetime-local" name="event_end" id="event_end" value="{{ old('event_end', '') }}">
                        @if($errors->has('event_end'))
                        <div class="invalid-feedback">
                            {{ $errors->first('event_end') }}
                        </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.event.fields.event_end_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cost">{{ trans('cruds.event.fields.cost') }}</label>
                        <input class="form-control {{ $errors->has('cost') ? 'is-invalid' : '' }}" type="text" name="cost" id="cost" value="{{ old('cost', '') }}">
                        @if($errors->has('cost'))
                        <div class="invalid-feedback">
                            {{ $errors->first('cost') }}
                        </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.event.fields.cost_helper') }}</span>
                    </div>
                </div>
            </div>




            <div class="form-group">
                <label for="location">{{ trans('cruds.event.fields.location') }}</label>
                <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" id="location" value="{{ old('location', '') }}">
                @if($errors->has('location'))
                <div class="invalid-feedback">
                    {{ $errors->first('location') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.location_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="event_video">{{ trans('cruds.event.fields.event_video') }}</label>
                <input class="form-control {{ $errors->has('event_video') ? 'is-invalid' : '' }}" type="text" name="event_video" id="event_video" value="{{ old('event_video', '') }}">
                @if($errors->has('event_video'))
                <div class="invalid-feedback">
                    {{ $errors->first('event_video') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.event_video_helper') }}</span>
            </div>

            <h4>Event Featured Image</h4>
            <div class="form-group">
                <div class="profile_pic_box">
                    <div class="form-group card-data">
                        <label class="" for="image">Featured {{ trans('cruds.event.fields.image') }}</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input {{ $errors->has('image') ? 'is-invalid' : '' }}" id="image" name="image" value="{{ old('image', '') }}" onchange="loadFile1(event)">
                            <label class="custom-file-label" for="image">Upload Featured {{ trans('cruds.event.fields.image') }}</label>
                            @if($errors->has('image'))
                                <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group card-data">
                        <div class="custom-file" style="height: auto;">
                            <img id="output_image" title="Preview Image" src="{{ asset('pilotNoImage.png') }}" alt="Featured {{ trans('cruds.event.fields.image') }}" width="100">
                        </div>
                    </div>
                </div>
            </div>

            <h4>Event Gallery</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="profile_pic_box">
                        <div class="form-group card-data">
                            <label class="" for="gallery_img_1">Gallery Image 1</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('gallery_img_1') ? 'is-invalid' : '' }}" id="gallery_img_1" name="gallery_img_1" value="{{ old('gallery_img_1', '') }}" onchange="loadFile2(event)">
                                <label class="custom-file-label" for="gallery_img_1">Upload Gallery Image 1</label>
                                @if($errors->has('gallery_img_1'))
                                    <div class="invalid-feedback">{{ $errors->first('gallery_img_1') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group card-data">
                            <div class="custom-file" style="height: auto;">
                                <img id="output_gallery_img_1" title="Preview Image" src="{{ asset('pilotNoImage.png') }}" alt="gallery_img_1 Preview Image" width="100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="profile_pic_box">
                        <div class="form-group card-data">
                            <label class="" for="gallery_img_2">Gallery Image 2</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('gallery_img_2') ? 'is-invalid' : '' }}" id="gallery_img_2" name="gallery_img_2" value="{{ old('gallery_img_2', '') }}" onchange="loadFile3(event)">
                                <label class="custom-file-label" for="gallery_img_2">Upload Gallery Image 2</label>
                                @if($errors->has('gallery_img_2'))
                                    <div class="invalid-feedback">{{ $errors->first('gallery_img_2') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group card-data">
                            <div class="custom-file" style="height: auto;">
                                <img id="output_gallery_img_2" title="Preview Image" src="{{ asset('pilotNoImage.png') }}" alt="Preview gallery_img_2 Image" width="100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="profile_pic_box">
                        <div class="form-group card-data">
                            <label class="" for="gallery_img_3">Gallery Image 3</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('gallery_img_3') ? 'is-invalid' : '' }}" id="gallery_img_3" name="gallery_img_3" value="{{ old('gallery_img_3', '') }}" onchange="loadFile4(event)">
                                <label class="custom-file-label" for="gallery_img_3">Upload Gallery Image 3</label>
                                @if($errors->has('gallery_img_3'))
                                    <div class="invalid-feedback">{{ $errors->first('gallery_img_3') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group card-data">
                            <div class="custom-file" style="height: auto;">
                                <img id="output_gallery_img_3" title="Preview Image" src="{{ asset('pilotNoImage.png') }}" alt="Preview gallery_img_3 Image" width="100">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="profile_pic_box">
                        <div class="form-group card-data">
                            <label class="" for="gallery_img_4">Gallery Image 4</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('gallery_img_4') ? 'is-invalid' : '' }}" id="gallery_img_4" name="gallery_img_4" value="{{ old('gallery_img_4', '') }}" onchange="loadFile5(event)">
                                <label class="custom-file-label" for="gallery_img_4">Upload Gallery Image 4</label>
                                @if($errors->has('gallery_img_4'))
                                    <div class="invalid-feedback">{{ $errors->first('gallery_img_4') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group card-data">
                            <div class="custom-file" style="height: auto;">
                                <img id="output_gallery_img_4" title="Preview Image" src="{{ asset('pilotNoImage.png') }}" alt="Preview gallery_img_4 Image" width="100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="profile_pic_box">
                        <div class="form-group card-data">
                            <label class="" for="gallery_img_5">Gallery Image 5</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('gallery_img_5') ? 'is-invalid' : '' }}" id="gallery_img_5" name="gallery_img_5" value="{{ old('gallery_img_5', '') }}" onchange="loadFile6(event)">
                                <label class="custom-file-label" for="gallery_img_5">Upload Gallery Image 5</label>
                                @if($errors->has('gallery_img_5'))
                                    <div class="invalid-feedback">{{ $errors->first('gallery_img_5') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group card-data">
                            <div class="custom-file" style="height: auto;">
                                <img id="output_gallery_img_5" title="Preview Image" src="{{ asset('pilotNoImage.png') }}" alt="Preview gallery_img_5 Image" width="100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="profile_pic_box">
                        <div class="form-group card-data">
                            <label class="" for="gallery_img_6">Gallery Image 6</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('gallery_img_6') ? 'is-invalid' : '' }}" id="gallery_img_6" name="gallery_img_6" value="{{ old('gallery_img_6', '') }}" onchange="loadFile7(event)">
                                <label class="custom-file-label" for="gallery_img_6">Upload Gallery Image 6</label>
                                @if($errors->has('gallery_img_6'))
                                    <div class="invalid-feedback">{{ $errors->first('gallery_img_6') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group card-data">
                            <div class="custom-file" style="height: auto;">
                                <img id="output_gallery_img_6" title="Preview Image" src="{{ asset('pilotNoImage.png') }}" alt="Preview gallery_img_6 Image" width="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="form-group">
                <label for="meta_title">{{ trans('cruds.event.fields.meta_title') }}</label>
                <input class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', '') }}">
                @if($errors->has('meta_title'))
                <div class="invalid-feedback">
                    {{ $errors->first('meta_title') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.meta_title_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="meta_keyword">{{ trans('cruds.event.fields.meta_keyword') }}</label>
                <input class="form-control {{ $errors->has('meta_keyword') ? 'is-invalid' : '' }}" type="text" name="meta_keyword" id="meta_keyword" value="{{ old('meta_keyword', '') }}">
                @if($errors->has('meta_keyword'))
                <div class="invalid-feedback">
                    {{ $errors->first('meta_keyword') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.meta_keyword_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="meta_description">{{ trans('cruds.event.fields.meta_description') }}</label>
                <textarea class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" name="meta_description" id="meta_description">{{ old('meta_description') }}</textarea>
                @if($errors->has('meta_description'))
                <div class="invalid-feedback">
                    {{ $errors->first('meta_description') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.meta_description_helper') }}</span>
            </div>

            <div class="form-group">
                <label>{{ trans('cruds.event.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Event::STATUS_SELECT as $key => $label)
                    <option value="{{ $key }}" {{ old('status', '1') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                <div class="invalid-feedback">
                    {{ $errors->first('status') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.event.fields.status_helper') }}</span>
            </div>

            <input type="hidden" name="is_featured" value="0">
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Featured</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input name="is_featured" class="form-check-input" id="inline-checkbox1" type="checkbox" value="1">
                        <label class="form-check-label" for="inline-checkbox1"></label>
                    </div>
                </div>
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

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&language=en&output=json&key={{ env('GOOGLE_API_KEY') }}" async defer></script>
<script type="text/javascript">
  function initAutocomplete() {
    var address = document.getElementById('location');
    var options = {componentRestrictions: {country: ['us']}};
    var autocomplete = new google.maps.places.Autocomplete(address, options);
  }

function loadFile1(event){
    var output2 = document.getElementById('output_image');
    output2.src = URL.createObjectURL(event.target.files[0]);
    output2.onload = function() {
        URL.revokeObjectURL(output2.src) // free memory
    }
};
function loadFile2(event){
    var output2 = document.getElementById('output_gallery_img_1');
    output2.src = URL.createObjectURL(event.target.files[0]);
    output2.onload = function() {
        URL.revokeObjectURL(output2.src) // free memory
    }
};
function loadFile3(event){
    var output2 = document.getElementById('output_gallery_img_2');
    output2.src = URL.createObjectURL(event.target.files[0]);
    output2.onload = function() {
        URL.revokeObjectURL(output2.src) // free memory
    }
};
function loadFile4(event){
    var output2 = document.getElementById('output_gallery_img_3');
    output2.src = URL.createObjectURL(event.target.files[0]);
    output2.onload = function() {
        URL.revokeObjectURL(output2.src) // free memory
    }
};
function loadFile5(event){
    var output2 = document.getElementById('output_gallery_img_4');
    output2.src = URL.createObjectURL(event.target.files[0]);
    output2.onload = function() {
        URL.revokeObjectURL(output2.src) // free memory
    }
};
function loadFile6(event){
    var output2 = document.getElementById('output_gallery_img_5');
    output2.src = URL.createObjectURL(event.target.files[0]);
    output2.onload = function() {
        URL.revokeObjectURL(output2.src) // free memory
    }
};
function loadFile7(event){
    var output2 = document.getElementById('output_gallery_img_6');
    output2.src = URL.createObjectURL(event.target.files[0]);
    output2.onload = function() {
        URL.revokeObjectURL(output2.src) // free memory
    }
};
</script>
@endsection