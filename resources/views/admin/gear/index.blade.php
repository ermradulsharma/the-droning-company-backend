@extends('layouts.admin')
@section('content')

   <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.gear_review.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.gear_review.title') }}
            </a>
        </div> 
    </div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.gear_review.title') }} {{ trans('global.list') }}
    </div>

    @if (\Session::has('success'))
            <div class="alert alert-success">
            {!! \Session::get('success') !!}
        </div>
    @endif
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-PilotGallery">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Video
                        </th>
                        <th>
                            Video Key
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allGear as $key => $gallery)
                        <tr data-entry-id="{{ $gallery->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $gallery->id ?? '' }}
                            </td>
                            
                            <td>
                              
                                {{ $gallery->name }}
                            </td>
                            <td>
                                <a href="{{ $gallery->video }}" target="_blank">{{ $gallery->video }}</a>
                            </td>
                            <td>
                                {{ $gallery->video_key }}
                            </td>
                            <td>
                                
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.gear_review.edit', $gallery->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                               

                               
                                    <form action="{{ route('admin.gear_review.destroy', $gallery->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                               

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
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.pilot-galleries.massDestroy') }}",
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
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)


  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-PilotGallery:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  $('div#sidebar').on('transitionend', function(e) {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  })
  
})

</script>
@endsection
