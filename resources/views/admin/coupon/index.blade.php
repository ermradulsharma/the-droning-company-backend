@extends('layouts.admin')
@section('content')
@can('user_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.coupons.create') }}">
                {{ trans('global.add') }} Coupon
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        Coupon {{ trans('global.list') }}
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
                            Id
                        </th>
                        <th>
                            Coupon Name
                        </th>
                        <th>
                            Type
                        </th>
                        

                        <th>
                            Coupon Code
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
                    @foreach($coupons as $key => $coupon)
                        <tr data-entry-id="{{ $coupon->id }}">
                            <td>
                                
                            </td>
                            <td>
                                {{ $coupon->id ?? '' }}
                            </td>
                            <td>
                                {{ ucwords(strtolower($coupon->coupon_name)) ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Coupon::COUPON_TYPE[$coupon->coupon_type] }}
                                
                            </td>
                            
                           
                            
                            <td>
                               {{$coupon->coupon_code}}
                            </td>
                            
                            <td>
                                {{ $coupon->created_at ?? '' }}
                            </td>
                            <td>
                               {{--  @can('user_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.coupons.show', $coupon->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan --}}

                              {{--   @can('user_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.coupons.edit', $coupon->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan --}}

                                @can('user_delete')
                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
            {{-- <button class="btn btn-success" id='update_active_st'>Active</button>
            <button class="btn btn-danger" id='update_inactive_st'>Inactive</button> --}}
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
        @can('user_delete2222')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
            let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.coupons.massDestroy') }}",
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
        url = "{{ route('admin.coupons.massActiveStatus') }}";
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
        url = "{{ route('admin.coupons.massInActiveStatus') }}";
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
