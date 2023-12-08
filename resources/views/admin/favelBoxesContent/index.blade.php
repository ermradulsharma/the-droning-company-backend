@extends('layouts.admin')
@section('content')
@can('favelboxcontent_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.favel-footnote-boxes.content.create", $favel_box_id) }}">
                {{ trans('global.add') }} {{ trans('cruds.favelBoxesContents.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.favelBoxesContents.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-BoxPage">
                <thead>
                    <tr>
                        <th class="d-none">
                        </th>
                        <th>
                            {{ trans('cruds.favelBoxesContents.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.favelBoxesContents.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.favelBoxesContents.fields.image') }}
                        </th>
                        <th>
                            {{ trans('cruds.favelBoxesContents.fields.page_video_link') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($content as $key => $data)
                        <tr data-entry-id="{{ $data->id }}">
                            <td class="d-none">
                            </td>
                            <td>
                                {{ $data->id }}
                            </td>
                            <td>
                                {{ $data->title }}
                            </td>
                            <td>
                                @if(isset($data->image))
                                    <img src="{{ $data->imagefullpath }}" alt="" height="100px" width="100px">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                {{ $data->page_video_link }}
                            </td>                            
                            <td>
                                @can('favelboxcontent_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.favel-footnote-boxes.content.edit', [$data->favel_box_id, $data->id]) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('favelboxcontent_delete')
                                    <form action="{{ route('admin.favel-footnote-boxes.content.destroy', [$data->favel_box_id, $data->id]) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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

    let table = $('.datatable-BoxPage').DataTable({
        buttons: []
    });
  
})

</script>
@endsection
