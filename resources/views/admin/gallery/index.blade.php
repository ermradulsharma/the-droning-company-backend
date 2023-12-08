@extends('layouts.admin')
@section('content')

   <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.photo_gallery.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.photo_gallery.title') }}
            </a>
        </div>
    </div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.photo_gallery.title') }} {{ trans('global.list') }}
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
                            {{ trans('cruds.pilotGallery.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.pilotGallery.fields.image') }}
                        </th>
                        <th>
                            Image Text
                        </th>
                        <th>
                           Image Link
                        </th>
                        <th>
                            {{ trans('cruds.pilotGallery.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($photo_galleries as $key => $gallery)
                        <tr data-entry-id="{{ $gallery->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $gallery->id ?? '' }}
                            </td>
                            
                            <td>
                              
                                <img class="rounded-circle" src="{{ asset($gallery->image) }}" alt="" width="100">
                            </td>
                            <td>
                                {{ $gallery->image_text ?? '' }}
                            </td>
                            <td>
                               <a href=" {{ $gallery->image_link ?? '' }}" target="_blank">Image Link</a>
                            </td>
                            
                            <td>
                                @if( App\Models\PhotoGallery::STATUS_SELECT[$gallery->status] == 'Active' )
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">In Active</span>
                                @endif
                                
                            </td>
                            <td>
                                
                                <a class="btn btn-xs btn-info" href="{{ route('admin.photo_gallery.edit', $gallery->id) }}">
                                    {{ trans('global.edit') }}
                                </a>

                                <form action="{{ route('admin.photo_gallery.destroy', $gallery->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
