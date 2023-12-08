@extends('layouts.admin')
@section('content')
@can('content_page_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.ads.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.banner.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.contentPage.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-BannerPage">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.banner.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.banner.fields.page') }}
                        </th>
                        <th>
                            {{ trans('cruds.banner.fields.page_section') }}
                        </th>
                        <th>
                            {{ trans('cruds.banner.fields.image_resolution') }}
                        </th>

                        <th>
                            {{ trans('cruds.banner.fields.banner_image') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $key => $banner)
                        <tr data-entry-id="{{ $banner->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $banner->id }}
                            </td>
                            <td>
                                {{ $banner->bannerSection->bannerPageList->page_name ?? '' }}
                            </td>
                            <td>
                                {{ $banner->bannerSection->section_name ?? '' }}
                            </td>
                            <td>
                                {{ $banner->image_resolution ?? '' }}
                            </td>

                            <td>
                                @if($banner->banner_image_full_path)
                                    <a href="{{$banner->banner_image_full_path}}" target="_blank" style="display: inline-block">
                                      
                                        <img src="{{ $banner->banner_image_full_path }}" width="100px">
                                       
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{-- @can('banner_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.banners.show', $banner->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan --}}

                                @can('banner_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.ads.edit', $banner->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('banner_delete')
                                    <form action="{{ route('admin.ads.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
<script>
    $(function () {
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('banner_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.ads.massDestroy') }}",
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
            url:  config.url,
            type: 'post',
            data: {
                ids: ids,
            },
            dataType: "json",
            cache: false,
            success: function(data){
                  location.reload()
            }
        });    
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
  let table = $('.datatable-BannerPage').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

  $( '.buttons-csv' ).remove();
  
})

</script>
@endsection
