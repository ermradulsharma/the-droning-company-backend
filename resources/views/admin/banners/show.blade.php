@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.contentPage.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.content-pages.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.contentPage.fields.id') }}
                        </th>
                        <td>
                            {{ $contentPage->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Slug
                        </th>
                        <td>
                            {{ $contentPage->slug }}
                        </td>
                    </tr>
					<tr>
                        <th>
                            Email
                        </th>
                        <td>
                            {{ $contentPage->email }}
                        </td>
                    </tr>
					<tr>
                        <th>
                            {{ trans('cruds.contentPage.fields.title') }}
                        </th>
                        <td>
                            {{ $contentPage->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contentPage.fields.category') }}
                        </th>
                        <td>
                            @foreach($contentPage->categories as $key => $category)
                                <span class="label label-info">{{ $category->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                   <tr>
                        <th>
                            {{ trans('cruds.contentPage.fields.excerpt') }}
                        </th>
                        <td>
                            {{ $contentPage->excerpt }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contentPage.fields.page_text') }}
                        </th>
                        <td>
                            
                            <?=str_replace("position: relative22;", 'position:static;', $contentPage->page_text)?>
                        </td>
                    </tr>
                    
                    <tr>
                        <th>
                            {{ trans('cruds.contentPage.fields.featured_image') }}
                        </th>
                        <td>
                       
                            @if($contentPage->image)
                                <a href="{{$contentPage->image}}" target="_blank" style="display: inline-block">
                                    <img src="{{$contentPage->image}}" width="50%">
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.content-pages.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
@section('scripts')
<script>
   //  alert('dd');
     $(function () {
        
     var x=$('.media').find("data-oembed-url div").addClass('position');

     console.log(x);
     })
    $( document ).ready(function() {
       


     //.removeClass('position');
});
</script>
@endsection
