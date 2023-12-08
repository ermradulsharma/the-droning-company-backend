@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.favelBoxes.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-BoxPage">
                <thead>
                    <tr>
                        <th class="d-none">
                        </th>
                        <th>
                            {{ trans('cruds.favelBoxes.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.favelBoxes.fields.box_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.favelBoxes.fields.slug') }}
                        </th>
                        <th>
                            {{ trans('cruds.favelBoxes.fields.link') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($boxes as $key => $box)
                        <tr data-entry-id="{{ $box->id }}">
                            <td class="d-none">
                            </td>
                            <td>
                                {{ $box->id }}
                            </td>
                            <td>
                                {{ $box->box_name }}
                            </td>
                            <td>
                                {{ $box->slug }}
                            </td>
                            <td>
                                {{ $box->link }}
                            </td>                            
                            <td>
                                @can('favelbox_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.favel-footnote-boxes.edit', $box->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                {{-- @can('favelboxcontent_access') --}}
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.favel-footnote-boxes.content.index', $box->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                {{-- @endcan --}}
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
