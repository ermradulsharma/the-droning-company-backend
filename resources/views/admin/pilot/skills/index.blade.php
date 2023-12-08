@extends('layouts.admin')
@section('content')
@can('user_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.skills.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.pilotSkills.title') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.pilotSkills.title_singular') }} {{ trans('global.list') }}
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
                            {{ trans('cruds.pilotSkills.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.pilotSkills.fields.skill') }}
                        </th>
                        <th>
                            {{ trans('cruds.pilotSkills.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.pilotSkills.fields.created_at') }}
                        </th>
                        
                        
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($skills as $key => $skill)
                        <tr data-entry-id="{{ $skill->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $skill->id ?? '' }}
                            </td>
                            <td>
                                {{ ucwords(strtolower($skill->skill_name)) ?? '' }} 
                            </td>
                            <td>
                                @if( $skill->status == '1' )
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">In Active</span>
                                @endif
                            </td>
                            <td>
                                {{ $skill->created_at ?? '' }}
                            </td>
                            
                            
                            <td>
                                @can('user_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.skills.show',$skill->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('user_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.skills.edit', $skill->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('user_delete')
                                    <form action="{{ route('admin.skills.destroy', $skill->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
<!--            <button class="btn btn-success" id='update_active_st'>Status Active</button>
            <button class="btn btn-danger" id='update_inactive_st'>Status Inactive</button>-->
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
$(function () 
{
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('user_delete1')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
            let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.users.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                    return $(entry).data('entry-id')
                });

                if (ids.length === 0) {
                    alert('{{ trans('global.datatables.zero_selected') }}')

                    return
                }

                if (confirm('{{ trans('global.areYouSure') }}')) {
                    $.ajax({
                        headers: {'x-csrf-token': _token},
                        method: 'POST',
                        url: config.url,
                        data: { ids: ids, _method: 'DELETE' }
                    })
                    .done(function () { location.reload() })
                }
            }
          }
          dtButtons.push(deleteButton)
        @endcan

    $.extend(true, $.fn.dataTable.defaults, {
        orderCellsTop: true,
        order: [[ 1, 'desc' ]],
        pageLength: 100,
    });
    
    let table = $('.datatable-User:not(.ajaxTable)').DataTable({ 
        buttons: dtButtons
    })

    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
    
    $("#update_active_st").click(function() {     
        url = "{{ route('admin.users.massActiveStatus') }}";
        var ids = $.map(table.rows({ selected: true }).nodes(), function (entry) {
                return $(entry).data('entry-id')
            });
        if (ids.length === 0) {
            alert('{{ trans('global.datatables.zero_selected') }}')

            return
        }

        if (confirm('{{ trans('global.areYouSure') }}')) {
            $.ajax({
                headers: {'x-csrf-token': _token},
                method: 'POST',
                url: url,
                data: { ids: ids, _method: 'PUT' }
            })
            .done(function () 
            { 
                location.reload() 
            })
        }
    });
    
    $("#update_inactive_st").click(function() {     
        url = "{{ route('admin.users.massInActiveStatus') }}";
        var ids = $.map(table.rows({ selected: true }).nodes(), function (entry) {
                return $(entry).data('entry-id')
            });
        if (ids.length === 0) {
            alert('{{ trans('global.datatables.zero_selected') }}')

            return
        }

        if (confirm('{{ trans('global.areYouSure') }}')) {
            $.ajax({
                headers: {'x-csrf-token': _token},
                method: 'POST',
                url: url,
                data: { ids: ids, _method: 'PUT' }
            })
            .done(function () 
            { 
                location.reload() 
            })
        }
    });
})



</script>
@endsection
