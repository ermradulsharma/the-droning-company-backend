<?php

namespace App\Http\Controllers\Api\V1;

use Storage;
use App\Models\Skill;
use App\Models\PilotJob;
use App\Mail\JobPostToUser;
use App\Models\ContentPage;
use App\Models\JobLocation;
use App\Mail\JobPostToAdmin;
use App\Models\PilotAddress;
use App\Models\PilotProfile;
use Illuminate\Http\Request;
use App\Services\SkillService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;

class JobApiController extends Controller
{

    /**
     * Job create
     *
     * This endpoint allows you to add a new job requirement to the list.
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the validation request will fail with a `400` error, and a response based on failed attribute
     *
     *
     *
     *  <aside class="info">Location data format </aside>
     * [ {
            "city": "delhi",
            "state": "delhi",
            "country": "india",
            "address": "delhi"
            },{

            "city": "delhi",
            "state": "delhi",
            "country": "india",
            "address": "delhi"

            }]

     *  <aside class="info">JobCategory data format </aside>
     *  [       {
     *          "id": 1
     *          },
                {
                    "id": 2
                },
                {
                    "id": 3
                },
                {
                    "id": 4
                }
            ]
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * @return \Illuminate\Http\Response
     *
     * @response status=200  {
            "statusCode": 200,
            "message": "Your job is under review.Base on Your information here List of recommended pilots",
            "data": [
                {
                    "pilot_profile_id": 110,
                    "user_id": 126,
                    "name": "Julian Raul Barragan",
                    "title": "Julian Barragan",
                    "slug": "Julian-Barragan",
                    "short_description": "Julian has over four years of experience in video editing and production.",
                    "image": "https://thedroningcompany.1rise.com/dron/droningproject/NULL",
                    "skills": "drone,Videography,Video Editing",
                    "no_of_jobs": 10,
                    "hourly_rate": "0"
                },
                {
                    "pilot_profile_id": 111,
                    "user_id": 127,
                    "name": "Md up Saif up",
                    "title": "Software developer",
                    "slug": "md-saif",
                    "short_description": "short description",
                    "image": "https://thedroningcompany.1rise.com/dron/droningproject/NULL",
                    "skills": "drone,Videography,Video Editing",
                    "no_of_jobs": 10,
                    "hourly_rate": "0"
                }
            ]
        }
     *


     * @response status=404 {
            "statusCode": 404,
            "message" =>"Your job is under review.Base on Your information we did not find any recommended pilots",
                'data' =>[]
        }

    * @response status=400 {
            "statusCode": 400,
            "message": "validation error",
            "data": {
                "userId": [
                    "user id is required"
                ]
            }
        }

    */
    public function store(Request $request)
    {
        //dd($request->all());
        $validation = Validator::make($request->all(), [
             // User id get from login api - required
            'userId' => ['required','exists:users,id','numeric'],
           // The title of the job post - required
            'jobTitle' => 'string|required',
              // how much $ you can spend - required
            'jobBudget'=>['numeric','nullable'],
            // Describe your requirements in detail - required
            'jobDescription'=>['required','string'],
             // skill category required in order to find perfect pilot - required
            'jobCategory'    => ['json','nullable'],
              // required
            'location'    => ['required','json'],
            'jobAttachement'=>['file','nullable'],
           // required field
            'phoneNumber'=>['required'],
             // required field
            'email'=>['required'],
             // optional field
            'companyName'=>['nullable','string'],
        ], [
            'userId.required'=>'userId is required',
            'userId.exists'=>'Invalid User Id',
            'location.json'=>'please enter valid json data',
            'jobCategory.json'=>'please enter valid json data'
        ]);
        
        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $job=new PilotJob();
        $job->user_id=$request->userId;
        $job->job_title=$request->jobTitle;
        $job->slug=\Str::slug($request->jobTitle);
        $job->job_description=$request->jobDescription;
        if ($request->has('jobBudget')) {
            $job->job_budget=$request->jobBudget;
        } else {
            $job->job_budget=0;
        }
       
        $job->company_name=$request->companyName;

        if ($request->phoneNumber===true || $request->phoneNumber==="true" || $request->phoneNumber==='true') {
            $contact_via_phone_number=true;
        } else {
            $contact_via_phone_number=false;
        }

        if ($request->email===true || $request->email==="true" || $request->email==='true') {
            $email=true;
        } else {
            $email=false;
        }
        $job->contact_via_phone_number=$contact_via_phone_number;
        $job->contact_via_email=$email;
        $job->role_id=2;
        $job->status=PilotJob::STATUS_AS_TEXT['pending-approval'];
              
        if ($request->hasFile('jobAttachement')) {
            $job->file_attachment = Storage::disk('public_uploads')
                    ->put('/images/jobPost', $request->jobAttachement);
        }

        $job->save();

        if ($request->has('jobCategory')) {
            $skill_arr=[];
            foreach (json_decode($request->input('jobCategory')) as $key => $value) {
                $skill_arr[]=$value->id;
            }

            $job->job_categoires()->sync($skill_arr);
        }
        
        


        if ($request->has('location')) {
            $arr=json_decode($request->input('location'));
          
            foreach ($arr as $key => $value) {
                $location=new JobLocation();
                $location->city=@$value->city;
                $location->state=@$value->state;
                $location->country=@$value->country;
                $location->address=@$value->address;
                $location->pilot_job_id=$job->id;
                $location->save();
            }
        }


        $loc=JobLocation::where('pilot_job_id', $job->id)->get();
        $skill_id=$job->job_categoires->pluck('id');
        $city=$loc->pluck('city');
        $state=$loc->pluck('state');

        $pilot_profile_id = PilotAddress::query()
                                     ->join('pilot_profile', 'pilot_address.pilot_profile_id', '=', 'pilot_profile.id')
                                    ->whereIn('city', $city)
                                    ->orWhere(function ($query) use ($state) {
                                        $query->whereHas('state', function ($q) use ($state) {
                                            $q->whereIn('name', $state);
                                        });
                                    })
                                    ->select('pilot_profile_id')
                                    ->distinct('pilot_profile_id')
                                    ->get()
                                    ->pluck('pilot_profile_id');

        $profiles=PilotProfile::whereIn('id', $pilot_profile_id)
                            ->active()
                            ->get();



        $jobd=PilotJob::find($job->id);

       

        Mail::to(config('app.admin_email'))
            ->send(new JobPostToAdmin($jobd));

        Mail::to($jobd->user->email)
            ->send(new JobPostToUser($jobd));
            
        if ($profiles->isEmpty()) {
            return response()->json([
                'statusCode' =>200,
                'message' => 'Your job is under review.Base on Your information we did not find any recommended pilots',
                 'data'=>['job_id'=>$job->id,
                         'recommended_pilots'=>[]],
            ])->setStatusCode(200);
        }
   
        $data=[];
        foreach ($profiles as $key => $value) {
            $data[]=[
                'pilot_profile_id'=>$value->id,
                'user_id'=>$value->user_id,
                'name'=>$value->users->name,
                'title'=>$value->title,
                'slug'=>$value->slug,
                'short_description'=>$value->short_description,
                'image'=>asset($value->image),
                'skills'=>(new SkillService())->pilot($value->id),
                'no_of_jobs'=>10,
                'hourly_rate'=>$value->hourlyRate->rate ?? '0',
          ];
        }



        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'Your job is under review.Base on Your information here List of recommended pilots',
                         'data'=>['job_id'=>$job->id,
                         'recommended_pilots'=>$data],
                     ])->setStatusCode(Response::HTTP_OK);
    }


    /**
     * Job detail
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the validation request will fail with a `400` error, and a response based on failed attribute
     *
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * @return \Illuminate\Http\Response
     *
     * @response status=200  {
                "statusCode": 200,
                "message": "Job detail fetch successfully",
                "data": {
                    "id": 1572,
                    "job_title": "Mock Turtle replied in a very long silence.",
                    "job_description": "Animi similique soluta dolorem repellendus voluptate animi. Vel facere velit velit iusto. Distinctio ut dolore veritatis sit. Est aliquam sequi ut necessitatibus id unde.",
                    "file_attachment": "http://local.drone/images/jobPost/XBCumbflFzgSAKj64iFTfkKFarsGjpJ2Z66QvHrt.png",
                    "job_budget": "974",
                    "status": "Pending Approval",
                    "enquiry_type": "Contact",
                    "created_at": "2021-08-15 04:23:49",
                    "job_category": "Adobe PhotoShop",
                    'rejection_reason':"Blah blah",
                    "location": [
                        {
                            "city": "West Zariabury",
                            "state": "up",
                            "country": "India",
                            "address": "Suite 564",
                            "pilot_job_id": 1572
                        }
                    ]
                }
        }
     *


     * @response status=404 {
            "statusCode": 404,
            "message" =>"Job detail not found!",
                'data' =>[]
        }

      * @authenticated

    */

    public function show(Request $request, int $job_id)
    {
        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        if (! $personalAccessToken) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Missing bearerToken Invalid',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $user = $personalAccessToken->tokenable;

        $job=PilotJob::with(['job_categoires:id,skill_name'])
                    ->where('id', $job_id)
                     ->select(
                         'id',
                         'job_title',
                         'job_description',
                         'file_attachment',
                         'job_budget',
                         'status',
                         'enquiry_type',
                         'created_at',
                         'user_id',
                         'contact_via_phone_number',
                         'contact_via_email',
                         'company_name',
                         'rejection_reason'
                     )
                     ->with(['location:city,state,country,address,pilot_job_id'])
                     ->latest('id')
                     ->first();


    
        if (!$job) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'Job detail not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        if ($user->id!=$job->user_id) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid bearerToken',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
      
       
        unset($job->user_id);
        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'Job detail fetch successfully',
                         'data'=>$job
                     ])->setStatusCode(Response::HTTP_OK);
    }



    /**
     * Job Edit
     *
     * This endpoint allows you to add a new job requirement to the list.
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the validation request will fail with a `400` error, and a response based on failed attribute
     *
     *
     *
     *  <aside class="info">Location data format </aside>
     * [ {
            "city": "delhi",
            "state": "delhi",
            "country": "india",
            "address": "delhi"
            },{

            "city": "delhi",
            "state": "delhi",
            "country": "india",
            "address": "delhi"

            }]

     *  <aside class="info">JobCategory data format </aside>
     *  [       {
     *          "id": 1
     *          },
                {
                    "id": 2
                },
                {
                    "id": 3
                },
                {
                    "id": 4
                }
            ]
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * @return \Illuminate\Http\Response
     *
     * @response status=200  {
            "statusCode": 200,
            "message": "Your job is under review.Base on Your information here List of recommended pilots",
            "data": [
                {
                    "pilot_profile_id": 110,
                    "user_id": 126,
                    "name": "Julian Raul Barragan",
                    "title": "Julian Barragan",
                    "slug": "Julian-Barragan",
                    "short_description": "Julian has over four years of experience in video editing and production.",
                    "image": "https://thedroningcompany.1rise.com/dron/droningproject/NULL",
                    "skills": "drone,Videography,Video Editing",
                    "no_of_jobs": 10,
                    "hourly_rate": "0"
                },
                {
                    "pilot_profile_id": 111,
                    "user_id": 127,
                    "name": "Md up Saif up",
                    "title": "Software developer",
                    "slug": "md-saif",
                    "short_description": "short description",
                    "image": "https://thedroningcompany.1rise.com/dron/droningproject/NULL",
                    "skills": "drone,Videography,Video Editing",
                    "no_of_jobs": 10,
                    "hourly_rate": "0"
                }
            ]
        }
     *


     * @response status=404 {
            "statusCode": 404,
            "message" =>"Your job is under review.Base on Your information we did not find any recommended pilots",
                'data' =>[]
        }

    * @response status=400 {
            "statusCode": 400,
            "message": "validation error",
            "data": {
                "userId": [
                    "user id is required"
                ]
            }
        }

    */
    public function edit(Request $request, int $job_id)
    {
        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        if (! $personalAccessToken) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Missing bearerToken Invalid',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $user = $personalAccessToken->tokenable;

        $job=PilotJob::where('id', $job_id)->first();

        if ($user->id!=$job->user_id) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid bearerToken',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }


        $validation = Validator::make($request->all(), [
           // The title of the job post - required
            'jobTitle' => 'string|required',
              // how much $ you can spend - required
            'jobBudget'=>['numeric','nullable'],
            // Describe your requirements in detail - required
            'jobDescription'=>['required','string'],
             // skill category required in order to find perfect pilot - required
            'jobCategory'    => ['json','nullable'],
              // required
            'location'    => ['required','json'],
            // required boolean `(true,false)` value
            'isNewJobAttachement'=>['required'],
            'jobAttachement'=>['file','nullable'],
            // required field
            'phoneNumber'=>['required'],
             // required field
            'email'=>['required'],
             // optional field
            'companyName'=>['nullable','string'],
            //optional field for job rejected condition
            'rejectionReason'=>['nullable','string']
        ], [
            'location.json'=>'please enter valid json data',
            'jobCategory.json'=>'please enter valid json data',
            'isNewJobAttachement.required'=>'File upload flag required, used boolean field as `isNewJobAttachement`'
        ]);
        
        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        if (!$job) {
            return response()->json([
                'statusCode' =>404,
                'message' =>'Job Not found!',
                'data' =>[],
            ])->setStatusCode(404);
        }
     
        $job->job_title=$request->jobTitle;
        $job->job_description=$request->jobDescription;
        $job->job_budget=0;

        $job->company_name=$request->companyName;

        if ($request->phoneNumber===true || $request->phoneNumber==="true" || $request->phoneNumber==='true') {
            $contact_via_phone_number=true;
        } else {
            $contact_via_phone_number=false;
        }

        if ($request->email===true || $request->email==="true" || $request->email==='true') {
            $email=true;
        } else {
            $email=false;
        }
        $job->contact_via_phone_number=$contact_via_phone_number;
        $job->contact_via_email=$email;
        $job->rejection_reason=$request->rejectionReason;
        
        if ($request->isNewJobAttachement && $request->hasFile('jobAttachement')) {
            $job->file_attachment = Storage::disk('public_uploads')
                    ->put('/images/jobPost', $request->jobAttachement);
        }

        $job->save();

        if ($request->has('jobCategory')) {
            $skill_arr=[];
            foreach (json_decode($request->input('jobCategory')) as $key => $value) {
                $skill_arr[]=$value->id;
            }

            $job->job_categoires()->sync($skill_arr);
        }
        
        
        //delete the existing location
        
        $oldLocation= JobLocation::where('pilot_job_id', $job_id);

        if ($oldLocation) {
            $oldLocation->delete();
        }

        if ($request->has('location')) {
            $arr=json_decode($request->input('location'));
          
            foreach ($arr as $key => $value) {
                $location=new JobLocation();
                $location->city=@$value->city;
                $location->state=@$value->state;
                $location->country=@$value->country;
                $location->address=@$value->address;
                $location->pilot_job_id=$job->id;
                $location->save();
            }
        }


        $loc=JobLocation::where('pilot_job_id', $job->id)->get();
        $skill_id=$job->job_categoires->pluck('id');
        $city=$loc->pluck('city');
        $state=$loc->pluck('state');

        $pilot_profile_id = PilotAddress::query()
                                     ->join('pilot_profile', 'pilot_address.pilot_profile_id', '=', 'pilot_profile.id')
                                    ->whereIn('city', $city)
                                    ->orWhere(function ($query) use ($state) {
                                        $query->whereHas('state', function ($q) use ($state) {
                                            $q->whereIn('name', $state);
                                        });
                                    })
                                    ->select('pilot_profile_id')
                                    ->distinct('pilot_profile_id')
                                    ->get()
                                    ->pluck('pilot_profile_id');

        $profiles=PilotProfile::whereIn('id', $pilot_profile_id)
                                     // ->orWhere(function ($query) use ($skill_id) {
                                     //     $query->whereHas('userSkill', function ($q) use ($skill_id) {
                                     //         $q->whereIn('id', $skill_id);
                                     //     });
                                     // })
                            ->active()
                            ->get();


        if ($profiles->isEmpty()) {
            return response()->json([
                'statusCode' =>Response::HTTP_OK,
                'message' => 'Job updated successfully.Base on Your information we did not find any recommended pilots',
                'data'=>['job_id'=>$job->id,
                         'recommended_pilots'=>[]
                     ]
            ])->setStatusCode(Response::HTTP_OK);
        }
   
        $data=[];
        foreach ($profiles as $key => $value) {
            $data[]=[
                'pilot_profile_id'=>$value->id,
                'user_id'=>$value->user_id,
                'name'=>$value->users->name,
                'title'=>$value->title,
                'slug'=>$value->slug,
                'short_description'=>$value->short_description,
                'image'=>asset($value->image),
                'skills'=>(new SkillService())->pilot($value->id),
                'no_of_jobs'=>10,
                'hourly_rate'=>$value->hourlyRate->rate ?? '0',
          ];
        }


        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'Job updated successfully.Base on Your information here List of recommended pilots',
                         'data'=>['job_id'=>$job->id,
                         'recommended_pilots'=>$data
                     ]
                     ])->setStatusCode(Response::HTTP_OK);
    }
}
