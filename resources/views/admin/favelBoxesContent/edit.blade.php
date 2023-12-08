@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.favelBoxesContents.title_singular') }}
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route("admin.favel-footnote-boxes.content.update", [$favelbox->id,$favelboxdetail->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <div class="row">
            @if($favelbox->slug == "articles") 
                <div class="col-md-6">
                    <label class="required" for="title">{{ trans('cruds.favelBoxesContents.fields.title') }}</label><br>
                    <input type="text" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" name="title" value="{{ $favelboxdetail->title }}">
                    @if($errors->has('title'))
                        <div class="invalid-feedback">
                            {{ $errors->first('title') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.favelBoxesContents.fields.title_helper') }}</span>
                </div>
            @endif
              
              <div class="col-md-6">
                <label for="page_video_link">{{ trans('cruds.favelBoxesContents.fields.page_video_link') }}</label><br>
                <input type="text" class="form-control {{ $errors->has('page_video_link') ? 'is-invalid' : '' }}" name="page_video_link" value="{{ $favelboxdetail->page_video_link }}">
                @if($errors->has('page_video_link'))
                    <div class="invalid-feedback">
                        {{ $errors->first('page_video_link') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.favelBoxesContents.fields.page_video_link_helper') }}</span>
              </div>
            </div>
            <div class="row mt-3">
                @if($favelbox->slug == "articles") 
                    <div class="col-md-6">
                        <label for="description">{{ trans('cruds.favelBoxesContents.fields.description') }} </label>
                        <textarea  class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description">{{ $favelboxdetail->description }}</textarea>
                        @if($errors->has('description'))
                            <div class="invalid-feedback">
                                {{ $errors->first('description') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.favelBoxesContents.fields.description_helper') }}</span>
                    </div>
                    <div class="col-md-4">
                        <label for="image">{{ trans('cruds.favelBoxesContents.fields.image') }}</label>
                        <div class="custom-file">
                        <input type="file" class="custom-file-input {{ $errors->has('image') ? 'is-invalid' : '' }}" id="customFile" name="image" value="{{ old('image', '') }}">
                        <label class="custom-file-label" for="customFile">Image</label>
        
                        @if($errors->has('image'))
                            <div class="invalid-feedback">
                                {{ $errors->first('image') }}
                            </div>
                        @endif
                        </div>
                    </div>
                    <div class="col-md-2">
                        <img src="{{ $favelboxdetail->imagefullpath }}" height="100px" alt="image">
                    </div>
                @endif              
            </div>         
            
            <div class="form-group mt-3">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.update') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')

<script>
$(document).ready(function () {      
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });  
});
</script>

@endsection
