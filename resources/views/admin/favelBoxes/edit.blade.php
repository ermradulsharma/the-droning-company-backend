@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.favelBoxes.title_singular') }}
    </div>
    {{-- {{ route("admin.favel-footnote-boxes.update", [$box->id]) }} --}}
    <div class="card-body">
        <form method="POST" action="{{ route("admin.favel-footnote-boxes.update", [$box->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <div class="row">
              <div class="col-md-6">
                <label class="required" for="box_name">{{ trans('cruds.favelBoxes.fields.box_name') }}</label><br>
                <input type="text" class="form-control {{ $errors->has('box_name') ? 'is-invalid' : '' }}" name="box_name" value="{{ $box->box_name }}">
                @if($errors->has('box_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('box_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.favelBoxes.fields.box_name_helper') }}</span>
              </div>
              <div class="col-md-6">
                <label for="slug">{{ trans('cruds.favelBoxes.fields.slug') }}</label><br>
                <input type="text" class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" name="slug" value="{{ $box->slug }}" disabled>
                @if($errors->has('slug'))
                    <div class="invalid-feedback">
                        {{ $errors->first('slug') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.favelBoxes.fields.slug_helper') }}</span>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-6">
                <label for="link">{{ trans('cruds.favelBoxes.fields.link') }} </label>
                <input type="text" class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}" name="link" value="{{ $box->link }}">
                @if($errors->has('link'))
                    <div class="invalid-feedback">
                        {{ $errors->first('link') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.favelBoxes.fields.link_helper') }}</span>
              </div>
              <div class="col-md-4">
                <label for="image">{{ trans('cruds.favelBoxes.fields.image') }}</label>
                <div class="custom-file">
                <input type="file" class="custom-file-input {{ $errors->has('image') ? 'is-invalid' : '' }}" id="customFile" name="image" value="{{ old('image', '') }}">
                <label class="custom-file-label" for="customFile">Box Image</label>

                @if($errors->has('image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('image') }}
                    </div>
                @endif
                </div>
              </div>
              <div class="col-md-2">
                <img src="{{ $box->image_full_path }}" height="100px" alt="image">
              </div>
            </div>         
            
            <div class="form-group mt-2">
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

      // $(document).on('change','#page',function (e) {
      //   $('#page_section').empty();
      //   $.ajax({
      //       url: "{{route('admin.ads.sections')}}",
      //       method: "post",
      //       data: {
      //         banner_page_list_id: $(this).val(),
      //       },
      //       dataType: "json",
      //       cache: false,
      //       success: function (result) {
      //         $.each(result, function( key, value ) {
      //           var type = (value.section_name == 'Side Bar' || value.section_name == 'Under Sidebar' || value.section_name == 'Above Recent Post' || value.section_name == 'Above Feature Pilot' || value.section_name == 'Middle Area Of The Sidebar' || value.section_name == 'Above Categories' || value.section_name == 'Under Sidebar Menu') ? 1 : 0 
      //             $('#page_section').append('<option data-type="'+type+'" value="'+value.id+'">'+value.section_name+'</option>');
      //         });
      //         let selected_val = $('#page_section').find('option:selected').text();
      //         let selected_type = $('#page_section').find('option:selected').data('type');
      //         // console.log(selected_val)
      //         // console.log(selected_type)
      //         disableRadioBtn(selected_val,0,selected_type,option_previous_type);
      //         option_previous_type = selected_type;
      //       }
      //   });


      // });
  
});
</script>

@endsection
