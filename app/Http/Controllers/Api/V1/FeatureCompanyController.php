<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\companyService;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use App\Services\CompanyServicesService;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class FeatureCompanyController extends Controller
{
    public function index(Request $request)
    {
        $profiles = CompanyProfile::with('userSkill')->latest()->featured()->active()->take(4)->get();
        if ($profiles->isEmpty()) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'featured Profile not found!',
                'data' => []
            ])->setStatusCode(404);
        }
        $data = [];
        foreach ($profiles as $key => $value) {
            $data[] = [
                'company_id' => $value->id,
                'user_id' => $value->user_id,
                'name' => $value->users->name,
                'title' => $value->title,
                'slug' => $value->slug,
                'short_description' => $value->short_description,
                'image' => asset($value->image),
                'services' => (new CompanyServicesService())->company($value->id),
                'is_insured' => $value->is_insured
            ];
        }
        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'featured Profile fetch successfully',
            'data' => $data
        ])->setStatusCode(Response::HTTP_OK);
    }

    public function homeFeatured(Request $request)
    {
        //home_featured_updated_at
        $profiles = CompanyProfile::homeFeatured()->active()->take(1)->latest('updated_at')->get();
        // Log::debug("profiles ".print_r($profiles,true));
        if ($profiles->isEmpty()) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'featured Profile not found!',
                'data' => []
            ])->setStatusCode(404);
        }
        $data = [];
        foreach ($profiles as $key => $value) {
            $data[] = [
                'company_profile_id' => $value->id,
                'user_id' => $value->user_id,
                'name' => $value->title, //$value->user->name,
                'title' => $value->title,
                'slug' => $value->slug,
                'short_description' => $value->short_description,
                'image' => asset($value->logo)
            ];
        }
        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'featured Profile fetch successfully',
            'data' => $data
        ])->setStatusCode(Response::HTTP_OK);
    }

    public function categoryFeature(Request $request)
    {
        $profiles = CompanyProfile::query()->active()->subscribed()->orderBy('is_featured', 'desc')->orderBy('id', 'desc');
        $profile_count = $profiles->count();
        if ($request->has('q') && $request->input('q') != '' && $request->input('q') != 'undefined') {
            if ($json_exists = $this->isJSON($request->input('q'))) {
                $decode_string = json_decode($request->input('q'));
                $city = $decode_string->city ?? '';
            } else {
                $city = $request->input('q');
            }
            $profiles = $profiles->where('city', 'like', '%' . $city . '%')->orWhere('state', 'like', '%' . $city . '%')->orWhere('zip_code', 'like', '%' . $city . '%')->orWhere('address', 'like', '%' . $city . '%')->orWhere('title', 'like', '%' . $city . '%');
        }
        if ($request->has('service') && $request->input('service') != '') {
            $profile_services = \DB::table('company_services')->where('service_id', $request->input('service'))->get()->pluck('company_id');
            $profiles = $profiles->whereIn('id', $profile_services);
        }
        $profile_count = $profiles->count();
        if ($request->has('page')) {
            $page = $request->input('page');
            $page = $page - 1;
            $offset = $page * 4;
            $profiles = $profiles->offset($offset);
        }
        $profiles = $profiles->take(4)->latest()->get();
        if ($profiles->isEmpty()) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'profiles not found!',
                'data' => []
            ])->setStatusCode(404);
        }
        $master = [];
        foreach ($profiles as $key => $value) {
            $data = $value;
            $data['name'] = @$value->user->name;
            $data['description'] = \Str::limit($value->description, 150);
            $data['services'] = (new CompanyServicesService())->company($value->id);
            unset($data['users']);
            $master[] = $data;
        }
        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'profiles fetched successfully',
            'profile_count' => $profile_count,
            'data' => $master,
        ]);
    }


    public function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) ? true : false;
    }


    public function findLatitudeAndLongitude($city)
    {
        $apiKey  = 'AIzaSyCLfVtYfTOVAcjsMpVDVltEu7SJIP007Uw';
        $address = urlencode($city);
        $url     = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $address . "&key=" . $apiKey;
        $resp    = json_decode(file_get_contents($url), true);
        $lat    = $resp['results'][0]['geometry']['location']['lat'] ?? '';
        $long   = $resp['results'][0]['geometry']['location']['lng'] ?? '';
        return  [$lat, $long];
    }

    public function sitemap(Request $request)
    {
        $profiles = CompanyProfile::query()->select('id', 'is_featured', 'slug', 'updated_at', 'title', 'user_id')->active()->orderBy('is_featured', 'desc')->orderBy('id', 'desc')->get();
        if ($profiles->isEmpty()) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'profiles not found!',
                'data' => []
            ])->setStatusCode(404);
        }
        $master = [];
        foreach ($profiles as $key => $value) {
            $master[] = [
                'name' => $value->user->name,
                'title' => $value->title,
                'slug' => $value->slug,
                'lastModified' => $value->updated_at->format('Y-m-d')
            ];
        }
        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'profile fetch successfully',
            'data' => $master,
        ]);
    }
}
