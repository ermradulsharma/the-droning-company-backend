@php
function convertToLinks($str){
    $url_pattern = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';
    $email_regex = '/(\S+@\S+\.\S+)/';
    $str= preg_replace($url_pattern, '<a href="$0" target="_blank">$0</a>', $str);
    $str= preg_replace($email_regex, '<a href="mailto:$1">$1</a>', $str);
    return $str;
}
@endphp
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
            <a class="btn btn-default" href="{{ route('admin.company.index') }}">
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
                    <a class="nav-link" id="base-gallery_tab" data-toggle="tab" aria-controls="gallery_tab" href="#gallery_tab"
                       aria-expanded="false">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="base-video_tab" data-toggle="tab" aria-controls="video_tab" href="#video_tab"
                       aria-expanded="false">Video</a>
                </li>
            </ul>
            <div class="tab-content px-1 pt-1">
                <div role="tabpanel" class="tab-pane active" id="profile_tab" aria-expanded="true" aria-labelledby="base-profile_tab">
                    <div class="form-group">
                                
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>{{ trans('cruds.pilotProfile.fields.id') }}</th>
                                    <td>{{ $company->id }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.pilotProfile.fields.name') }}</th>
                                    <td>{{ $company->user->first_name }} {{ $company->user->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.pilotProfile.fields.title') }}</th>
                                    <td>{{ $company->title }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.pilotProfile.fields.slug') }}</th>
                                    <td>{{ $company->slug }}</td>
                                </tr>
                                <!-- <tr>
                                    <th>Company Services</th>
                                    <td>@foreach($company->services as $key => $service)
                                        <span class="badge badge-info">{{ $service->service->title }}</span>
                                        @endforeach
                                    </td>
                                </tr> -->

                                <tr>
                                    <th>Company Services</th>
                                    <td>
                                        @if($company->service_1)<span class="badge badge-info">{{ $company->service_1 }}</span>@endif
                                        @if($company->service_2)<span class="badge badge-info">{{ $company->service_2 }}</span>@endif
                                        @if($company->service_3)<span class="badge badge-info">{{ $company->service_3 }}</span>@endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>Address</th>
                                    <td>{{ $company->address }}</td>
                                </tr>
                                <tr>
                                    <th>Suite #</th>
                                    <td>{{ $company->suite }}</td>
                                </tr>
                                <tr>
                                    <th>City</th>
                                    <td>{{ $company->city }}</td>
                                </tr>
                                <tr>
                                    <th>State</th>
                                    <td>{{ $company->state }}</td>
                                </tr>
                                <tr>
                                    <th>Country</th>
                                    <td>{{ $company->country }}</td>
                                </tr>
                                <tr>
                                    <th>Zip Code</th>
                                    <td>{{ $company->zip_code }}</td>
                                </tr>
                                <tr>
                                    <th>Contact Person</th>
                                    <td>{{ $company->contact_person }}</td>
                                </tr>
                                <tr>
                                    <th>Website</th>
                                    <td>{{ $company->website }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $company->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $company->phone }}</td>
                                </tr>
                                <tr>
                                    <th>Facebook</th>
                                    <td>{{ $company->facebook }}</td>
                                </tr>
                                <tr>
                                    <th>Twitter</th>
                                    <td>{{ $company->twitter }}</td>
                                </tr>
                                <tr>
                                    <th>Linkedin</th>
                                    <td>{{ $company->linkedin }}</td>
                                </tr>
                                <tr>
                                    <th>Youtube</th>
                                    <td>{{ $company->youtube }}</td>
                                </tr>
                                <tr>
                                    <th>Instagram</th>
                                    <td>{{ $company->instagram }}</td>
                                </tr>
                                <tr>
                                    <th>Working working_hours</th>
                                    <td>{!! $company->working_hours !!}</td>
                                </tr>

                                <tr>
                                    <th>{{ trans('cruds.pilotProfile.fields.description') }}</th>
                                    <td><div style="white-space: break-spaces;">{!! convertToLinks($company->description) !!}</div></td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.pilotProfile.fields.short_description') }}</th>
                                    <td><div style="white-space: break-spaces;">{!! convertToLinks($company->short_description) !!}</div></td>
                                </tr>
                                
                                @php $press_release_1 = json_decode($company->press_release_1); @endphp
                                <tr>
                                    <th>Press Release 1</th>
                                    <td>
                                        <h4>{{ @$press_release_1->subject }}</h4>
                                        <span class="badge badge-info">{{ @$press_release_1->date }}</span>
                                        <div style="white-space: break-spaces;">{!! convertToLinks(@$press_release_1->content) ?? 'N/A' !!}</div>
                                    </td>
                                </tr>

                                @php $press_release_2 = json_decode($company->press_release_2); @endphp
                                <tr>
                                    <th>Press Release 2</th>
                                    <td>
                                        <h4>{{ @$press_release_2->subject }}</h4>
                                        <span class="badge badge-info">{{ @$press_release_2->date }}</span>
                                        <div style="white-space: break-spaces;">{!! convertToLinks(@$press_release_2->content) ?? 'N/A' !!}</div>
                                    </td>
                                </tr>

                                @php $press_release_3 = json_decode($company->press_release_3); @endphp
                                <tr>
                                    <th>Press Release 3</th>
                                    <td>
                                        <h4>{{ @$press_release_3->subject }}</h4>
                                        <span class="badge badge-info">{{ @$press_release_3->date }}</span>
                                        <div style="white-space: break-spaces;">{!! convertToLinks(@$press_release_3->content) ?? 'N/A' !!}</div>
                                    </td>
                                </tr>

                                @php $dc_articles = json_decode($company->dc_articles); @endphp
                                <tr>
                                    <th>Droning Company Article 1</th>
                                    <td>
                                        <div style="white-space: break-spaces;">{!! convertToLinks(@$dc_articles[0]->article) ?? 'N/A' !!}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Droning Company Article 2</th>
                                    <td>
                                        <div style="white-space: break-spaces;">{!! convertToLinks(@$dc_articles[1]->article) ?? 'N/A' !!}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Droning Company Article 3</th>
                                    <td>
                                        <div style="white-space: break-spaces;">{!! convertToLinks(@$dc_articles[2]->article) ?? 'N/A' !!}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Droning Company Article 4</th>
                                    <td>
                                        <div style="white-space: break-spaces;">{!! convertToLinks(@$dc_articles[3]->article) ?? 'N/A' !!}</div>
                                    </td>
                                </tr>


                                <tr>
                                    <th>{{ trans('cruds.pilotProfile.fields.metatitle') }}</th>
                                    <td>{{ $company->metatitle }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.pilotProfile.fields.metakeyword') }}</th>
                                    <td>{{ $company->metakeyword }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.pilotProfile.fields.metadescription') }}</th>
                                    <td>{!! $company->metadescription !!}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.pilotProfile.fields.status') }}</th>
                                    <td>@if( $company->status == '1' )
                                            <span class="badge badge-success">Activate</span>
                                        @else
                                            <span class="badge badge-danger">Deactivate</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>{{ trans('cruds.pilotProfile.fields.is_featured') }}</th>
                                    <td>@if( $company->is_featured == 'Yes' )
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-danger">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Home Featured</th>
                                    <td>@if( $company->home_featured)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-danger">No</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($company->logo)
                                <tr>
                                    <th>Company Logo</th>
                                    <td><img title="{{ ucwords(strtolower($company->title)) ?? '' }}" src="{{ asset($company->logo) ?? '' }}" alt="{{ ucwords(strtolower($company->title)) ?? '' }}" width="100"></td>
                                </tr>
                                @endif
                                @if($company->featured_image)
                                <tr>
                                    <th>Featured Image</th>
                                    <td><img title="{{ ucwords(strtolower($company->title)) ?? '' }}" src="{{ asset($company->featured_image) ?? '' }}" alt="{{ ucwords(strtolower($company->title)) ?? '' }}" width="100"></td>
                                </tr>
                                @endif
                                @if($company->profile_img_1)
                                <tr>
                                    <th>Profile Image 1</th>
                                    <td><img title="{{ ucwords(strtolower($company->title)) ?? '' }}" src="{{ asset($company->profile_img_1) ?? '' }}" alt="{{ ucwords(strtolower($company->title)) ?? '' }}" width="100"></td>
                                </tr>
                                <tr><th>Profile Image 1 Desc</th><td>{{ $company->pic_desc_1 ?? "N/A" }}</td></tr>
                                @endif
                                @if($company->profile_img_2)
                                <tr>
                                    <th>Profile Image 2</th>
                                    <td><img title="{{ ucwords(strtolower($company->title)) ?? '' }}" src="{{ asset($company->profile_img_2) ?? '' }}" alt="{{ ucwords(strtolower($company->title)) ?? '' }}" width="100"></td>
                                </tr>
                                <tr><th>Profile Image 2 Desc</th><td>{{ $company->pic_desc_2 ?? "N/A" }}</td></tr>
                                @endif
                                @if($company->profile_img_3)
                                <tr>
                                    <th>Profile Image 3</th>
                                    <td><img title="{{ ucwords(strtolower($company->title)) ?? '' }}" src="{{ asset($company->profile_img_3) ?? '' }}" alt="{{ ucwords(strtolower($company->title)) ?? '' }}" width="100"></td>
                                </tr>
                                <tr><th>Profile Image 3 Desc</th><td>{{ $company->pic_desc_3 ?? "N/A" }}</td></tr>
                                @endif
                                @if($company->profile_img_4)
                                <tr>
                                    <th>Profile Image 4</th>
                                    <td><img title="{{ ucwords(strtolower($company->title)) ?? '' }}" src="{{ asset($company->profile_img_4) ?? '' }}" alt="{{ ucwords(strtolower($company->title)) ?? '' }}" width="100"></td>
                                </tr>
                                <tr><th>Profile Image 4 Desc</th><td>{{ $company->pic_desc_4 ?? "N/A" }}</td></tr>
                                @endif
                                @if($company->profile_img_5)
                                <tr>
                                    <th>Profile Image 5</th>
                                    <td><img title="{{ ucwords(strtolower($company->title)) ?? '' }}" src="{{ asset($company->profile_img_5) ?? '' }}" alt="{{ ucwords(strtolower($company->title)) ?? '' }}" width="100"></td>
                                </tr>
                                <tr><th>Profile Image 5 Desc</th><td>{{ $company->pic_desc_5 ?? "N/A" }}</td></tr>
                                @endif
                                @if($company->profile_img_6)
                                <tr>
                                    <th>Profile Image 6</th>
                                    <td><img title="{{ ucwords(strtolower($company->title)) ?? '' }}" src="{{ asset($company->profile_img_6) ?? '' }}" alt="{{ ucwords(strtolower($company->title)) ?? '' }}" width="100"></td>
                                </tr>
                                <tr><th>Profile Image 6 Desc</th><td>{{ $company->pic_desc_6 ?? "N/A" }}</td></tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.company.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane" id="gallery_tab" aria-labelledby="base-gallery_tab">
                    <div class="card-body">   
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover datatable datatable-PilotGallery">
                                <thead>
                                    <tr>
                                        <th width="10"></th>
                                        <th>{{ trans('cruds.pilotGallery.fields.id') }}</th>
                                        <th>{{ trans('cruds.pilotGallery.fields.image') }}</th>
                                        <th>{{ trans('cruds.pilotGallery.fields.status') }}</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($company->gallery as $key => $gallery)
                                    <tr data-entry-id="{{ $gallery->id }}">
                                        <td></td>
                                        <td>{{ $gallery->id ?? '' }}</td>
                                        <td><img class="rounded-circle" src="{{ asset($gallery->image) }}" alt="" width="100"></td>
                                        <td>@if( App\Models\CompanyGallery::STATUS_SELECT[$gallery->status] == 'Active' )
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">In Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-xs btn-info" href="{{ route('admin.company-galleries.edit', $gallery->id) }}">{{ trans('global.edit') }}</a>
                                            <form action="{{ route('admin.company-galleries.destroy', $gallery->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-                                            block;">
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
                                        <th width="10"></th>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Key</th>
                                        <th>Video</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($videos as $key => $video)
                                    <tr data-entry-id="{{ $video->id }}">
                                        <td></td>
                                        <td>{{ $video->id ?? '' }}</td>
                                        <td>{{ $video->type ?? '' }}</td>
                                        <td>{{ $video->video_key ?? '' }}</td>
                                        <td><a href="{{ $video->video }}" target="_blank">{{ $video->video }}</a></td>
                                        <td>
                                            <a class="btn btn-xs btn-info" href="{{ route('admin.company-videos.edit', $video) }}">{{ trans('global.edit') }}</a>
                                            <form action="{{ route('admin.company-videos.destroy', $video->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-                                            block;">
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

