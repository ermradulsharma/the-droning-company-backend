@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="color:#000000;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:14px;line-height:24px;font-weight:700;">
               Job Detail
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <b> Customer Name</b><br> {{$pilotJob->user->name}}
                    </div>
                    <div class="col-md-4">
                        <b>Customer Email </b><br>{{$pilotJob->user->email}}
                    </div>
                    <div class="col-md-4">
                        <b>Job Created Date</b><br>  {{$pilotJob->created_at}}
                    </div> <hr>
                </div>
                <div class="row" style="padding-top: 20px ;">
                    <div class="col-md-4">
                        <b>Job Title</b><br>  {{$pilotJob->job_title}}
                    </div>
                    <div class="col-md-4">
                        <b>Job Description</b><br>  {{$pilotJob->job_description}}
                    </div>
                     <div class="col-md-4">
                        <b>Job status</b><br>  {{$pilotJob->status}}
                    </div>
                    
                </div>
                <div class="row" style="padding-top: 20px ;">
                   {{--  <div class="col-md-4">
                        <b>Job Budget</b><br>  ${{$pilotJob->job_budget}}
                    </div> --}}
                     <div class="col-md-4">
                        <b>Company Name</b><br>  {{$pilotJob->company_name}}
                    </div>
                    <div class="col-md-4">
                        <b>File Attachment</b><br>
                        <a target="_blank" href="{{$pilotJob->file_attachment}}"><i class="fa fa-download" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-md-4">
                        <b>Contact Preference </b><br>  {{$pilotJob->contactPreference()}}
                    </div>
                    {{-- <div class="col-md-4">
                        <b>Job Categories</b><br>
                        @foreach($pilotJob->job_categoires as $category)
                        <span class="badge badge-info">{{ $category->skill_name }}</span>
                    @endforeach
                    </div> --}}
                </div>

                <div class="row" style="padding-top: 20px ;">
                   
                    <div class="col-md-4">
                        <b>Job Location</b><br>
                        @foreach($pilotJob->location as $loc)
                        <span class="badge badge-info">{{ $loc->getImpoadedLocation() }}</span>
                    @endforeach
                    </div>
                   

                    

                    <div class="col-md-4">
                        <b>Job Rejection Message</b><br>  {{$pilotJob->rejection_reason}}
                    </div>

                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection
