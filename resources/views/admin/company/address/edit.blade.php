@extends('layouts.admin')
@section('content')

<div class="card">
    @if (\Session::has('success'))
        <div class="alert alert-success">
            {!! \Session::get('success') !!}
        </div>
    @endif
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.pilotAddress.title_singular') }}
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route("admin.pilot_address.update", $user->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <input class="form-control" type="hidden" name="profile_id" value="{{ $user->id }}" readonly>
            </div>
            <div id="loader" style="text-align: center;margin:20px;"></div>
            
           {{--  <div class="form-group">
                <label class="required" for="address1">{{ trans('cruds.pilotAddress.fields.address1') }}</label>
                <input class="form-control {{ $errors->has('address1') ? 'is-invalid' : '' }}" type="text" name="address1" id="address1" value="{{ old('address_line1', $user->address_line1) }}" required>
                @if($errors->has('address1'))
                    <div class="invalid-feedback">
                        {{ $errors->first('address1') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotAddress.fields.address1_helper') }}</span>
            </div> --}}
            
            {{-- <div class="form-group">
                <label class="required" for="address2">{{ trans('cruds.pilotAddress.fields.address2') }}</label>
                <input class="form-control {{ $errors->has('address2') ? 'is-invalid' : '' }}" type="text" name="address2" id="address2" value="{{ old('address_line2', $user->address_line2) }}" required>
                @if($errors->has('address2'))
                    <div class="invalid-feedback">
                        {{ $errors->first('address2') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotAddress.fields.address1_helper') }}</span>
            </div> --}}
            
            <div class="form-group">
                <label class="required" for="city">{{ trans('cruds.pilotAddress.fields.city') }}</label>
                <input class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" type="text" name="city" id="city" value="{{ old('city', $user->city) }}" required>
                @if($errors->has('city'))
                    <div class="invalid-feedback">
                        {{ $errors->first('city') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotAddress.fields.city_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label class="required" for="country">{{ trans('cruds.pilotAddress.fields.country') }}</label>                
                <select class="form-control select2" name="country_id" id="country_id" required>
                    <option value="" >Select Country</option>
                    @foreach($country as $id => $allcountry)
                        <option value="{{ $id }}" {{ ($user->country == $id) ? "selected" : "" }} >{{ $allcountry }}</option>
                    @endforeach
                </select>                
            </div>
            
            <div class="form-group">
                <label class="required" for="state">{{ trans('cruds.pilotAddress.fields.state') }}</label>
                <select class="form-control select2" name="state" id="state" required>
                    <option value="" >Select State</option>
                    @foreach($state as $id => $allState)
                        <option value="{{ $id }}" {{ ($user->state == $id) ? "selected" : "" }} >{{ $allState }}</option>
                    @endforeach
                </select>
                
                @if($errors->has('state'))
                    <div class="invalid-feedback">
                        {{ $errors->first('state') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotAddress.fields.city_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label class="required" for="zip">{{ trans('cruds.pilotAddress.fields.zip') }}</label>
                <input class="form-control {{ $errors->has('zip') ? 'is-invalid' : '' }}" type="text" name="zip" id="zip" value="{{ old('zip', $user->zip) }}" required>
                @if($errors->has('zip'))
                    <div class="invalid-feedback">
                        {{ $errors->first('zip') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotAddress.fields.zip_helper') }}</span>
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
@section('scripts')
@parent
<script type="text/javascript">

$( document ).ready(function() {
    $('#country_id').on('change', function() {
        $("#loader").html('<span class="alert alert-info">Please wait...</span>');
        var country_id = this.value;
        $("#state-dropdown").html('');
        $.ajax({
            url : "{{ route('admin.pilot_address.get-states') }}",
            type: "POST",
            data: { country_id: country_id,_token: '{{csrf_token()}}' },
            dataType : 'json',
            success: function(result)
            {
                $("#loader").html('');
                $('#state').html('<option value="">Select State</option>'); 
                $.each(result.states,function(key,value){
                    $("#state").append('<option value="'+value.id+'">'+value.name+'</option>');
                });
            }
        });
    });    
     
});

</script>
@endsection
