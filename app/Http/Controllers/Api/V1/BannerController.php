<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\BannerPageList;

class BannerController extends Controller
{
    function index($page_slug)
    {
    
        $banner = BannerPageList::with('bannerSection','bannerSection.banner')->where('slug',$page_slug)->first();
        if($banner){
            return response()->json([
                'status'=> true,
                'statusCode'=> 200,
                'message' => 'banner fetch successfully',
                'data'=> $banner,
            ]);
        }
        else{
            return response()->json([
                'status'=> false,
                'statusCode'=> 404,
                'message' => 'not found',
                'data'=> '',
            ]);
        }
        
    }
}
