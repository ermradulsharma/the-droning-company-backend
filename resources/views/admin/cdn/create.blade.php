@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Upload Image
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.image-cdn.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-6">
                     <div class="form-group">
               <div class="custom-file">

                    <input type="file" class="custom-file-input" id="customFile" name="image">
                    <label class="custom-file-label" for="customFile">Choose image</label>
                  </div>
                @if($errors->has('image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('image') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.blog.fields.image_helper') }}</span>
            </div>
                </div>
                <div class="col-sm-4"></div>
            </div>
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-6">
                     <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
                </div>
            </div>
           
            
           
        </form>
    </div>
</div>



@endsection
