@extends('layouts.admin')
@section('content')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.image-cdn.create') }}">
                 Image Cdn
            </a>
        </div>
    </div>

<div class="card">
    <div class="card-header">
        Image Cdn List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Country">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            S.No
                        </th>
                        <th>
                           image
                        </th>
                        <th>
                           Image Link
                        </th>
                        <th>
                           Image Link
                        </th>
                       
                    </tr>
                </thead>
                <tbody>
                    @foreach($imagescdn as $key => $country)
                        <tr data-entry-id="{{ $country->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $country->id ?? '' }}
                            </td>
                            <td>
                               <a href=""><img src=" {{ $country->image ?? '' }}" height="200px" width="200px"></a>
                            </td>
                            <td>
                              <a target="_blank" href="{{ $country->image ?? '' }}">Image Link</a>
                            </td>
                            <td>
                            <!-- Trigger -->
                            <button id="copyAction" class="btn btn-info" data-clipboard-text="{{$country->image}}">
                                Copy to image link
                            </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>

<script>
var clipboard = new ClipboardJS('#copyAction');

clipboard.on('success', function(e) {
    console.info('Action:', e.action);
    console.info('Text:', e.text);
    console.info('Trigger:', e.trigger);

    e.clearSelection();
});
</script>
@endsection
