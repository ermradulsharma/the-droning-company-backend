<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\PilotJob;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class JobPublicApiController extends Controller
{

    
    

    /**
     * Job detail public
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

    */

    public function show(Request $request, int $job_id)
    {
        $job=PilotJob::with(['job_categoires:id,skill_name'])
                    ->where('id', $job_id)
                     ->select(
                         'id',
                         'user_id',
                         'job_title',
                         'job_description',
                         'file_attachment',
                         'job_budget',
                         'status',
                         'enquiry_type',
                         'created_at',
                         'contact_via_phone_number',
                         'contact_via_email',
                         'company_name',
                         'rejection_reason',
                         'slug'
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

        $user=User::find($job->user_id);
        $job->user_email=$user->email;
        $job->user_mobile=$user->mobile;

        unset($job->user_id);

        $moreJobs=PilotJob::query()
                    ->with(['job_categoires:id,skill_name'])
                     ->select(
                         'id',
                         'job_title',
                         'slug',
                         'job_description',
                         'status',
                         'created_at'
                     )
                     ->with(['location:city,state,country,pilot_job_id'])
                     ->approvedJob()
                     ->take(6)
                     ->get();
    
        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'Job detail fetch successfully',
                         'data'=>$job,'moreJobs'=>$moreJobs
                     ])->setStatusCode(Response::HTTP_OK);
    }


    /**
     * Job List
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
                "message": "Job index fetch successfully",
                "data": [
                    {
                    "id": 1628,
                    "job_title": "update",
                    "job_description": "job desxfription comes here",
                    "status": "Active",
                    "created_at": "10-11-2021",
                    "job_categoires": [],
                    "location": [
                    {
                    "city": "new york",
                    "state": "new york",
                    "country": "New York",
                    "pilot_job_id": 1628
                    }
                    ]
                    },
                    {
                    "id": 1599,
                    "job_title": "update",
                    "job_description": "update",
                    "status": "Active",
                    "created_at": "08-23-2021",
                    "job_categoires": [
                    {
                    "id": 1,
                    "skill_name": "Drone"
                    },
                    {
                    "id": 2,
                    "skill_name": "Photography"
                    },
                    {
                    "id": 3,
                    "skill_name": "Videography"
                    },
                    {
                    "id": 4,
                    "skill_name": "Video Editing"
                    },
                    {
                    "id": 5,
                    "skill_name": "Adobe PhotoShop"
                    }
                    ],
                    "location": [
                    {
                    "city": "new york",
                    "state": "new york",
                    "country": "New York",
                    "pilot_job_id": 1599
                    }
                    ]
                }
                ]
        }
     *


     * @response status=404 {
            "statusCode": 404,
            "message" =>"Job index not found!",
                'data' =>[]
        }

    */

    public function index(Request $request)
    {
        $job=PilotJob::query()
                    ->with(['job_categoires:id,skill_name'])
                     ->select(
                         'id',
                         'job_title',
                         'slug',
                         'job_description',
                         'status',
                         'created_at'
                     )
                     ->with(['location:city,state,country,pilot_job_id'])
                     ->approvedJob();


      
        if ($request->has('q') && $request->input('q')!='' && $request->input('q')!='anywhere') {
            if ($json_exists= (new CommonService())->isJSON($request->input('q'))) {
                $decode_string=json_decode($request->input('q'));
                $city= $decode_string->city ?? '';
            } else {
                $city= $request->input('q');
            }
          
            [$latitude,$longitude]= (new CommonService())->findLatitudeAndLongitude($city);
            
           
            $pilot_job_id = \DB::table('job_locations')
                                ->whereRaw(
                                    'SQRT(POW(69.1 * (latitude - ?), 2) + POW(69.1 * (? - longitude) * COS(latitude / 57.3), 2)) < 804',
                                    [$latitude,$longitude]
                                )
                               ->select('pilot_job_id')
                               ->distinct('pilot_job_id')
                               ->get()
                               ->pluck('pilot_job_id');
           
            $job=$job->whereIn('id', $pilot_job_id);
        }

        $job_count= $job->count();
       

        if ($request->has('page')) {
            $page=$request->input('page');
            $page=$page-1;
            $offset=$page*4;
            $job=$job->offset($offset);
        }
        $job=$job->orderBy('id', 'desc')->take(4)->get();


    
        if (!$job) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'Job list not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

    
        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'Job index fetch successfully',
                         'job_count'=>$job_count,
                         'data'=>$job
                     ])->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Job List sitemap
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the validation request will fail with a `400` error, and a response based on failed attribute
     *
    */
    public function sitemap(Request $request)
    {
        $job=PilotJob::query()
                     ->select(
                         'id',
                         'job_title',
                         'slug',
                         'updated_at'
                     )
                     ->approvedJob()
                     ->get();


    
        if (!$job) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'Job sitemap not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        $sitemap=[];
        foreach ($job as $key => $value) {
            $sitemap[]=[
            'id'=>$value->id,
            'job_title'=>$value->job_title,
            'slug'=>$value->slug,
            'lastModified'=>$value->updated_at->format('Y-m-d'),
           ];
        }
    
        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'Job sitemap fetch successfully',
                         'data'=>$sitemap
                     ])->setStatusCode(Response::HTTP_OK);
    }
}
