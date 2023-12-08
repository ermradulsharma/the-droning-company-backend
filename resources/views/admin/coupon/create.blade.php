@extends('layouts.admin')
@section('content')
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.Coupons.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.coupons.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.Coupons.fields.coupon_name') }}</label>
                <input class="form-control {{ $errors->has('coupon_name') ? 'is-invalid' : '' }}" type="text" name="coupon_name" id="coupon_name" value="{{ old('coupon_name', '') }}" required>
                @if($errors->has('coupon_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('coupon_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.Coupons.fields.coupon_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="coupon_code">Coupon Code <small>(space and special character not allowed)</small></label>
                <input class="form-control {{ $errors->has('coupon_code') ? 'is-invalid' : '' }}" type="text" name="coupon_code" id="coupon_code" value="{{ old('coupon_code', '') }}" required>
                @if($errors->has('coupon_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('coupon_code') }}
                    </div>
                @endif
            </div>    
            <div class="form-group row">
                <label class="col-md-3 col-form-label required">{{ trans('cruds.Coupons.fields.coupons_type') }}</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input labelclassschecked" id="radio_coupons_type1" type="radio" value="1" name="coupon_type" checked>
                        <label class="form-check-label labelclassschecked" for="radio_coupons_type1">Percentage Discount</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input labelclassschecked" id="radio_coupons_type2" type="radio" value="2" name="coupon_type">
                        <label class="form-check-label labelclassschecked" for="radio_coupons_type2">Fixed Amount</label>
                    </div>
                </div>
            </div>
             <div class="form-group">
                <label class="required" for="discount" id="discount_lable">Discount Value</label>
                <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="number" name="discount" id="discount" value="{{ old('discount', '') }}" required>
                @if($errors->has('discount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('discount') }}
                    </div>
                @endif
            </div>
            
            <div class="form-group">
                <button class="btn btn-success" type="submit">
                    {{ trans('global.add') }}
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

    $('labelclassschecked').on('click',function(){

        var discount=$('#coupon_type').val();
        alert(discount);
        if(discount==1){
          //  $('#discount_lable').
            document.getElementById('discount_lable').innerHTML = 'Your tip has been submitted!';
        }else {
           document.getElementById('discount_lable').innerHTML = 'Your tip has been submitted!'; 
        }

    })
    
    $( "#start_date" ).datepicker({changeYear: true, dateFormat: 'yy-mm-dd', minDate:0 });
    $( "#end_date" ).datepicker({changeYear: true, dateFormat: 'yy-mm-dd', minDate:0 });
    
});

</script>
@endsection
