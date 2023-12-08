<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Skill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class SkillsApiController extends Controller
{
    /**
     * Method - GET
     * All Pilot Skills .
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response Pilot skills not found!
     *
     *
     * <aside class="notice">basepath/api/v1/pilot-skill.</aside>
     *
     *
     * @return \Illuminate\Http\Response
     *
     * @response
     *  {
            "statusCode": 200,
            "message": "Skill Category fetch successfully",
            "data": [
                {
                    "skill_name": "Skill-1"
                },
                {
                    "skill_name": "Skill-2"
                },
                {
                    "skill_name": "Skill-3"
                },
                {
                    "skill_name": "Skill-4"
                }
            ]
        }
     *
     *
     * @response status=404 {
            "statusCode": 404,
            "message": "Pilot Skills not found!",
            "data": []
       }
     *
     *
     *
     */


    public function index()
    {
        $skills = Skill::query()
            ->select('id', 'skill_name')
            ->where('status', '1')
            ->orderBy('skill_name', 'ASC')
            ->get();

        if ($skills->isEmpty()) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'Skill Categories not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'Skill Categories fetch successfully',
             'data'=>$skills
        ])->setStatusCode(Response::HTTP_OK);
    }
}
