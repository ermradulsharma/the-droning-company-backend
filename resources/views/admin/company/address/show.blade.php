@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.pilotAddress.title') }}
    </div>

    <div class="card-body">
        
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.pilot.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.pilotAddress.fields.id') }}
                        </th>
                        <td>
                            {{ $user->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pilotAddress.fields.profile_id') }}
                        </th>
                        <td>
                            {{ $user->pilot_profile_id }}
                        </td>
                    </tr>
                    
                    <tr>
                        <th>
                            {{ trans('cruds.pilotAddress.fields.address1') }}
                        </th>
                        <td>
                            {{ $user->address_line1 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pilotAddress.fields.address2') }}
                        </th>
                        <td>
                            {{ $user->address_line2 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pilotAddress.fields.city') }}
                        </th>
                        <td>
                            {{ $user->city }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pilotAddress.fields.state') }}
                        </th>
                        <td>
                            {{ $user->state }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pilotAddress.fields.country') }}
                        </th>
                        <td>
                            {{ $user->country }}
                        </td>
                    </tr>
                    
                    <tr>
                        <th>
                            {{ trans('cruds.pilotAddress.fields.zip') }}
                        </th>
                        <td>
                            {{ $user->zip }}
                        </td>
                    </tr>
                    
                    
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.pilot.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection