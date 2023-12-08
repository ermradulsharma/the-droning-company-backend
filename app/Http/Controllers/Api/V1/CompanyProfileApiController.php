<?php

namespace App\Http\Controllers\Api\V1;

use Gate;
use App\Models\CompanyService;
use App\Models\CompanyVideo;
use App\Models\PilotAddress;
use App\Models\CompanyGallery;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use App\Services\CompanyServicesService;
use App\Models\PilotEquipments;
use App\Http\Controllers\Controller;
use Hamcrest\Arrays\IsArray;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Blog;

class CompanyProfileApiController extends Controller
{
    /**
     * Company Profile .
     */
    public function show($slug)
    {
        $profile=CompanyProfile::where('slug', $slug)->first();
        if (!$profile) {
            return response()->json([
                 'statusCode' =>404,
                'message' => 'Company profile not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }
        $data=$profile;
        $name=$profile->user->name;
        $data['name']=$name;
        $data['mobile']=$profile->phone;
        $data['email']=$profile->email;
        $data['member_since']=$profile->created_at->format('Y');
        $data['services']=(new CompanyServicesService())->company($profile->id);
        $data['press_release_1'] = json_decode($profile->press_release_1);
        $data['press_release_2'] = json_decode($profile->press_release_2);
        $data['press_release_3'] = json_decode($profile->press_release_3);
        $data['dc_articles'] = json_decode($profile->dc_articles);

        $article_urls = array();
        $dc_articles = $profile->dc_articles;
        if(is_array($dc_articles)){
            foreach($dc_articles as $art_url){
                $uriSegments = explode("/", parse_url($art_url->article, PHP_URL_PATH));
                $article_urls[] = @$uriSegments[2];
            }
        }
        $data['dc_articles'] = Blog::whereIn('slug', $article_urls)->select('id', 'title', 'image', 'excerpt', 'description', 'slug')->get();
        //unset($data['users']);
        $data = ['profile'=>$data];
        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' => 'Company profile fetch successfully',
            'data'=>$data
        ])->setStatusCode(Response::HTTP_OK);
    }

    public function portfolio($id)
    {
        $pilotGallery = CompanyGallery::where('company_id', $id)->where('status', '1')->get();
        if ($pilotGallery->isEmpty()) {
            return response()->json([
                 'statusCode' =>404,
                'message' => 'Company Gallery not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }
        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' => 'Company Gallery fetch successfully',
            'data'=>$pilotGallery
        ])->setStatusCode(Response::HTTP_OK);
    }


    public function portfolioNew($id)
    {
        session()->forget('companyGallery_session_value');
        $pilotGallery = CompanyGallery::query()->where('company_id', $id)->where('status', '1')->chunk(3, function ($q) {
            session()->push('companyGallery_session_value', $q);
        });

        if (!session()->has('companyGallery_session_value')) {
            return response()->json([
                'statusCode' =>404,
                'message' =>'pilot Gallery not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'pilot  Gallery fetch successfully',
             'data'=>session()->get('companyGallery_session_value'),
        ])->setStatusCode(Response::HTTP_OK);
    }

    public function reel($id)
    {
        if($id){
            $main = CompanyVideo::where('company_id', $id)->inRandomOrder()->where('position', 'Main')->first();
            $reels = CompanyVideo::where('company_id', $id)->whereNotIn('id', [$main->id])->get();
        }else{
            $reels = new CompanyVideo();
        }

        if ($reels->isEmpty() && !$main) {
            return response()->json([
                 'statusCode' =>404,
                'message' => 'Company reels not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }
        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' => 'Company reels fetch successfully',
            'main'=>$main,
            'data'=>$reels
        ])->setStatusCode(Response::HTTP_OK);
    }


}
