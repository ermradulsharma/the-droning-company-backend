@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.pilotSkills.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.skills.store") }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label class="required" for="skills">{{ trans('cruds.pilotSkills.fields.skill') }}</label>
                <input class="form-control {{ $errors->has('skills') ? 'is-invalid' : '' }}" type="text" name="skills" id="skills" value="{{ old('skills', '') }}" required>
                @if($errors->has('skills'))
                    <div class="invalid-feedback">
                        {{ $errors->first('skills') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotSkills.fields.skill_helper') }}</span>
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