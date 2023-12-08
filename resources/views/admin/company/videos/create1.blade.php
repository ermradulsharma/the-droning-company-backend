@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Build Pilot Video
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.company-videos.store") }}" enctype="multipart/form-data" onsubmit="return checkValidation();">
            @csrf
           
            <input class="form-control" type="hidden" name="user_id" id="user_id" value="{{ $userId }}">
            <input class="form-control" type="hidden" name="profile_id" id="profile_id" value="{{ $profileId }}">
            <input class="form-control" type="hidden" name="count" id="total_count" value="1">

             @livewire('company-video-add-more')
            
            <div class="form-group">
                <button class="btn btn-danger" id="btnVideoSave" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
@parent
@endsection
