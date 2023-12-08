<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ContentPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Collection;

class CmsApiController extends Controller
{
    /**
     * About .
     * Get Method
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response about data not found!
     *
     *
     * <aside class="notice">basepath/api/v1/about.</aside>
     *
     *
     * @return \Illuminate\Http\Response
     *
     * @response
     *  {
            "statusCode": 200,
            "message": "about fetch successfully",
            "data": [
                {
                    "title": "Specializing in Drone Services, and Aerial Photography",
                    "page_text": "<p>There are many variations of passages of lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly.\n</p>"
                }
            ]
        }
     *
     *
     * @response status=404 {
            "statusCode": 404,
            "message": "about data not found!",
            "data": []
        }
     *
     *
     *
     */

    public function index($slug)
    {
        $cms = ContentPage::query()
            ->where('slug', $slug)->get();

        if ($cms->isEmpty()) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'Data not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'Data found successfully',
             'data'=> $cms
        ])->setStatusCode(Response::HTTP_OK);
    }
	
	 public function list()
    {
        $cms = ContentPage::query()
                ->select('title', 'slug', 'id')->get()->map(function ($collable) {
                  

                    if (in_array('Our Team', $collable->categories->pluck('name')->toArray())) {
                      
                        $slug='our-team/'.$collable->slug;
                    } elseif(in_array('WebSite Page', $collable->categories->pluck('name')->toArray())) {
						//WebSite Page
                        $slug='page/'.$collable->slug;
                    }else {
						 $slug=$collable->slug;
					}
                    return      [
                'id'=>$collable->id,
                'title'=>$collable->title,
                'slug'=>$slug,

            ];
                });


        if ($cms->isEmpty()) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'Data not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'Data found successfully',
             'data'=> $cms
        ])->setStatusCode(Response::HTTP_OK);
    }
}
