@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.users.update", [$user->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="first_name">{{ trans('cruds.user.fields.first_name') }}</label>
                <input class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}" type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                @if($errors->has('first_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('first_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.first_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="last_name">{{ trans('cruds.user.fields.last_name') }}</label>
                <input class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                @if($errors->has('last_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('last_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.last_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="mobile">Contact Number</label>
                <input class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}" type="mobile" name="mobile" id="mobile" value="{{ old('mobile', $user->mobile) }}" required>
                @if($errors->has('mobile'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mobile') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" value="{{ old('password', $user->password) }}">
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
            </div>
            
            <div class="form-group row">
            <label class="col-md-3 col-form-label required">Password send to user ?</label>
            <div class="col-md-9 col-form-label">
            <div class="form-check form-check-inline mr-1">
            <input class="form-check-input" id="send_password" type="checkbox" name="send_password" id="send_password" value="Yes">
            <label class="form-check-label" for="send_password">Yes</label>
            </div>

            </div>
            </div>
            <div class="form-group">
                <label class="required" for="user_source">Source</label>                
                <select class="form-control" name="registration_source" id="registration_source" required>
                    <option value="" >Select Certification</option>                    
                        <option value="Admin" {{ ($user->registration_source) == "Admin" ? "selected" : "" }} >Admin </option>
                        <option value="Frontend" {{ ($user->registration_source) == "Frontend" ? "selected" : "" }} >Frontend</option>                    
                </select>                
            </div>
            
            <div class="form-group">
                <label class="required" for="country">{{ trans('cruds.user.fields.country') }}</label>                
                <select class="form-control select2" name="country_id" id="country_id" required>
                    <option value="" >Select Country</option>
                    @foreach($country as $id => $allcountry)
                        <option value="{{ $id }}"  {{ $user->country_id == $id ? "selected" : "" }} >{{ $allcountry }}</option>
                    @endforeach
                </select>
                
            </div>
            
            <div class="form-group">
                <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]" id="roles" multiple required>
                    @foreach($roles as $id => $roles)
                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'selected' : '' }}>{{ $roles }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <div class="invalid-feedback">
                        {{ $errors->first('roles') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.update') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
