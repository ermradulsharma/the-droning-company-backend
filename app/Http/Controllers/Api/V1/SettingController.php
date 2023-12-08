<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends Controller
{

    /**
    * Setting
    *
    * This endpoint allows you to fetch pilot dashboard data.
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
                "message": "setting fetch successfully",
                "data": [
                    {
                    "block_id": 1,
                    "block_title": "Racing Videos",
                    "block_subTitle1": "dsdfs2",
                    "block_subTitle2": "sdfdsf2",
                    "block_description": "Take a seat as co-pilot and experience the world of drone racing.",
                    "block_button_link": "https://laravel.com/docs/8.x/helpers#method-str-ordered-uuid2",
                    "block_image": "http://local.project1/images/setting/8DWZ0JlzKAJXCYIOwUqZ5RjfdGSEtLM6gJaadeOi.png"
                    },
                    {
                    "block_id": 2,
                    "block_title": "Free Tools and Education",
                    "block_subTitle1": "2",
                    "block_subTitle2": "dsdfs",
                    "block_description": "Learn more about droning, becoming a drone pilot, or advance your skills with The Droning Company's Free Tools and Education.",
                    "block_button_link": "https://laravel.com/docs/8.x/helpers#method-str-ordered-uuid",
                    "block_image": "http://local.project1/images/setting/n7EJGisWa1tTaGXfmiZySSpqwtvqPtdI1L34mQXh.png"
                    }
                    ]
      }

    */
    public function __invoke(Request $request)
    {
        if (!$request->has('ids')) {
            return response()->json([
            'statusCode'=>404,
            'message' =>'setting ids missing found',
            'data'=>[]
        ])->setStatusCode(404);
        }
        
        $setting=Setting::whereIn('id', explode(",", $request->ids))
                    ->orderBy('id', 'ASC')
                    ->select(
                        'id as block_id',
                        'key_1 as block_title',
                        'key_2 as block_subTitle1',
                        'key_3 as block_subTitle2',
                        'value as block_description',
                        'key_link as block_button_link',
                        'block_image',
                        'updated_at'
                    )
                    ->get();
       
        if (!$setting) {
            return response()->json([
            'statusCode'=>404,
            'message' =>'setting not found',
            'data'=>[]
        ])->setStatusCode(404);
        }

        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'setting fetch successfully',
            'data'=>$setting
        ])->setStatusCode(Response::HTTP_OK);
    }
}
