@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.Coupons.title_singular') }}
    </div>
    
    <div class="card-body">
        <form method="POST" action="{{ route("admin.coupons.update", [$coupon->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.Coupons.fields.coupon_name') }}</label>
                <input class="form-control {{ $errors->has('coupon_name') ? 'is-invalid' : '' }}" type="text" name="coupon_name" id="coupon_name" value="{{ old('coupon_name', $coupon->coupon_name) }}" required>
                @if($errors->has('coupon_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('coupon_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.Coupons.fields.coupon_name_helper') }}</span>
            </div>
            
            <div class="form-group row">
                <label class="col-md-3 col-form-label required">{{ trans('cruds.Coupons.fields.coupons_type') }}</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_coupons_type1" type="radio" value="1" name="coupon_type" {{ ($coupon->coupon_type) == "1" ? "checked" : "" }} required>
                        <label class="form-check-label" for="radio_coupons_type1">Percentage</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_coupons_type2" type="radio" value="2" name="coupon_type" {{ ($coupon->coupon_type) == "2" ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_coupons_type2">Fixed</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="required" for="discount">Discount Value</label>
                <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="number" name="discount" id="discount" value="{{ old('discount',$coupon->discount) }}" required>
                @if($errors->has('discount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('discount') }}
                    </div>
                @endif
                
            </div>
            
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.Coupons.fields.start_date') }}</label>
                <input class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}" id="start_date" type="text" name="start_date" value="{{ old('start_date', $coupon->start_date->format('Y-m-d')) }}"  required>
                @if($errors->has('start_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('start_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.Coupons.fields.start_date_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.Coupons.fields.end_date') }}</label>
                <input class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}" type="text" name="end_date" id="end_date" value="{{ old('end_date', $coupon->end_date->format('Y-m-d')) }}"  required>
                @if($errors->has('end_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('end_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.Coupons.fields.end_date_helper') }}</span>
            </div>
            
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{ trans('cruds.Coupons.fields.status') }}</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_status1" type="radio" value="1" name="status" {{ ($coupon->status) == "1" ? "checked" : "" }} required>
                        <label class="form-check-label" for="radio_status1">Active</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_status2" type="radio" value="0" name="status" {{ ($coupon->status) == "0" ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_status2">Inactive</label>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <button class="btn btn-success" type="submit">
                    {{ trans('global.update') }}
                </button>
                <a href="{{ route('admin.coupons.index') }}">
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

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
 
$( document ).ready(function() {
    
    $( "#start_date" ).datepicker({changeYear: true, dateFormat: 'yy-mm-dd', minDate:0 });
    $( "#end_date" ).datepicker({changeYear: true, dateFormat: 'yy-mm-dd', minDate:0 });
    
});

</script>
@endsection
