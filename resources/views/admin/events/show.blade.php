@extends('layouts.admin')
@section('content')
<style>.img-responsive{width:100px;height:100px;object-fit:cover}</style>
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.event.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.events.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>{{ trans('cruds.event.fields.id') }}</th>
                        <td>{{ $event->id }}</td>
                    </tr>
                    <tr>
                        <th>User ID</th>
                        <td>{{ $event->user_id }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.event.fields.title') }}</th>
                        <td>{{ $event->title }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.event.fields.slug') }}</th>
                        <td>{{ $event->slug }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.event.fields.description') }}</th>
                        <td>{!! $event->description !!}</td>
                    </tr>

                    <tr>
                        <th>{{ trans('cruds.event.fields.event_type') }}</th>
                        <td>{!! $event->event_type !!}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.event.fields.event_video') }}</th>
                        <td>{!! $event->event_video !!}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.event.fields.event_start') }}</th>
                        <td>{!! $event->event_start !!}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.event.fields.event_end') }}</th>
                        <td>{!! $event->event_end !!}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.event.fields.cost') }}</th>
                        <td>{!! $event->cost !!}</td>
                    </tr>
                    <tr>
                        <th>Contact Email</th>
                        <td>{!! $event->contact_email !!}</td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td>{!! $event->phone_number !!}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.event.fields.location') }}</th>
                        <td>{!! $event->street_add.', '.$event->suite.' '.$event->city.', '.$event->state; !!}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.event.fields.meta_title') }}</th>
                        <td>{!! $event->meta_title !!}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.event.fields.meta_keyword') }}</th>
                        <td>{{ $event->meta_keyword }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.event.fields.meta_description') }}</th>
                        <td>{{ $event->meta_description }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.event.fields.image') }}</th>
                        <td><img src="{{ $event->image_thumb }}" height="auto" class="img-responsive"></td>
                    </tr>
                    <tr>
                        <th>Gallery Image 1</th>
                        <td><img src="{{ asset($event->gallery_img_1) }}" height="auto" class="img-responsive"></td>
                    </tr>
                    <tr>
                        <th>Gallery Image 2</th>
                        <td><img src="{{ asset($event->gallery_img_2) }}" height="auto" class="img-responsive"></td>
                    </tr>
                    <tr>
                        <th>Gallery Image 3</th>
                        <td><img src="{{ asset($event->gallery_img_3) }}" height="auto" class="img-responsive"></td>
                    </tr>
                    <tr>
                        <th>Gallery Image 4</th>
                        <td><img src="{{ asset($event->gallery_img_4) }}" height="auto" class="img-responsive"></td>
                    </tr>
                    <tr>
                        <th>Gallery Image 5</th>
                        <td><img src="{{ asset($event->gallery_img_5) }}" height="auto" class="img-responsive"></td>
                    </tr>
                    <tr>
                        <th>Gallery Image 6</th>
                        <td><img src="{{ asset($event->gallery_img_6) }}" height="auto" class="img-responsive"></td>
                    </tr>
                    <tr>
                        <th>Featured Event</th>
                        <td>{{ $event->is_featured == 1 ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.event.fields.status') }}</th>
                        <td>{{ App\Models\Event::STATUS_SELECT[$event->status] ?? '' }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.events.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
