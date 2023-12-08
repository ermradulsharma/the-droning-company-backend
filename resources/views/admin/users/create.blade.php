@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.users.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.user.fields.first_name') }}</label>
                <input class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}" type="text" name="first_name" id="first_name" value="{{ old('first_name', '') }}" required>
                @if($errors->has('first_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('first_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.first_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.user.fields.last_name') }}</label>
                <input class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ old('last_name', '') }}" required>
                @if($errors->has('last_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('last_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.last_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="slug">Slug</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="name_slug" id="name_slug" readonly required>
                @if($errors->has('slug'))
                    <div class="invalid-feedback">
                        {{ $errors->first('slug') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.last_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="mobile">Contact Number</label>
                <input class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}" type="mobile" name="mobile" id="mobile" value="{{ old('mobile') }}" required>
                @if($errors->has('mobile'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mobile') }}
                    </div>
                @endif
               
            </div>
            <div class="form-group">
                <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" required>
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label class="required" for="country">{{ trans('cruds.user.fields.country') }}</label>                
                <select class="form-control select2" name="country_id" id="country_id" required>
                    <option value="" >Select Country</option>
                    @foreach($country as $id => $allcountry)
                        <option value="{{ $id }}" >{{ $allcountry }}</option>
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
                        <option value="{{ $id }}" {{ in_array($id, old('roles', [])) ? 'selected' : '' }}>{{ $roles }}</option>
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
                <button class="btn btn-success" type="submit">
                    {{ trans('global.add') }}
                </button>
                <a href="{{ route('admin.users.index') }}">
                    <button class="btn btn-danger" type="button">
                        Back
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

    $("#last_name").keyup(function(){
        var f_name = $('#first_name').val();
        var l_name =  $('#last_name').val();
        var slug, fname, lname;
        f_name = f_name.toLowerCase();
        fname = f_name.split(' ');
        
        l_name = l_name.toLowerCase();
        lname = l_name.split(' ');
        fname[0] = fname[0].concat('-');
        slug =  fname[0].concat(lname[0]);
        
        $("#name_slug").val(slug);        
    });
});

</script>
@endsection
