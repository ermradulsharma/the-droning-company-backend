@extends('layouts.admin')
@section('content')
@can('user_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.award-company.create') }}">
                {{ trans('global.add') }} Award Company
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        Award Company {{ trans('global.list') }}
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
                        <th>Award Company Title</th>
						<th>Award Company URL</th>
                        <th>
                            {{ trans('cruds.services.fields.created_at') }}
                        </th>
                        
                        
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $key => $company)
                        <tr data-entry-id="{{ $company->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $company->id ?? '' }}
                            </td>
                            <td>
                                {{ ucwords(strtolower($company->title)) ?? '' }} 
                            </td>
                            <td>
                                {{ $company->url ?? '' }}
                            </td>
                            <td>
                                {{ $company->created_at ?? '' }}
                            </td>
                            <td>
                                @can('user_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.award-company.show',$company->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('user_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.award-company.edit', $company->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('user_delete')
                                    <form action="{{ route('admin.award-company.destroy', $company->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
