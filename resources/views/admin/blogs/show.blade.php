@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.blog.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.blogs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.blog.fields.id') }}
                        </th>
                        <td>
                            {{ $blog->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blog.fields.title') }}
                        </th>
                        <td>
                            {{ $blog->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blog.fields.slug') }}
                        </th>
                        <td>
                            {{ $blog->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blog.fields.image') }}
                        </th>
                        <td>
                            <img src="{{ $blog->image }}" height="auto" class="img-responsive">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blog.fields.description') }}
                        </th>
                        <td>
                            {!! $blog->description !!}
                        </td>
                    </tr>
                    
                    <tr>
                        <th>
                            {{ trans('cruds.blog.fields.meta_keyword') }}
                        </th>
                        <td>
                            {{ $blog->meta_keyword }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blog.fields.meta_description') }}
                        </th>
                        <td>
                            {{ $blog->meta_description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blog.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Blog::STATUS_SELECT[$blog->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blog.fields.no_of_view') }}
                        </th>
                        <td>
                            {{ $blog->no_of_view }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blog.fields.blog_category') }}
                        </th>
                        <td>
                            @foreach($blog->blog_categories as $key => $blog_category)
                                <span class="badge badge-primary">{{ $blog_category->title }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.blogs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
