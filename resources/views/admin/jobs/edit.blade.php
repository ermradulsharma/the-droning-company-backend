@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Edit Job
    </div>
    <div class="card-body">
        <form method="POST" action="{{URL::to('/admin/pilot-jobs/update',$pilotJob->id)}}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="user_id">Customer Name</label>
                <select class="form-control select2" name="user_id" id="user_id" required>
                    <option value="" >Select Customer</option>
                    @foreach($users as $id => $user)
                    <option value="{{ $id }}"  {{$pilotJob->user_id== $id ? "selected" : "" }} >{{ $user }}</option>
                    @endforeach
                </select>
                
                <div class="form-group">
                    <label class="required" for="job_title">Job Title</label>
                    <input class="form-control {{ $errors->has('job_title') ? 'is-invalid' : '' }}" type="text" name="job_title" id="job_title" value="{{ old('job_title', $pilotJob->job_title) }}" required>
                    @if($errors->has('job_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('job_title') }}
                    </div>
                    @endif
                    
                </div>
                <div class="form-group">
                    <label class="required" for="company_name">Company Name</label>
                    <input class="form-control {{ $errors->has('company_name') ? 'is-invalid' : '' }}" type="text" name="company_name" id="company_name" value="{{ old('company_name', $pilotJob->company_name) }}" required>
                    @if($errors->has('company_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('company_name') }}
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    <label class="required" for="job_description">Job Description</label>
                    <input class="form-control {{ $errors->has('job_description') ? 'is-invalid' : '' }}" type="text" name="job_description" id="job_description"
                    value="{{ old('job_description', $pilotJob->job_description) }}" required>
                    @if($errors->has('job_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('job_description') }}
                    </div>
                    @endif
                    
                </div>
                
              
                <div class="form-group">
                    <label>{{ trans('cruds.blogCategory.fields.status') }} {{$pilotJob->status}}</label>
                    <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                        <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                        @foreach(App\Models\PilotJob::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $pilotJob->status) === (string) $label ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.blogCategory.fields.status_helper') }}</span>
                </div>
                
                
                <div class="form-group">
                    <label for="rejection_reason">Rejection Reason Message</label>
                    <input class="form-control {{ $errors->has('rejection_reason') ? 'is-invalid' : '' }}" type="text" name="rejection_reason" id="rejection_reason" value="{{ old('rejection_reason', $pilotJob->rejection_reason) }}">
                    @if($errors->has('rejection_reason'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rejection_reason') }}
                    </div>
                    @endif
                </div>
                 <input type="hidden" name="contact_via_phone_number" value="0">
                  <input type="hidden" name="contact_via_email" value="0">
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Contact Preference</label>
                    <div class="col-md-9 col-form-label">
                        <div class="form-check form-check-inline mr-1">
                           
                            <input name="contact_via_phone_number" class="form-check-input" id="inline-checkbox1" type="checkbox"  value="1" {{$pilotJob->contact_via_phone_number==1 ? 'checked':''}}>
                            <label class="form-check-label" for="inline-checkbox1">Phone Number</label>
                        </div>
                        <div class="form-check form-check-inline mr-1">
                            <input name="contact_via_email" class="form-check-input" id="inline-checkbox2" type="checkbox" value="1" {{$pilotJob->contact_via_email==1 ? 'checked':''}}>
                            <label class="form-check-label" for="inline-checkbox2">Email</label>
                        </div>
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
