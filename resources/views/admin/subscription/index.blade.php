@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Subscription List
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
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        <th>
                            Subscription Id
                        </th>

                        <th>
                            {{ trans('cruds.user.fields.active_status') }}
                        </th>
                        <th>
                           Plan Name
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        <tr data-entry-id="{{ $user->id }}">
                            <td>
                                
                            </td>
                            <td>
                                 {{ $loop->iteration}}
                            </td>
                            <td>
                                {{ ucwords(strtolower($user->first_name)) ?? '' }} {{ ucwords(strtolower($user->last_name)) ?? '' }}
                            </td>
                            <td>
                                {{ $user->email ?? '' }}
                            </td>
                            
                            <td>
                                {{ $user->subscriptions->stripe_id ?? '' }}
                            </td>
                            
                            <td>
                                <?php
                                if ($user->subscriptions->stripe_status == 'active') {
                                    echo '<span class="badge badge-success">'. $user->subscriptions->stripe_status ?? '' .'</span>';
                                } else {
                                    echo '<span class="badge badge-danger">'. $user->subscriptions->stripe_status ?? '' .'</span>';
                                }
                                ?>
                            </td>
                            <td>
                               {{ $user->subscriptions->name ?? '' }}
                            </td>
                            <td>
                                @can('user_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.subscriptions.show', $user->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('can_cancel_subscription')
                                    @if(isset($user->subscriptions->ends_at))
                                        <span class="badge badge-warning">Ends at: {{ $user->subscriptions->ends_at ?? '' }}</span>
                                    @else
                                        <a class="btn btn-xs btn-danger" href="{{ route('admin.subscriptions.cancel', [$user->id, $user->subscriptions->name]) }}" onclick="return confirm('Are you sure, want to Cancel Subscription?')">Cancel Subscription</a>
                                    @endif
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
