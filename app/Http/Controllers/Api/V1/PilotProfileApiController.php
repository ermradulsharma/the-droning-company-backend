<?php

namespace App\Http\Controllers\Api\V1;

use Gate;
use App\Models\PilotSkills;
use App\Models\PilotVideos;
use App\Models\PilotAddress;
use App\Models\PilotGallery;
use App\Models\PilotProfile;
use Illuminate\Http\Request;
use App\Services\SkillService;
use App\Models\PilotEquipments;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;

class PilotProfileApiController extends Controller
{
    /**
     * Pilot Profile .
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response Pilot profile not found!
     *
     *
     *
     * @bodyParam id integer required The id of the user/pilot. Example: 1,2,3
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * (Ex - User Id Required)
     *
     * @return \Illuminate\Http\Response
     *
     * @response
     *  {
            "statusCode": 200,
            "message": "pilot profile fetch successfully",
            "data": {
                "profile": {
                    "id": 2,
                    "title": "Minim nostrum molest",
                    "slug": "lionel-moss",
                    "image": "http://local.drone/images/2/profile/QB5nBYkIOxqp79Xg2jGb0BhCYh5oJASKY10g7xQ9.png",
                    "description": "<p>Logn&nbsp;description</p>",
                    "short_description": "<p>short description</p>",
                    "is_certified": "No",
                    "travel_option": "No",
                    "is_featured": "No",
                    "metatitle": "Occaecat vel qui dol",
                    "metakeyword": "Labore ullamco sint",
                    "metadescription": "<p>Meta description</p>"
                },
                "skills": [
                    {
                        "id": 1,
                        "name": "Skill-1"
                    },
                    {
                        "id": 2,
                        "name": "Skill-2"
                    }
                ],
                "equipment": [
                    {
                        "title": "Adipisci eiusmod ven",
                        "image": "http://local.drone/images/2/equipments/2rshlAHFtllnKz65LK3p2X6Tm6Mm8Z8m8uAxe5yK.png",
                        "manufacturer": "Ullamco Nam itaque m"
                    },
                    {
                        "title": "Nesciunt officia a",
                        "image": "http://local.drone/images/2/equipments/13fjfcbJ2Ww5gLCAsVTY1wbsY9MZ0jQJLWjLbAwD.png",
                        "manufacturer": "Et id explicabo Lab"
                    }
                ],
                "address": [
                    {
                        "address_line1": "47 South Old Drive",
                        "address_line2": "Ab deserunt et maior",
                        "country": "United Kingdom",
                        "state": "Anglesey",
                        "city": "Magnam in ad ut null",
                        "zip": "92748"
                    },
                    {
                        "address_line1": "156 North Rocky New Road",
                        "address_line2": "Quo vel similique ut",
                        "country": "India",
                        "state": "Bihar",
                        "city": "Eaque eum inventore",
                        "zip": "841301"
                    }
                ],
                "video": [
                    {
                        "type": "Youtube",
                        "video": "https://www.youtube.com/watch?v=9nsZmvkRfj4",
                        "video_key": "9nsZmvkRfj4"
                    },
                    {
                        "type": "Vimeo",
                        "video": "http://vimeo.com/channels/11111111",
                        "video_key": "11111111"
                    }
                ],
                "gallery": [
                    {
                        "image": "http://local.drone/images/2/gallery/N3SpeoyKRNAp1vjxrR82UqmOmherU7PW71vLyFCE.png"
                    },
                    {
                        "image": "http://local.drone/images/2/gallery/FXe169kbW6XXUMJPxF9BzRPk6mvz3KJpxQcDovFE.png"
                    }
                ]
            }
        }
     *
     *
     * @response status=404 {
            "statusCode": 404,
            "message": "Pilot profile not found!",
            "data": []
        }
     *
     */
    public function show($slug)
    {
        $profile = PilotProfile::where('slug', $slug)->first();
        if (!$profile) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'Pilot profile not found!',
                'data' => []
            ])->setStatusCode(404);
        }
        $data = $profile;
        $name = $profile->users->name;
        $data['name'] = $name;
        $data['mobile'] = $profile->users->mobile;
        $data['email'] = $profile->users->email;
        $data['member_since'] = $profile->created_at->format('Y');
        $data['skills'] = (new SkillService())->pilot($profile->id);
        unset($data['users']);
        unset($profile->license_image);
        $PilotEquipments = PilotEquipments::query()->select('title', 'image', 'manufacturer')->where('pilot_profile_id', $profile->id)->where('status', '1')->get();
        $data = ['profile' => $data, 'equipment' => $PilotEquipments];
        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'pilot profile fetch successfully',
            'data' => $data
        ])->setStatusCode(Response::HTTP_OK);
    }
    /**
     * Pilot Profile .
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response Pilot profile not found!
     *
     *
     *
     * @bodyParam id integer required The id of the user/pilot. Example: 1,2,3
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * (Ex - User Id Required)
     *
     * @return \Illuminate\Http\Response
     *
     * @response
     *  {
           "statusCode": 200,
           "message": "pilot Gallery fetch successfully",
           "data": {
                   {
                       "image": "http://local.drone/images/2/gallery/N3SpeoyKRNAp1vjxrR82UqmOmherU7PW71vLyFCE.png"
                   },
                   {
                       "image": "http://local.drone/images/2/gallery/FXe169kbW6XXUMJPxF9BzRPk6mvz3KJpxQcDovFE.png"
                   }
           }
       }
     *
     *
     * @response status=404 {
           "statusCode": 404,
           "message": "Pilot profile not found!",
           "data": []
       }
     *
     */

    public function portfolio($id)
    {
        $pilotGallery = PilotGallery::where('pilot_profile_id', $id)
            ->where('status', '1')
            ->get();

        if ($pilotGallery->isEmpty()) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'pilot Gallery not found!',
                'data' => []
            ])->setStatusCode(404);
        }


        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'pilot Gallery fetch successfully',
            'data' => $pilotGallery
        ])->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Pilot Profile New.
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response Pilot profile not found!
     *
     *
     *
     * @bodyParam id integer required The id of the user/pilot. Example: 1,2,3
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * (Ex - User Id Required)
     *
     * @return \Illuminate\Http\Response
     *
     * @response
     *  {
            "statusCode": 200,
            "message": "pilot Gallery fetch successfully",
            "data": {
                    {
                        "image": "http://local.drone/images/2/gallery/N3SpeoyKRNAp1vjxrR82UqmOmherU7PW71vLyFCE.png"
                    },
                    {
                        "image": "http://local.drone/images/2/gallery/FXe169kbW6XXUMJPxF9BzRPk6mvz3KJpxQcDovFE.png"
                    }
            }
        }
     *
     *
     * @response status=404 {
            "statusCode": 404,
            "message": "Pilot profile not found!",
            "data": []
        }
     *
     */
    public function portfolioNew($id)
    {
        session()->forget('pilotGallery_session_value');

        $pilotGallery = PilotGallery::query()
            ->where('pilot_profile_id', $id)
            ->where('status', '1')
            ->chunk(3, function ($q) {
                session()->push('pilotGallery_session_value', $q);
            });


        if (!session()->has('pilotGallery_session_value')) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'pilot Gallery not found!',
                'data' => []
            ])->setStatusCode(404);
        }

        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'pilot  Gallery fetch successfully',
            'data' => session()->get('pilotGallery_session_value'),
        ])->setStatusCode(Response::HTTP_OK);
    }

    public function reel($id)
    {
        $reels = PilotVideos::where('pilot_profile_id', $id)
            ->whereNotIn('position', ['Main'])
            ->get();

        $main = PilotVideos::where('pilot_profile_id', $id)
            ->where('position', 'Main')
            ->first();


        if ($reels->isEmpty() && !$main) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'pilot reels not found!',
                'data' => []
            ])->setStatusCode(404);
        }


        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'pilot reels fetch successfully',
            'main' => $main,
            'data' => $reels
        ])->setStatusCode(Response::HTTP_OK);
    }



    public function serviceAddress(int $profile_id)
    {
        $address = PilotAddress::query()
            ->where('pilot_profile_id', $profile_id)
            ->get();


        if ($address->isEmpty()) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'service location not found!',
                'data' => []
            ])->setStatusCode(404);
        }

        $locations = [
            ['New delhi', 28.644800, 77.216721, 4],
            ['Gazibad', 28.667856, 77.449791, 5],
            ['Cronulla Beach', 19.075983, 72.877655, 3],
            ['kanpur', 26.449923, 80.331871, 3],
        ];

        $data = [];
        foreach ($address as $key => $value) {
            //  dd($value->getLatitudeAndLongitude());
            if ((!$value->latitude) || (!$value->longitude)) {
                $sameLocationGeodecoingExist = PilotAddress::where(['city' => $value->city, 'state' => $value->state])
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->first();

                if (!$sameLocationGeodecoingExist) {
                    [$latitude, $longitude] = $value->getLatitudeAndLongitude();

                    $newGeo = PilotAddress::where('id', $value->id)->first();

                    $newGeo->latitude = $latitude;
                    $newGeo->longitude = $longitude;
                    $newGeo->save();
                } else {
                    $newGeo = PilotAddress::where('id', $value->id)->first();
                    $newGeo->latitude = $sameLocationGeodecoingExist->latitude ?? '';
                    $newGeo->longitude = $sameLocationGeodecoingExist->longitude ?? '';
                    $newGeo->save();
                }
                $value->refresh();

                $pilot_latitude = $newGeo->latitude;
                $pilot_longitude = $newGeo->longitude;
            } else {
                [$latitude, $longitude] = $value->getLatitudeAndLongitude();
                $pilot_latitude = $latitude;
                $pilot_longitude = $longitude;
            }
            //dd($value, $pilot_latitude, $pilot_longitude, $value->getLatitudeAndLongitude());
            $data[] = [
                $value->stateRelation->name ?? '',
                $value->city,
                $pilot_latitude,
                $pilot_longitude

            ];
        }


        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'service location fetch successfully',
            'data' => $data
        ])->setStatusCode(Response::HTTP_OK);
    }
}
