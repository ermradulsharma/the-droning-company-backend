<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Image;
use App\Models\Blog;
use App\Models\User;
use App\Models\Skill;
use App\Models\PilotJob;
use App\Models\JobLocation;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Mail\JobPostApproved;
use App\Mail\JobPostRejected;
use App\Services\CommonService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\MassDestroyBlogRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class JobController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        return view('admin.jobs.index', [
            'jobs'=>PilotJob::latest()->get(),
        ]);
    }

   

    

    public function edit(PilotJob $pilotJob)
    {
        $skills=Skill::get()->pluck('skill_name', 'id');
        $users=User::get()->pluck('first_name', 'id');
       
        $job=PilotJob::with(['job_categoires:id,skill_name'])
                    ->where('id', $pilotJob->id)
                     ->select('id', 'job_title', 'job_description', 'file_attachment', 'job_budget', 'status', 'enquiry_type', 'created_at', 'user_id')
                     ->with(['location:city,state,country,address,pilot_job_id'])
                     ->latest('id')
                     ->first();

        return view('admin.jobs.edit', [
            'pilotJob'=>$pilotJob,
            'skills'=>$skills,
            'job'=>$job,
            'users'=>$users,
        ]);
    }

    public function update(Request $request, int $id)
    {
        if ($request->has('type') && $request->input('type')=="rejection") {
            $job=PilotJob::where('id', $id)->first();
            $job->status=PilotJob::STATUS_AS_TEXT['rejected'];
            $job->rejection_reason=$request->input('rejection_reason');
            $job->save();

            Mail::to($job ->user->email)
            ->send(new \App\Mail\JobPostRejected($job));

            return redirect()->action([JobController::class, 'index'])->with('error', 'Job Rejected Successfully');
        }
        
        $pilotJob=PilotJob::where('id', $id)->first();

        if ($request->input('status')==PilotJob::STATUS_AS_TEXT['active']) {
            if (!empty($pilotJob->location)) {
                foreach ($pilotJob->location as $key => $value) {
                    $newGeo=JobLocation::find($value->id);
           
                    [$latitude,$longitude]=(new CommonService())->findLatitudeAndLongitude($value->city);

                    $newGeo->latitude=$latitude;
                    $newGeo->longitude=$longitude;
                    $newGeo->save();
                }
            }
            Mail::to($pilotJob ->user->email)
            ->send(new JobPostApproved($pilotJob));
        }

        if ($request->input('status')=="3") {
            Mail::to($pilotJob ->user->email)
            ->send(new JobPostRejected($pilotJob));
        }
        $pilotJob->update($request->all());
        $pilotJob->job_categoires()->sync($request->input('job_categoires', []));

        return redirect()->route('admin.pilot-jobs.index');
    }

    public function show(int $id)
    {
        $pilotJob=PilotJob::where('id', $id)->first();

        return view('admin.jobs.show', compact('pilotJob'));
    }

    

    public function jobStatusUpdate(int $status_id, int $job_id)
    {
        $pilotJob =PilotJob::find($job_id);
        if ($status_id===(int)PilotJob::STATUS_AS_TEXT['active']) {
            $pilotJob ->status=PilotJob::STATUS_AS_TEXT['active'];

            $pilotJob ->save();

            Mail::to($pilotJob ->user->email)
            ->send(new \App\Mail\JobPostApproved($pilotJob));

            return redirect()->action([JobController::class, 'index'])->with('success', 'Job Approved Successfully');
        }


        return view('admin.jobs.reject', compact('pilotJob'));
    }
}
