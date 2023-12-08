@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.coupons.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.coupons.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th>
                        <td>
                            {{ $coupon->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.Coupons.fields.coupon_name') }}
                        </th>
                        <td>
                            {{ ucwords(strtolower($coupon->coupon_name)) ?? '' }}
                        </td>
                    </tr>
                    
                    <tr>
                        <th>
                            {{ trans('cruds.Coupons.fields.coupons_type') }}
                        </th>
                        <td>
                            {{ App\Models\Coupon::COUPON_TYPE[$coupon->coupon_type] }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Discount Value
                        </th>
                        <td>
                            {{ $coupon->discount}} {{ App\Models\Coupon::COUPON_TYPE[$coupon->coupon_type] }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.Coupons.fields.start_date') }}
                        </th>
                        <td>
                            {{ $coupon->start_date->format('d-m-Y') ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.Coupons.fields.end_date') }}
                        </th>
                        <td>
                            {{ $coupon->end_date->format('d-m-Y') ?? '' }}
                        </td>
                    </tr>
                    
                    <tr>
                        <th>
                            {{ trans('cruds.Coupons.fields.status') }}
                        </th>
                        <td>
                            
                            @if( $coupon->status == '1' )
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">InActive</span>
                            @endif
                        </td>
                    </tr>
                    
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.coupons.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
