@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
       Job Rejection
    </div>

    <div class="card-body">
        <form method="POST" action="{{URL::to('/admin/pilot-jobs/update',$pilotJob->id)}}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
             
                <input type="hidden" name="job_id" value="{{$pilotJob->id}}">
                <input type="hidden" name="type" value="rejection">
            <div class="form-group">
                <label class="required" for="rejection_reason">Job Rejection Reason</label>
                <textarea class="form-control {{ $errors->has('rejection_reason') ? 'is-invalid' : '' }}" type="text" name="rejection_reason" id="rejection_reason" 
                   required>
                    
                </textarea>
                @if($errors->has('rejection_reason'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rejection_reason') }}
                    </div>
                @endif
                
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
