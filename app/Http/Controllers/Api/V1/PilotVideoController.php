<?php
namespace App\Http\Controllers\Api\V1;

use App\Models\PilotVideos;
use App\Models\PilotProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;

class PilotVideoController extends Controller
{


    /**
    * Pilot Reel video store
    *
    * This endpoint allows you to store pilot reel video.
    *
    * If everything is okay, you'll get a `200` OK response with data.
    *

    *
    * @param \Illuminate\Http\Request  $request
    * @param $id required
    * @return \Illuminate\Http\Response
    *
    * @response status=200  {
                "statusCode": 200,
                "message": "profile reel video save successfully",
                "data": []
      }
    *
    *  @authenticated
    */
    public function store(Request $request, int $user_id)
    {
        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        if (!$personalAccessToken) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'bearerToken is required',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $user = $personalAccessToken->tokenable;

        if ($user->id!=$user_id) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid bearerToken',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $validation = Validator::make($request->all(), [
             'reel_video' => ['required'],
             'reel_video.*.video_type' => 'required',
             'reel_video.*.video_url' => 'required',
             'reel_video.*.position' => 'required',
        ]);


        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $profile=PilotProfile::byUser($user_id)->latest('id')->first();
        if (!$profile) {
            return response()->json([
                'statusCode' =>401,
                'message' =>'Please build your profile first',
                'data' =>[],
            ])->setStatusCode(401);
        }
        $ids=[];
        if ($request->has('reel_video')) {
            foreach ($request->input('reel_video') as $key => $value) {
                $video= new PilotVideos();
                $video->type=ucfirst($value['video_type']);
                $video->video=$value['video_url'];
                $video->video_key=$this->findVideoKey($video->type, $value['video_url']);
                $video->position=ucfirst($value['position']);
                $video->pilot_profile_id=$profile->id;
                $video->save();
                $ids[]=$video->id;

                if (ucfirst($value['position'])=="Main") {
                    PilotVideos::where('pilot_profile_id', $profile->id)
                                ->whereNotIn('id', [$video->id])
                                ->update(['position'=>'Gallery']);
                }
            }
        }

        $videos=PilotVideos::whereIn('id', $ids)
                        ->select('id as pilot_video_id', 'type as video_type', 'video as video_url', 'video_key', 'position')
                        ->get();
        
        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile reel video save successfully',
            'data'=>$videos,
        ])->setStatusCode(Response::HTTP_OK);
    }

    /**
    * Pilot Reel video show
    *
    * This endpoint allows you to fetch pilot profile reel video.
    *
    * If everything is okay, you'll get a `200` OK response with data.
    *

    *
    * @param \Illuminate\Http\Request  $request
    * @param $id required
    * @return \Illuminate\Http\Response
    *
    * @response status=200  {
                "statusCode": 200,
                "message": "profile reel video fetch successfully",
                "data": [
                    {
                        "video_type": "Youtube",
                        "video_url": "https://www.youtube.com/watch?v=CXa0f4-dWi4",
                        "video_key": "CXa0f4-dWi4",
                        "position": "Main"
                    },
                    {
                        "video_type": "Youtube",
                        "video_url": "https://www.youtube.com/watch?v=CXa0f4-dWi4",
                        "video_key": "CXa0f4-dWi4",
                        "position": "Gallery"
                    }
                ]
      }
    *

    * @response status=4004 {
          "statusCode": 404,
          "message": "profile reel not found",
          "data": []
      }
     * @authenticated
    */
    public function show(Request $request, int $user_id)
    {
        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        if (!$personalAccessToken) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'bearerToken is required',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $user = $personalAccessToken->tokenable;

        if ($user->id!=$user_id) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid bearerToken',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $profile=PilotProfile::byUser($user_id)->latest('id')->first();

        $pilot_profile_id=$profile->id ?? '';
        $videos=PilotVideos::where('pilot_profile_id', $pilot_profile_id)
                        ->select('id as pilot_video_id', 'type as video_type', 'video as video_url', 'video_key', 'position')
                        ->latest()
                        ->get();

        if ($videos->isEmpty()) {
            return response()->json([
            'statusCode'=>404,
            'message' =>'profile reel not found',
            'data'=>[]
            ])->setStatusCode(404);
        }
        

        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile reel video fetch successfully',
            'data'=>$videos
        ])->setStatusCode(Response::HTTP_OK);
    }



    private function findVideoKey($video_type, $video_url)
    {
        if ($video_type== 'Youtube') {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url, $match);

            if (!empty($match)) {
                return    $video_key = ($match[1] != '') ? $match[1] : 'NA' ;
            }
        } else {
            preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $video_url, $match);
                    
            if (!empty($match)) {
                return   $video_key = ($match[3] != '') ? $match[3] : 'NA' ;
            }
        }
    }


    /**
    *  Pilot Video Reel delete
    *
    * This endpoint allows you to remove pilot Equipment.
    *
    * If everything is okay, you'll get a `200` OK response with data.
    *

    *
    * @param \Illuminate\Http\Request  $request
    * @param $id required
    * @return \Illuminate\Http\Response
    *
    * @response status=200  {
              "statusCode": 200,
              "message": "Pilot Equipment delete successfully",
              "data": []
    }
    * @authenticated
    */
    public function remove(Request $request, int $pilot_video_id)
    {
        $video=PilotVideos::find($pilot_video_id);

        if (!$video) {
            return response()->json([
            'statusCode'=>404,
            'message' =>'Invalid pilot video id',
            'data'=>[]
        ])->setStatusCode(404);
        }
        
        $video->forceDelete();

        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'Pilot video delete successfully',
            'data'=>[]
        ])->setStatusCode(Response::HTTP_OK);
    }

    /**
    *  Pilot Video Reel mark As Main
    *
    * This endpoint allows you to mark as Main video.
    *
    * If everything is okay, you'll get a `200` OK response with data.
    *

    *
    * @param \Illuminate\Http\Request  $request
    * @param $id required
    * @return \Illuminate\Http\Response
    *
    * @response status=200  {
              "statusCode": 200,
              "message": "Pilot video mark as Main successfully",
              "data": [
                    {
                        "video_type": "Youtube",
                        "video_url": "https://www.youtube.com/watch?v=CXa0f4-dWi4",
                        "video_key": "CXa0f4-dWi4",
                        "position": "Main"
                    },
                    {
                        "video_type": "Youtube",
                        "video_url": "https://www.youtube.com/watch?v=CXa0f4-dWi4",
                        "video_key": "CXa0f4-dWi4",
                        "position": "Gallery"
                    }

              ]
    }
    * @authenticated
    */
    public function markAsMainVideo(Request $request, int $pilot_video_id)
    {
        $video=PilotVideos::find($pilot_video_id);


        if (!$video) {
            return response()->json([
            'statusCode'=>404,
            'message' =>'Invalid pilot video id',
            'data'=>[]
        ])->setStatusCode(404);
        }


        $video->update(['position'=>'Main']);

        PilotVideos::where('pilot_profile_id', $video->pilot_profile_id)
                    ->whereNotIn('id', [$video->id])
                    ->update(['position'=>'Gallery']);


        $videos=PilotVideos::where('pilot_profile_id', $video->pilot_profile_id)
                        ->select('id as pilot_video_id', 'type as video_type', 'video as video_url', 'video_key', 'position')
                        ->latest()
                        ->get();

        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'Pilot video mark as Main successfully',
            'data'=>$videos
        ])->setStatusCode(Response::HTTP_OK);
    }
}
