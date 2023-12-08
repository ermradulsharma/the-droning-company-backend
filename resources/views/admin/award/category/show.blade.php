@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.services.title') }}
    </div>

    <div class="card-body">
        
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.services.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.services.fields.id') }}
                        </th>
                        <td>
                            {{ $service->id }}
                        </td>
                    </tr>
                    
                    
                    <tr>
                        <th>
                            {{ trans('cruds.services.fields.title') }}
                        </th>
                        <td>
                            {{ ucwords(strtolower($service->title)) ?? '' }} 
                        </td>
                    </tr>
                    
                    <tr>
                        <th>
                            {{ trans('cruds.services.fields.status') }}
                        </th>
                        <td>
                            @if( $service->status == '1' )
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">In Active</span>
                            @endif
                        </td>
                    </tr>
                    
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.services.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection