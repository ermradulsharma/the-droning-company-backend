<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\PilotProfile;
use App\Models\PilotAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SearchApiController extends Controller
{
    /**
     * Search Result.
     * If everything is okay,you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a Pilot not found!
     *
     *
     * <aside class="notice">basepath/api/v1/search.</aside>
     *
     *
     * @queryParam ?zip Example ?zip=zip code Example 110044.
     * @queryParam ?state Example ?state=state_id Example 83..
     * @queryParam ?city Example ?country=country_id Example 99..
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @response
     *  {
            "statusCode": 200,
            "message": "Pilot Search result fetch successfully",
            "data": {
                "current_page": 1,
                "data": [
                    {
                        "title": "Minim nostrum molest",
                        "slug": "lionel-moss",
                        "short_description": "<p>short description</p>",
                        "is_certified": "No",
                        "travel_option": "No",
                        "is_featured": "No",
                        "address_line1": "47 South Old Drive",
                        "address_line2": "Ab deserunt et maior",
                        "city": "Magnam in ad ut null",
                        "zip": "92748",
                        "state": "Anglesey"
                    },
                    {
                        "title": "Minim nostrum molest",
                        "slug": "lionel-moss",
                        "short_description": "<p>short description</p>",
                        "is_certified": "No",
                        "travel_option": "No",
                        "is_featured": "No",
                        "address_line1": "156 North Rocky New Road",
                        "address_line2": "Quo vel similique ut",
                        "city": "Eaque eum inventore",
                        "zip": "841301",
                        "state": "Bihar"
                    },

                ],
                "first_page_url": "http://local.drone/api/v1/search?page=1",
                "from": 1,
                "next_page_url": "http://local.drone/api/v1/search?page=2",
                "path": "http://local.drone/api/v1/search",
                "per_page": 10,
                "prev_page_url": null,
                "to": 10
            }
        }
     *
     *
     * @response status=404 {
            "statusCode": 404,
            "message": "Pilot Search not found!",
            "data": []
        }
     *
     *
     *
     */


    public function index(Request $request)
    {
        $pilotResults = PilotAddress::query()
                ->select('pilot_profile.title', 'pilot_profile.slug', 'pilot_profile.short_description', 'pilot_profile.is_certified', 'pilot_profile.travel_option', 'pilot_profile.is_featured', 'pilot_address.address_line1', 'pilot_address.address_line2', 'pilot_address.city', 'pilot_address.zip', 'state.name as state')
                ->join('pilot_profile', 'pilot_address.pilot_profile_id', '=', 'pilot_profile.id')
                ->join('state', 'pilot_address.state', '=', 'state.id');

        if ($request->has('zip')) {
            $pilotResults=$pilotResults->where('pilot_address.zip', '=', $request->input('zip'));
        }

        if ($request->has('state')) {
            $pilotResults=$pilotResults->where('pilot_address.state', '=', $request->input('state'));
        }

        if ($request->has('city')) {
            $pilotResults=$pilotResults->where('pilot_address.city', 'like', '%'.$request->input('city').'%');
        }
        
        $totalResult=$pilotResults->count();
        $pilotResults=$pilotResults->simplePaginate(10);

        
        if ($pilotResults->isEmpty()) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'Pilot Search result not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }
        
        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' => 'Pilot Search result fetch successfully',
            'post_count'=>$totalResult,
            'data'=>$pilotResults
        ]);
    }
}
