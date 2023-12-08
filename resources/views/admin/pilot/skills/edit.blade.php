@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.pilotSkills.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.skills.update", $skill->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <input class="form-control" type="hidden" name="skill_id" value="{{ $skill->id }}" readonly>
            </div>
            
            <div class="form-group">
                <label class="required" for="address1">{{ trans('cruds.pilotSkills.fields.skill') }}</label>
                <input class="form-control {{ $errors->has('skill') ? 'is-invalid' : '' }}" type="text" name="skill_name" id="skill_name" value="{{ old('skill_name', $skill->skill_name) }}" required>
                @if($errors->has('skill'))
                    <div class="invalid-feedback">
                        {{ $errors->first('skill') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotSkills.fields.skill_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="is_featured">Status</label>                
                <div class="d-inline-block custom-control custom-radio mr-3">
                    <input type="radio" class="custom-control-input" name="status" id="radio7" value="1" {{ ($skill->status) == "1" ? "checked" : "" }}>
                    <label class="custom-control-label" for="radio7">Active</label>
                </div>
                <div class="d-inline-block custom-control custom-radio mr-3">
                    <input type="radio" class="custom-control-input" name="status" id="radio8" value="0" {{ ($skill->status) == "0" ? "checked" : "" }}>
                    <label class="custom-control-label" for="radio8">In Active</label>
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