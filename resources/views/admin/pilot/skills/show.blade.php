@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.pilotSkills.title') }}
    </div>

    <div class="card-body">
        
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.skills.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.pilotSkills.fields.id') }}
                        </th>
                        <td>
                            {{ $skill->id }}
                        </td>
                    </tr>
                    
                    
                    <tr>
                        <th>
                            {{ trans('cruds.pilotSkills.fields.skill') }}
                        </th>
                        <td>
                            {{ ucwords(strtolower($skill->skill_name)) ?? '' }} 
                        </td>
                    </tr>
                    
                    <tr>
                        <th>
                            {{ trans('cruds.pilotSkills.fields.status') }}
                        </th>
                        <td>
                            @if( $skill->status == '1' )
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">In Active</span>
                            @endif
                        </td>
                    </tr>
                    
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.skills.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection