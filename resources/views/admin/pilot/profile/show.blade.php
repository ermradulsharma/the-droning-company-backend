@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.pilotProfile.title') }}
    </div>
    
    <div class="card-body">
        @if (\Session::has('success'))
            <div class="alert alert-success">
                {!! \Session::get('success') !!}
            </div>
        @endif
        <div class="form-group">
            <a class="btn btn-default" href="{{ route('admin.pilot.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
        <div class="col-xl-12 col-lg-12">
            
            <ul class="nav nav-tabs nav-linetriangle no-hover-bg">
                <li class="nav-item">
                    <a class="nav-link active" id="base-profile_tab" data-toggle="tab" aria-controls="profile_tab" href="#profile_tab"
                       aria-expanded="true">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="base-address_tab" data-toggle="tab" aria-controls="address_tab" href="#address_tab"
                       aria-expanded="true">Address</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="base-gallery_tab" data-toggle="tab" aria-controls="gallery_tab" href="#gallery_tab"
                       aria-expanded="false">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="base-video_tab" data-toggle="tab" aria-controls="video_tab" href="#video_tab"
                       aria-expanded="false">Video</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="base-equipments_tab" data-toggle="tab" aria-controls="equipments_tab" href="#equipments_tab"
                       aria-expanded="false">Equipment</a>
                </li>
            </ul>
            <div class="tab-content px-1 pt-1">
                <div role="tabpanel" class="tab-pane active" id="profile_tab" aria-expanded="true" aria-labelledby="base-profile_tab">
                    <div class="form-group">
                                
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pilotProfile.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $user->id }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        {{ trans('cruds.pilotProfile.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $user->users->first_name }} {{ $user->users->last_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pilotProfile.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $user->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pilotProfile.fields.slug') }}
                                    </th>
                                    <td>
                                        {{ $user->users->slug }} 
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Pilot Skills
                                    </th>
                                    <td>                                        
                                        @foreach($user->userSkill as $key => $ukills)
                                            @foreach($skills as $skey => $skill)
                                                @if($ukills->skill_id ==$skey)
                                                {{ $loop->first ? '' : ',' }}
                                                <span class="badge badge-info">{{$skill}}</span>
                                                @endif
                                            @endforeach
                                        @endforeach                                        
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Rate
                                    </th>
                                    <td>
                                        {{ $pilot_rate }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pilotProfile.fields.description') }}
                                    </th>
                                    <td>
                                        <?php echo htmlspecialchars_decode($user->description); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pilotProfile.fields.short_description') }}
                                    </th>
                                    <td>
                                        <?php echo htmlspecialchars_decode($user->short_description); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pilotProfile.fields.metatitle') }}
                                    </th>
                                    <td>
                                        {{ $user->metatitle }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pilotProfile.fields.metakeyword') }}
                                    </th>
                                    <td>
                                        {{ $user->metakeyword }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pilotProfile.fields.metadescription') }}
                                    </th>
                                    <td>
                                        <?php echo htmlspecialchars_decode($user->metadescription); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pilotProfile.fields.is_certified') }}
                                    </th>
                                    <td>
                                        
                                        @if( $user->is_certified == 'Yes' )
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-danger">No</span>
                                        @endif
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pilotProfile.fields.travel_option') }}
                                    </th>
                                    <td>
                                        @if( $user->travel_option == 'Yes' )
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-danger">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pilotProfile.fields.status') }}
                                    </th>
                                    <td>
                                        @if( $user->status == '1' )
                                            <span class="badge badge-success">Activate</span>
                                        @else
                                            <span class="badge badge-danger">Deactivate</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        {{ trans('cruds.pilotProfile.fields.is_featured') }}
                                    </th>
                                    <td>
                                        @if( $user->is_featured == 'Yes' )
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-danger">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Home Featured
                                    </th>
                                    <td>
                                        @if( $user->home_featured)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-danger">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Is Insured
                                    </th>
                                    <td>
                                        @if( $user->is_insured)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-danger">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Profile photo
                                    </th>
                                    <td>
                                        <img title="{{ ucwords(strtolower($user->users->first_name)) ?? '' }}" class="rounded-circle" src="{{ asset($user->image) ?? '' }}" alt="{{ ucwords(strtolower($user->users->first_name)) ?? '' }}" width="100">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        License Image
                                    </th>
                                    <td>
                                        <img  class="rounded-circle" src="{{$user->license_image}}"  width="100">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.pilot.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="address_tab" aria-labelledby="base-address_tab">

                    <div class="card-body">   
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                                <thead>
                                    <tr>
                                        <th width="10">

                                        </th>
                                        <th>
                                            ID
                                        </th>
                                        <th>
                                            Country
                                        </th>

                                        <th>
                                            State
                                        </th>
                                        <th>
                                            City
                                        </th>
                                        {{-- <th>
                                            Address Line1
                                        </th>
                                        <th>
                                            Address Line2
                                        </th> --}}
                                        <th>
                                            Zip
                                        </th>

                                        <th>
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($address as $key => $user)

                                    <tr data-entry-id="{{ $user->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $user->id ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($country as $id => $cnt)
                                                @if($id == $user->country)
                                                    {{ $cnt }}
                                                @endif
                                            @endforeach
                                        </td>


                                        <td>
                                            {{ $user->name ?? ''}}                                            
                                            
                                        </td>
                                        <td>
                                            {{ $user->city ?? '' }}
                                        </td>
                                        {{-- <td>
                                            {{ $user->address_line1 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $user->address_line2 ?? '' }}
                                        </td> --}}
                                        <td>
                                            {{ $user->zip ?? '' }}
                                        </td>
                                        <td>
                                            @can('user_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.pilot_address.edit', $user->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan
                                            @can('user_delete')
                                            <form action="{{ route('admin.pilot_address.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                <div class="tab-pane" id="gallery_tab" aria-labelledby="base-gallery_tab">

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
                                            {{ trans('cruds.pilotGallery.fields.status') }}
                                        </th>
                                        <th>
                                            &nbsp;
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($galleries as $key => $pilotGallery)
                                    <tr data-entry-id="{{ $pilotGallery->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $pilotGallery->id ?? '' }}
                                        </td>                                        
                                        <td>
                                            <img class="rounded-circle" src="{{ asset($pilotGallery->image) }}" alt="" width="100">
                                        </td>

                                        <td>
                                            @if( App\Models\PilotGallery::STATUS_SELECT[$pilotGallery->status] == 'Active' )
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">In Active</span>
                                            @endif
                                        </td>
                                        <td>

                                        
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.pilot-galleries.edit', $pilotGallery->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                       
                                    <form action="{{ route('admin.pilot-galleries.destroy', $pilotGallery->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-                                            block;">
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
                <div class="tab-pane" id="video_tab" aria-labelledby="base-video_tab">

                    <div class="card-body">   
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover datatable datatable-PilotVideos">
                                <thead>
                                    <tr>
                                        <th width="10">
                                        </th>
                                        <th>
                                            ID
                                        </th>
                                        
                                        <th>
                                            Type
                                        </th>
                                        <th>
                                            Key
                                        </th>
                                        <th>
                                            Video
                                        </th>
                                        <th>
                                            &nbsp;
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($videos as $key => $pilotVideos)
                                    <tr data-entry-id="{{ $pilotVideos->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $pilotVideos->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pilotVideos->type ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pilotVideos->video_key ?? '' }}
                                        </td>
                                        
                                        <td>
                                            <a href="{{ $pilotVideos->video }}" target="_blank">{{ $pilotVideos->video }}</a>
                                        </td>
                                        
                                        <td>

                                        
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.pilot_videos.edit',['pilot_video'=>$pilotVideos->id]) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                       
                                    <form action="{{ route('admin.pilot_videos.destroy', $pilotVideos->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-                                            block;">
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
                <div class="tab-pane" id="equipments_tab" aria-labelledby="base-equipments_tab">

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
                                            Title
                                        </th>
                                        
                                        <th>
                                            Image
                                        </th>
                                        <th>
                                            Manufacturer
                                        </th>
                                        <th>
                                            &nbsp;
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($equipments as $key => $pilotEqp)
                                    <tr data-entry-id="{{ $pilotEqp->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $pilotEqp->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pilotEqp->title ?? '' }}
                                        </td>
                                        
                                        <td>

                                            <img class="rounded-circle" src="{{ asset($pilotEqp->image) }}" alt="" width="100">
                                        </td>
                                        <td>
                                            {{ $pilotEqp->manufacturer ?? '' }}
                                        </td>
                                        <td>

                                        
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.pilot_equipments.edit', ['pilot_equipment'=>$pilotEqp->id ]) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                       
                                    <form action="{{ route('admin.pilot_equipments.destroy', $pilotEqp->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-                                            block;">
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
            </div>
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
        @can('user_delete')
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
    
    
})



</script>
@endsection

