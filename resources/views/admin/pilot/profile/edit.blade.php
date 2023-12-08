@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.pilotManagement.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.pilot.update", $user->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <input class="form-control" type="hidden" name="profile_id" value="{{ $user->id }}">
            </div>
            <input type="hidden" name="user_id" value="{{$user->user_id}}">
            <div class="form-group">
                <label class="required" for="first_name">Last Name</label>
                <input name="first_name" class="form-control" type="text" value="{{$user->userOne->first_name ?? ''}}" >
            </div>
            <div class="form-group">
                <label class="required" for="last_name">Last Name</label>
                <input name="last_name" class="form-control" type="text" value="{{$user->userOne->last_name ?? ''}}" >
            </div>
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.pilotProfile.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $user->title) }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.title_helper') }}</span>
            </div>
           
            <div class="form-group">
                <label class="required" for="profile_rate">{{ trans('cruds.pilotProfile.fields.profile_rate') }}</label>
                <input class="form-control {{ $errors->has('profile_rate') ? 'is-invalid' : '' }}" type="text" name="rate" id="rate" value="{{ old('title', $pilot_rate) }}" required>
                @if($errors->has('profile_rate'))
                    <div class="invalid-feedback">
                        {{ $errors->first('profile_rate') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.title_helper') }}</span>
            </div>
            
            <div class="form-group card-data">
                <div class="custom-file">
                    <img id="output" title="Preview Image" src="{{ asset($user->image) ?? '' }}" class="rounded-circle"  alt="Preview Image" width="100">
                 </div>
             </div>
            <br>
            
            <div class="form-group card-data">
                <div class="custom-file">
                    <label class="required" for="image">{{ trans('cruds.pilotProfile.fields.image') }}</label>
                    <input type="file" class="custom-file-input {{ $errors->has('image') ? 'is-invalid' : '' }}" id="customFile" name="image" value="{{ old('image', '') }}" onchange="loadFile(event)">
                    <label class="custom-file-label" for="customFile">Upload Pilot Profile Gallery</label>

                    @if($errors->has('image'))
                        <div class="invalid-feedback">
                            {{ $errors->first('image') }}
                        </div>
                    @endif
                    </div>
            </div>

                @if($user->license_image)
                 <div class="form-group card-data">
                <div class="custom-file">
                    <img id="output1" title="Preview Image" src="{{$user->license_image}}" class="rounded-circle"  alt="Preview Image" width="100">
                 </div>
             </div>
            <br>
            @endif
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
                        <option value="{{ $id }}" {{ (in_array($id, old('skill', [])) || (in_array($id, $total_skills))) ? 'selected' : '' }}>{{ $skill }}</option>
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
                <label class="required" for="short_description">{{ trans('cruds.pilotProfile.fields.short_description') }}</label>
                <textarea rows="10" name="short_description" class="ckeditor form-control {{ $errors->has('short_description') ? 'is-invalid' : '' }}" rows="4" style="resize: none; width: 100%">{{ old('short_description', $user->short_description) }}</textarea>
                
                @if($errors->has('short_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('short_description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.short_description_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label class="required" for="description">{{ trans('cruds.pilotProfile.fields.description') }}</label>
                <textarea name="description" class="ckeditor form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" rows="4" style="resize: none; width: 100%">{{ old('description', $user->description) }}</textarea>
                
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.description_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label for="metatitle">{{ trans('cruds.pilotProfile.fields.metatitle') }}</label>
                <input class="form-control {{ $errors->has('metatitle') ? 'is-invalid' : '' }}" type="text" name="metatitle" id="metatitle" value="{{ old('metatitle', $user->metatitle) }}">
                @if($errors->has('metatitle'))
                    <div class="invalid-feedback">
                        {{ $errors->first('metatitle') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.metatitle_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="metatitle">{{ trans('cruds.pilotProfile.fields.metakeyword') }}</label>
                <input class="form-control {{ $errors->has('metakeyword') ? 'is-invalid' : '' }}" type="text" name="metakeyword" id="metakeyword" value="{{ old('metakeyword', $user->metakeyword) }}">
                @if($errors->has('metakeyword'))
                    <div class="invalid-feedback">
                        {{ $errors->first('metakeyword') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.metakeyword_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label for="metadescription">{{ trans('cruds.pilotProfile.fields.metadescription') }}</label>
                <textarea name="metadescription" class="form-control {{ $errors->has('metadescription') ? 'is-invalid' : '' }}" rows="4" style="resize: none; width: 100%">{{ old('metadescription', $user->metadescription) }}</textarea>
                
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
                        <input class="form-check-input" id="radio_certified1" type="radio" value="Yes" name="is_certified" {{ ($user->is_certified) == "Yes" ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_certified1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_certified2" type="radio" value="No" name="is_certified" {{ ($user->is_certified) == "No" ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_certified2">No</label>
                    </div>
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-md-3 col-form-label required">{{ trans('cruds.pilotProfile.fields.travel_option') }}</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_travel1" type="radio" value="Yes" name="travel_option" {{ ($user->travel_option) == "Yes" ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_travel1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_travel2" type="radio" value="No" name="travel_option" {{ ($user->travel_option) == "No" ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_travel2">No</label>
                    </div>
                </div>
            </div>
            
            
            <div class="form-group row">
                <label class="col-md-3 col-form-label required">{{ trans('cruds.pilotProfile.fields.is_featured') }}</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_is_featured1" type="radio" value="Yes" name="is_featured" {{ ($user->is_featured) == "Yes" ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_is_featured1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_is_featured2" type="radio" value="No" name="is_featured" {{ ($user->is_featured) == "No" ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_is_featured2">No</label>
                    </div>
                </div>
            </div>
             <div class="form-group row">
                <label class="col-md-3 col-form-label required">Home featured</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_is_home_featured1" type="radio" 
                        value="1" name="home_featured" {{ ($user->home_featured) ==1 ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_is_home_featured1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_is_home_featured2" type="radio" value="0" name="home_featured" {{ ($user->home_featured) ==0 ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_is_home_featured2">No</label>
                    </div>
                </div>
            </div>

             <div class="form-group row">
                <label class="col-md-3 col-form-label required">Is Insured</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_is_is_insured1" type="radio" 
                        value="1" name="is_insured" {{ ($user->is_insured) ==true ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_is_is_insured1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_is_is_insured2" type="radio" value="0" name="is_insured" {{ ($user->is_insured) ==false ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_is_is_insured2">No</label>
                    </div>
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-md-3 col-form-label required">Status</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_status1" type="radio" value="1" name="status" {{ ($user->status) == "1" ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_status1">Activate</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_status2" type="radio" value="0" name="status" {{ ($user->status) == "0" ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_status2">Deactivate</label>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <button class="btn btn-success" type="submit">
                    {{ trans('global.update') }}
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

