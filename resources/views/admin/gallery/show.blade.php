@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.pilotGallery.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.pilot-galleries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.pilotGallery.fields.id') }}
                        </th>
                        <td>
                            {{ $pilotGallery->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pilotGallery.fields.pilot_profile') }}
                        </th>
                        <td>
                            {{ $pilotGallery->pilot_profile->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pilotGallery.fields.image') }}
                        </th>
                        <td>
                            {{ $pilotGallery->image }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pilotGallery.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\PilotGallery::STATUS_SELECT[$pilotGallery->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.pilot-galleries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection