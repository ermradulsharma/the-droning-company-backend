<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Skill;
use App\Models\PilotJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class JobPublishApiController extends Controller
{
    /**
     * Job Publishing States
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
                    "message": "Job Publishing States fetch successfully",
                    "data": [
                        {
                            "id": 1575,
                            "job_title": "I hadn't begun my tea--not above a week or.",
                            "job_description": "Quis praesentium minus assumenda rerum. Consequatur molestiae fugit inventore minima incidunt non est. Quae animi eius maiores est.",
                            "file_attachment": "http://local.drone/images/jobPost/XBCumbflFzgSAKj64iFTfkKFarsGjpJ2Z66QvHrt.png",
                            "job_budget": "861",
                            "status": "Archived",
                            "enquiry_type": "Contact",
                            "created_at": "2021-08-15 04:23:49",
                            "job_category": "drone",
                            "location": [
                                {
                                    "city": "Bartellfort",
                                    "state": "up",
                                    "country": "India",
                                    "address": "Apt. 138",
                                    "pilot_job_id": 1575
                                },
                                {
                                    "city": "Mullerborough",
                                    "state": "up",
                                    "country": "India",
                                    "address": "Apt. 559",
                                    "pilot_job_id": 1575
                                }
                            ]
                        },
                        {
                            "id": 1574,
                            "job_title": "OURS they had to leave off being arches to do.",
                            "job_description": "Ullam optio ducimus assumenda quia ducimus perspiciatis pariatur nisi. Eligendi sequi aut ut voluptatem eos unde quia.",
                            "file_attachment": "http://local.drone/images/jobPost/XBCumbflFzgSAKj64iFTfkKFarsGjpJ2Z66QvHrt.png",
                            "job_budget": "455",
                            "status": "Active",
                            "enquiry_type": "Bid",
                            "created_at": "2021-08-15 04:23:49",
                            "job_category": "Photography",
                            "location": [
                                {
                                    "city": "Collierfort",
                                    "state": "delhi",
                                    "country": "India",
                                    "address": "Apt. 839",
                                    "pilot_job_id": 1574
                                }
                            ]
                        }
                    ]
        }
     *


     * @response status=404 {
            "statusCode": 404,
            "message" =>"Job Publishing States not found!",
                'data' =>[]
        }

     * @authenticated
    */
    public function JobPublishingStates(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user_id' => ['required','exists:users,id'],
        ], [
            'user_id.required'=>'userId is required',
            'user_id.exists'=>'Invalid User Id',
        ]);
       
        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' =>$validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        if (! $personalAccessToken) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid token',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $user = $personalAccessToken->tokenable;

        if ($user->id!=$request->user_id) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'mismatch token',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $jobs=PilotJob::where('user_id', $request->user_id)
                     ->select('id', 'job_title', 'job_description', 'file_attachment', 'job_budget', 'status', 'enquiry_type', 'created_at')
                     ->addSelect(['job_category' => Skill::select('skill_name')
                     ->whereColumn('skill_category_id', 'skills.id')
                     ->limit(1)])
                     ->with(['location:city,state,country,address,pilot_job_id'])
                     ->latest('id')
                     ->take(10)
                     ->get();
       
        
        if ($jobs->isEmpty()) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'Job Publishing States not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'Job Publishing States fetch successfully',
                         'data'=>$jobs
                     ])->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Job Recently
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
                    "message": "Recently Job fetch successfully",
                    "data": [
                        {
                            "id": 1575,
                            "job_title": "I hadn't begun my tea--not above a week or.",
                            "job_description": "Quis praesentium minus assumenda rerum. Consequatur molestiae fugit inventore minima incidunt non est. Quae animi eius maiores est.",
                            "file_attachment": "http://local.drone/images/jobPost/XBCumbflFzgSAKj64iFTfkKFarsGjpJ2Z66QvHrt.png",
                            "job_budget": "861",
                            "status": "Archived",
                            "enquiry_type": "Contact",
                            "created_at": "2021-08-15 04:23:49",
                            "job_category": "drone",
                            "location": [
                                {
                                    "city": "Bartellfort",
                                    "state": "up",
                                    "country": "India",
                                    "address": "Apt. 138",
                                    "pilot_job_id": 1575
                                },
                                {
                                    "city": "Mullerborough",
                                    "state": "up",
                                    "country": "India",
                                    "address": "Apt. 559",
                                    "pilot_job_id": 1575
                                }
                            ]
                        },
                        {
                            "id": 1574,
                            "job_title": "OURS they had to leave off being arches to do.",
                            "job_description": "Ullam optio ducimus assumenda quia ducimus perspiciatis pariatur nisi. Eligendi sequi aut ut voluptatem eos unde quia.",
                            "file_attachment": "http://local.drone/images/jobPost/XBCumbflFzgSAKj64iFTfkKFarsGjpJ2Z66QvHrt.png",
                            "job_budget": "455",
                            "status": "Active",
                            "enquiry_type": "Bid",
                            "created_at": "2021-08-15 04:23:49",
                            "job_category": "Photography",
                            "location": [
                                {
                                    "city": "Collierfort",
                                    "state": "delhi",
                                    "country": "India",
                                    "address": "Apt. 839",
                                    "pilot_job_id": 1574
                                }
                            ]
                        }
                    ]
        }
     *


     * @response status=404 {
            "statusCode": 404,
            "message" =>"Recently Job not found!",
                'data' =>[]
        }

     * @authenticated
    */
    public function recently(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user_id' => ['required','exists:users,id'],
        ], [
            'user_id.required'=>'user Id is required',
            'user_id.exists'=>'Invalid User Id',
        ]);
       
        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' =>$validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        if (! $personalAccessToken) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Missing bearerToken Invalid',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $user = $personalAccessToken->tokenable;

        if ($user->id!=$request->user_id) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid bearerToken',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $jobs=PilotJob::query()
                    ->where('user_id', $request->user_id)
                     ->whereIn('status', [1,2,3])
                     ->select('id', 'job_title', 'job_description', 'file_attachment', 'job_budget', 'status', 'enquiry_type', 'created_at')
                     ->addSelect(['job_category' => Skill::select('skill_name')
                     ->whereColumn('skill_category_id', 'skills.id')
                     ->limit(1)])
                     ->with(['location:city,state,country,address,pilot_job_id'])
                     ->latest('id')
                     ->last30days();
       
        if ($request->has('jobfilter') && $request->input('jobfilter')!='all') {
            $status=PilotJob::STATUS_AS_TEXT[$request->input('jobfilter')];
            $jobs=   $jobs->where('status', $status);
        }

        $no_of_jobs=$jobs->count();
       
        $jobs=$jobs->take(5)->get();
       

        $user=User::where('id', $request->user_id)->first();

        $data=[];
        $data['member_since']=$user->created_at->format('Y-m-d');
        $data['profile_photo']=$user->profile_photo;
        $data['hire']=0;
        $data['job_posted']=$no_of_jobs;
        $data['recentJobs']=$jobs;


        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'Recently Job fetch successfully',
                         'data'=>$data
                     ])->setStatusCode(Response::HTTP_OK);
    }

    /**
    * MyJobs
    *
    * If everything is okay, you'll get a `200` OK response with data.
    *
    * Otherwise, the validation request will fail with a `400` error, and a response based on failed attribute
    *
    * @bodyParam user_id required The id of the user. Example: 1
    * @param \Illuminate\Http\Request  $request
    * @param $id required
    * @return \Illuminate\Http\Response
    *
    * @response status=200  {
                   "statusCode": 200,
                   "message": "Job Publishing States fetch successfully",
                   "data": [
                       {
                           "id": 1575,
                           "job_title": "I hadn't begun my tea--not above a week or.",
                           "job_description": "Quis praesentium minus assumenda rerum. Consequatur molestiae fugit inventore minima incidunt non est. Quae animi eius maiores est.",
                           "file_attachment": "http://local.drone/images/jobPost/XBCumbflFzgSAKj64iFTfkKFarsGjpJ2Z66QvHrt.png",
                           "job_budget": "861",
                           "status": "Archived",
                           "enquiry_type": "Contact",
                           "created_at": "2021-08-15 04:23:49",
                           "job_category": "drone",
                           "location": [
                               {
                                   "city": "Bartellfort",
                                   "state": "up",
                                   "country": "India",
                                   "address": "Apt. 138",
                                   "pilot_job_id": 1575
                               },
                               {
                                   "city": "Mullerborough",
                                   "state": "up",
                                   "country": "India",
                                   "address": "Apt. 559",
                                   "pilot_job_id": 1575
                               }
                           ]
                       },
                       {
                           "id": 1574,
                           "job_title": "OURS they had to leave off being arches to do.",
                           "job_description": "Ullam optio ducimus assumenda quia ducimus perspiciatis pariatur nisi. Eligendi sequi aut ut voluptatem eos unde quia.",
                           "file_attachment": "http://local.drone/images/jobPost/XBCumbflFzgSAKj64iFTfkKFarsGjpJ2Z66QvHrt.png",
                           "job_budget": "455",
                           "status": "Active",
                           "enquiry_type": "Bid",
                           "created_at": "2021-08-15 04:23:49",
                           "job_category": "Photography",
                           "location": [
                               {
                                   "city": "Collierfort",
                                   "state": "delhi",
                                   "country": "India",
                                   "address": "Apt. 839",
                                   "pilot_job_id": 1574
                               }
                           ]
                       }
                   ]
       }
    *


    * @response status=404 {
           "statusCode": 404,
           "message" =>"Job Publishing States not found!",
               'data' =>[]
       }

    * @authenticated
    */

    public function myJobs(Request $request)
    {
        $validation = Validator::make($request->all(), [
            // user id required
            'user_id' => ['required','exists:users,id'],
        ], [
            'user_id.required'=>'userId is required',
            'user_id.exists'=>'Invalid User Id',
        ]);
       
        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' =>$validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        //  $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        // if (! $personalAccessToken) {
        //     return response()->json([
        //         'statusCode' => Response::HTTP_BAD_REQUEST,
        //         'message' => 'Invalid token',
        //         'data' =>[],
        //     ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        // }
        // $user = $personalAccessToken->tokenable;

        // if ($user->id!=$request->user_id) {
        //     return response()->json([
        //         'statusCode' => Response::HTTP_BAD_REQUEST,
        //         'message' => 'mismatch token',
        //         'data' =>[],
        //     ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        // }

        $jobs=PilotJob::where('user_id', $request->user_id)
                     ->whereIn('status', [1,2,3])
                     ->select('id', 'job_title', 'job_description', 'file_attachment', 'job_budget', 'status', 'enquiry_type', 'created_at')
                     ->addSelect(['job_category' => Skill::select('skill_name')
                     ->whereColumn('skill_category_id', 'skills.id')
                     ->limit(1)])
                     ->with(['location:city,state,country,address,pilot_job_id'])
                     ->latest('id');
       
      
        if ($request->has('jobfilter') && $request->input('jobfilter')!='all') {
            $status=PilotJob::STATUS_AS_TEXT[$request->input('jobfilter')];
            $jobs=   $jobs->where('status', $status);
        }
       
        if ($request->has('q') && $request->input('q')!='') {
            $jobs=$jobs->where('job_title', 'like', '%'.$request->input('q').'%')
                ->orWhere(
                    function ($query) use ($request) {
                        $query->where('job_description', 'like', '%'.$request->input('q').'%');
                    }
                )->orWhere(function ($queryLocation) use ($request) {
                    $queryLocation->whereHas('location', function ($q) use ($request) {
                        $q->where('city', 'like', '%'.$request->input('q').'%')
                            ->orWhere('state', 'like', '%'.$request->input('q').'%');
                    });
                });
        }
        
        $no_of_jobs=$jobs->count();
        if ($request->has('page')) {
            $page=$request->input('page');
            $page=$page-1;
            $offset=$page*5;
            $jobs=$jobs->offset($offset);
        }
      
        $jobs=$jobs->take(10)->get();

        if ($jobs->isEmpty()) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'My Jobs not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'My Jobs fetch successfully',
                         'no_of_jobs'=>$no_of_jobs,
                         'data'=>$jobs
                     ])->setStatusCode(Response::HTTP_OK);
    }
}
