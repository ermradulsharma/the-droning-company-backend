@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Setting
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Country">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            S.No
                        </th>
                        <th>
                           Title
                        </th>
                        <th>
                           Sub Title 1
                        </th>
                        <th>
                         Action
                        </th>
                       
                    </tr>
                </thead>
                <tbody>
                    @foreach($settings as $key => $setting)
                        <tr data-entry-id="{{ $setting->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $setting->id ?? '' }}
                            </td>
                            <td>
                              {{ $setting->key_1 ?? '' }}
                            </td>
                            <td>
                              {{ $setting->key_2 ?? '' }}
                            </td>
                            <td>
                             <a class="btn btn-xs btn-info" href="{{ route('admin.setting.edit', $setting->uuid) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                            </td>
                           
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
