@extends('layouts.admin')
@section('content')

   
<div class="card">
    <div class="card-header">
        {{ trans('cruds.pilotGallery.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-PilotGallery">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.pilotGallery.fields.id') }}</th>
                        <th>{{ trans('cruds.CompanyManagement.profile_title') }}</th>
                        <th>{{ trans('cruds.pilotGallery.fields.image') }}</th>
                        <th>{{ trans('cruds.pilotGallery.fields.status') }}</th>
                        <th>{{ trans('cruds.CompanyManagement.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($company_galleries as $key => $gallery)
                        <tr data-entry-id="{{ $gallery->id }}">
                            <td></td>
                            <td>{{ $gallery->id ?? '' }}</td>
                            <td>{{ @$gallery->company_profile->title ?? '' }}</td>
                            <td><img class="rounded-circle" src="{{ asset($gallery->image) }}" alt="" width="100"></td>
                            <td>@if( App\Models\CompanyGallery::STATUS_SELECT[$gallery->status] == 'Active' )
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">InActive</span>
                                @endif
                            </td>
                            <td><a class="btn btn-xs btn-info" href="{{ route('admin.company-galleries.edit', $gallery->id) }}">
                                    {{ trans('global.edit') }}
                                </a>
                                <form action="{{ route('admin.company-galleries.destroy', $gallery->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    url: "{{ route('admin.company-galleries.massDestroy') }}",
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
