@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Build {{ trans('cruds.pilotProfile.title') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.pilot.store") }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label class="required" for="country">{{ trans('cruds.pilotProfile.fields.pilot') }}</label>                
                <select class="form-control select2 required" name="user_id" id="user_id" required>
                    <option value="">Select Pilot User</option>
                    @foreach($users as $id => $user)
                            <option value="{{ $user->id }}" >{{ $user->first_name .' '.$user->last_name }}</option>
                    @endforeach
                </select>                
            </div>
             <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="hidden" name="slug" id="slug">

           
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.pilotProfile.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.title_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label class="required" for="pilot_rate">{{ trans('cruds.pilotProfile.fields.profile_rate') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="number" name="rate" id="rate" required>
                @if($errors->has('pilot_rate'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pilot_rate') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.profile_rate_helper') }}</span>
            </div>
            <div class="form-group card-data">
                <div class="custom-file">
                    <img id="output" title="Preview Image" src="{{ asset('pilotNoImage.png') }}" class="rounded-circle"  alt="Preview Image" width="100">
                 </div>
             </div>
            <br>
            <br>
            <br>
            <div class="form-group card-data">
                <div class="custom-file">
                    <label class="required" for="image">{{ trans('cruds.pilotProfile.fields.image') }}</label>
                    <input type="file" class="custom-file-input {{ $errors->has('image') ? 'is-invalid' : '' }}" id="customFile" name="image" value="{{ old('image', '') }}" onchange="loadFile(event)" required >
                    <label class="custom-file-label" for="customFile">Upload Pilot Profile Photo</label>

                    @if($errors->has('image'))
                        <div class="invalid-feedback">
                            {{ $errors->first('image') }}
                        </div>
                    @endif
                    </div>
             </div>
            
            
            <div class="form-group card-data">
                <div class="custom-file">
                    <label class="required" for="license_image">License Image</label>
                    <input type="file" class="custom-file-input {{ $errors->has('license_image') ? 'is-invalid' : '' }}" id="customFile1" name="license_image" value="{{ old('license_image', '') }}">
                    <label class="custom-file-label" for="customFile1">Upload Pilot License Image</label>

                    @if($errors->has('license_image'))
                        <div class="invalid-feedback">
                            {{ $errors->first('license_image') }}
                        </div>
                    @endif
                    </div>
            </div>
            
            <div class="form-group">
                <label class="required" for="roles">{{ trans('cruds.user.fields.skill') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('skill') ? 'is-invalid' : '' }}" name="skill[]" id="skill" multiple required>
                    @foreach($skills as $id => $skill)
                        <option value="{{ $id }}" {{ in_array($id, old('skill', [])) ? 'selected' : '' }}>{{ $skill }}</option>
                    @endforeach
                </select>
                @if($errors->has('skill'))
                    <div class="invalid-feedback">
                        {{ $errors->first('skill') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.skill_helper') }}</span>
            </div>
            

            <div class="form-group">
                <label class="required" for="description">Short Description</label>
                <textarea  class="form-control ckeditor {{ $errors->has('short_description') ? 'is-invalid' : '' }}" name="short_description"></textarea>
                
                @if($errors->has('short_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('short_description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.short_description_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label for="description">{{ trans('cruds.pilotProfile.fields.description') }}</label>
                <textarea rows="4" style="resize: none; width: 100%" name="description" class=" ckeditor form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"></textarea>
                
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.description_helper') }}</span>
            </div>
            

            <div class="form-group">
                <label for="metatitle">{{ trans('cruds.pilotProfile.fields.metatitle') }}</label>
                <input class="form-control {{ $errors->has('metatitle') ? 'is-invalid' : '' }}" type="text" name="metatitle" id="metatitle">
                @if($errors->has('metatitle'))
                    <div class="invalid-feedback">
                        {{ $errors->first('metatitle') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.metatitle_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="metatitle">{{ trans('cruds.pilotProfile.fields.metakeyword') }}</label>
                <input class="form-control {{ $errors->has('metakeyword') ? 'is-invalid' : '' }}" type="text" name="metakeyword" id="metakeyword">
                @if($errors->has('metakeyword'))
                    <div class="invalid-feedback">
                        {{ $errors->first('metakeyword') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.metakeyword_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label for="metadescription">{{ trans('cruds.pilotProfile.fields.metadescription') }}</label>
                <textarea name="metadescription" class="form-control {{ $errors->has('metadescription') ? 'is-invalid' : '' }}" rows="4" style="resize: none; width: 100%"></textarea>
                
                @if($errors->has('metadescription'))
                    <div class="invalid-feedback">
                        {{ $errors->first('metadescription') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.metadescription_helper') }}</span>
            </div>
            
            
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{ trans('cruds.pilotProfile.fields.is_certified') }}</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_certified1" type="radio" value="Yes" name="is_certified">
                        <label class="form-check-label" for="radio_certified1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_certified2" type="radio" value="No" name="is_certified" checked>
                        <label class="form-check-label" for="radio_certified2">No</label>
                    </div>
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-md-3 col-form-label required">{{ trans('cruds.pilotProfile.fields.travel_option') }}</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_travel1" type="radio" value="Yes" name="travel_option" checked>
                        <label class="form-check-label" for="radio_travel1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_travel2" type="radio" value="No" name="travel_option">
                        <label class="form-check-label" for="radio_travel2">No</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label required">{{ trans('cruds.pilotProfile.fields.is_featured') }}</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_is_featured1" type="radio" value="Yes" name="is_featured">
                        <label class="form-check-label" for="radio_is_featured1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_is_featured2" type="radio" value="No" name="is_featured" checked>
                        <label class="form-check-label" for="radio_is_featured2">No</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label required">Home Featured</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_is_home_featured1" type="radio" value="1" name="home_featured">
                        <label class="form-check-label" for="radio_is_home_featured1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_is_home_featured2" type="radio" value="0" name="home_featured" checked>
                        <label class="form-check-label" for="radio_is_home_featured2">No</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label required">Is Insured</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_is_is_insured1" type="radio" value="1" name="is_insured">
                        <label class="form-check-label" for="radio_is_is_insured1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_is_is_insured2" type="radio" value="0" name="is_insured" checked>
                        <label class="form-check-label" for="radio_is_is_insured2">No</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label required">Status</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_status1" type="radio" value="1" name="status" checked>
                        <label class="form-check-label" for="radio_status1">Activate</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_status2" type="radio" value="0" name="status">
                        <label class="form-check-label" for="radio_status2">Deactivate</label>
                    </div>
                </div>
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


@endsection
@section('scripts')
@parent
<script type="text/javascript">
    
$( document ).ready(function() {


    $("#user_id").change(function(){

        url = "{{ route('admin.pilot.getSlug') }}";
        var id = $(this).val();
        var pass = { ids: id, _method: 'GET' };
        
        $.ajax({
            headers: {'x-csrf-token': _token},
            method: 'GET',
            url: url,
            data: { sendInfo: pass },
            success: function(responseText)
            {
                var result = $.parseJSON(responseText);
                $('#slug').val(result.slug);
            },
            error: function(data){
                alert("fail---->"+JSON.stringify(data));
            }    
        });
        
    });
});
</script>
@endsection
