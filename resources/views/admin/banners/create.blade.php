@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.banner.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ads.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
              <div class="col-md-6">
                <label class="required" for="page">{{ trans('cruds.banner.fields.page') }}</label>
                <select class="form-control {{ $errors->has('page') ? 'is-invalid' : '' }}" name="page" id="page">
                  @foreach ($pageNames as $pageName)
                    <option value="{{ $pageName->id }}">{{ $pageName->page_name }}</option>
                  @endforeach                  
                </select>
                @if($errors->has('page'))
                    <div class="invalid-feedback">
                        {{ $errors->first('page') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.banner.fields.page_helper') }}</span>
              </div>
              <div class="col-md-6">
                <label class="required" for="banner_section_id">{{ trans('cruds.banner.fields.page_section') }} </label>
                <select class="form-control {{ $errors->has('banner_section_id') ? 'is-invalid' : '' }}" name="banner_section_id" id="page_section">
                  @foreach ($pageSectionNames as $pageSectionName)
                    <option data-val='{{ $pageSectionName->section_name }}' value="{{ $pageSectionName->id }}">{{ $pageSectionName->section_name }}</option>
                  @endforeach                  
                </select>
                @if($errors->has('banner_section_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('banner_section_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.banner.fields.page_section_helper') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-6">
                <label class="required" for="link">{{ trans('cruds.banner.fields.link') }} </label>
                <input type="text" class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}" name="link">
                @if($errors->has('link'))
                    <div class="invalid-feedback">
                        {{ $errors->first('link') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.banner.fields.link_helper') }}</span>
              </div>
              <div class="col-md-6">
                <label class="required" for="image_resolution">{{ trans('cruds.banner.fields.image_resolution') }}</label><br>
                @foreach ($resolutions as $key => $resolution)
                <div class="mr-5 float-left">
                <input id="res_{{ $resolution->id }}" class="{{ $errors->has('image_resolution') ? 'is-invalid' : '' }}" type="radio" name="image_resolution" value="{{ $resolution->resolution }}" {{ $key == 0 ? 'checked' : '' }}>
                <label for="res_{{ $resolution->id }}">{{ $resolution->resolution }}</label>
                </div>
                @endforeach
                
                @if($errors->has('image_resolution'))
                    <div class="invalid-feedback">
                        {{ $errors->first('image_resolution') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.banner.fields.image_resolution_helper') }}</span>
              </div>
            </div>
            
            <div class="form-group">
              <label class="required" for="banner_image">{{ trans('cruds.banner.fields.banner_image') }}</label>
              <div class="custom-file">
                <input type="file" class="custom-file-input {{ $errors->has('banner_image') ? 'is-invalid' : '' }}" id="customFile" name="banner_image" value="{{ old('banner_image', '') }}">
                <label class="custom-file-label" for="customFile">Upload Banner Image</label>

                @if($errors->has('banner_image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('banner_image') }}
                    </div>
                @endif
              </div>    
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
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


      function disableRadioBtn(selected_val){
        if(selected_val == 'Side Bar' || selected_val == 'Under Sidebar' || selected_val == 'Above Recent Post' || selected_val == 'Above Recent Post Expos' || selected_val == 'Above Recent Post Accessories' || selected_val == 'Above Feature Pilot' || selected_val == 'Middle Area Of The Sidebar' || selected_val == 'Above Categories'|| selected_val == 'Under Sidebar Menu'){
          $('#res_1').prop("disabled", false)
          $("#res_1").prop("checked", true)
          $('#res_2').prop("disabled", false)
          // $('#res_3').prop("disabled", true)
          // $('#res_4').prop("disabled", true)
          // $('#res_5').prop("disabled", true)
          // $('#res_6').prop("disabled", true)
          // $('#res_7').prop("disabled", true)
          // $('#res_8').prop("disabled", true)
        }
        else{
          $('#res_1').prop("disabled", true)
          $('#res_2').prop("disabled", true)
          $('#res_3').prop("disabled", false)
          $("#res_3").prop("checked", true)
          $('#res_4').prop("disabled", false)
          $('#res_5').prop("disabled", false)
          $('#res_6').prop("disabled", false)
          $('#res_7').prop("disabled", false)
          $('#res_8').prop("disabled", false)
        }
      }

      

      $(document).on('change','#page',function (e) {
        $('#page_section').empty();
        $.ajax({
            url: "{{route('admin.ads.sections')}}",
            method: "post",
            data: {
              banner_page_list_id: $(this).val(),
            },
            dataType: "json",
            cache: false,
            success: function (result) {
              $.each(result, function( key, value ) {
                  $('#page_section').append('<option value="'+value.id+'">'+value.section_name+'</option>');
              });
            }
        });
        let selected_val = $('#page_section').find('option:selected').text();
        disableRadioBtn(selected_val); 
      });



      let selected_val = $('#page_section').find('option:selected').text();
        disableRadioBtn(selected_val);


      $(document).on('change','#page_section',function (e) {
        let selected_val = $(this).find('option:selected').text();
        disableRadioBtn(selected_val);     
      });
      

      

        $("#page").keyup(function(){

        var generatedSlug=$('#page').val()
            .toString().trim().toLowerCase()
            .replace(/\s+/g, "-")
            .replace(/[^\w\-]+/g, "")
            .replace(/\-\-+/g, "-")
            .replace(/^-+/, "")
            .replace(/-+$/, "");

            $('#slug').val(generatedSlug);
        });

  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/content-pages/ckmedia', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $contentPage->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  
});
</script>

<script>
    Dropzone.options.featuredImageDropzone = {
    url: '{{ route('admin.content-pages.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="featured_image"]').remove()
      $('form').append('<input type="hidden" name="featured_image" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="featured_image"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($contentPage) && $contentPage->featured_image)
      var file = {!! json_encode($contentPage->featured_image) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="featured_image" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
@endsection
