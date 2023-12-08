@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.blogCategory.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.blog-categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.blogCategory.fields.id') }}
                        </th>
                        <td>
                            {{ $blogCategory->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blogCategory.fields.title') }}
                        </th>
                        <td>
                            {{ $blogCategory->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blogCategory.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\BlogCategory::STATUS_SELECT[$blogCategory->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blogCategory.fields.slug') }}
                        </th>
                        <td>
                            {{ $blogCategory->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blogCategory.fields.meta_keyword') }}
                        </th>
                        <td>
                            {{ $blogCategory->meta_keyword }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blogCategory.fields.meta_title') }}
                        </th>
                        <td>
                            {{ $blogCategory->meta_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blogCategory.fields.meta_description') }}
                        </th>
                        <td>
                            {{ $blogCategory->meta_description }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.blog-categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection