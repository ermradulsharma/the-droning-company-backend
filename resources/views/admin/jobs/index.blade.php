@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Jobs List
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
                            <button style="border: none; background: transparent; font-size: 14px;" id="MyTableCheckAllButton">
                                <i class="far fa-square"></i>  
                            </button>
                        </th>
                        <th>
                            S.No
                        </th>
                        <th>
                            Customer Name
                        </th>
                        <th>
                            Job Title
                        </th>
                        <th>
                            Job Budget
                        </th>

                        <th>
                           Job Status
                        </th>
                        <th>
                           Created At
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobs as $key => $job)
                        <tr data-entry-id="{{ $job->id }}">
                            <td>
                                
                            </td>
                            <td>
                                 {{ $loop->iteration}}
                            </td>
                            <td>
                                {{ $job->user->name}}
                            </td>
                            <td>
                                {{$job->job_title }}
                            </td>
                            <td>
                                $ {{ $job->job_budget ?? '' }}
                            </td>
                            <td>
                                
                                {{$job->status}}
                            
                            </td>
                            <td>
                                
                                {{$job->created_at}}
                            
                            </td>
                            
                            <td>
                                @can('user_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.pilot-jobs.show', $job->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                 <a class="btn btn-xs btn-info" href="{{ route('admin.pilot-jobs.edit', $job->id) }}">
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
@section('scripts')
@parent
<script>
$(function () 
{
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('user_delete22')
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
        order: [[ 1, 'asc' ]],
        pageLength: 100,
    });
    
    let table = $('.datatable-User:not(.ajaxTable)').DataTable({ 
        buttons: dtButtons
    })
    
    $('#MyTableCheckAllButton').click(function() {
        if (table.rows({
                selected: true
            }).count() > 0) {
            table.rows().deselect();
            return;
        }

        table.rows().select();
    });
    
    table.on('select deselect', function(e, dt, type, indexes) {
        if (type === 'row') {
            // We may use dt instead of myTable to have the freshest data.
            if (dt.rows().count() === dt.rows({
                    selected: true
                }).count()) {
                // Deselect all items button.
                $('#MyTableCheckAllButton i').attr('class', 'far fa-check-square');
                return;
            }

            if (dt.rows({
                    selected: true
                }).count() === 0) {
                // Select all items button.
                $('#MyTableCheckAllButton i').attr('class', 'far fa-square');
                return;
            }

            // Deselect some items button.
            $('#MyTableCheckAllButton i').attr('class', 'far fa-minus-square');
        }
    });
    

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
