@extends('layouts.admin')
@section('content')
@can('user_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.services.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.services.title') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.services.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        
    @if (\Session::has('success'))
            <div class="alert alert-success">
            {!! \Session::get('success') !!}
        </div>
    @endif
        
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.services.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.services.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.services.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.services.fields.created_at') }}
                        </th>
                        
                        
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $key => $service)
                        <tr data-entry-id="{{ $service->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $service->id ?? '' }}
                            </td>
                            <td>
                                {{ ucwords(strtolower($service->title)) ?? '' }} 
                            </td>
                            <td>
                                @if( $service->status == '1' )
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">In Active</span>
                                @endif
                            </td>
                            <td>
                                {{ $service->created_at ?? '' }}
                            </td>
                            
                            
                            <td>
                                @can('user_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.services.show',$service->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('user_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.services.edit', $service->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('user_delete')
                                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent

@endsection
