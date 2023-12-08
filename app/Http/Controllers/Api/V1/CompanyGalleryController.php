<?php

namespace App\Http\Controllers\Api\V1;

use Storage;
use App\Models\CompanyGallery;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use App\Jobs\CompanyGalleryImageCrop;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use File;

class CompanyGalleryController extends Controller
{

    /**
    * Company Gallery create
    */
    public function store(Request $request, int $user_id)
    {
        $validation = Validator::make($request->all(), [
             'gallery'=>['required'],
             'gallery.*.image' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $profile=CompanyProfile::byUser($user_id)->latest('id')->first();

        if (!$profile) {
            return response()->json([
                'statusCode' =>401,
                'message' =>'Please build your profile first',
                'data' =>[],
            ])->setStatusCode(401);
        }
        $i=1;
        foreach ($request->input('gallery') as $key => $value) {
            $base64_image = $value['image'];
            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
                $data = substr($base64_image, strpos($base64_image, ',') + 1);
                $data = base64_decode($data);
                $imgforext = explode(',', $base64_image);
                $ini = substr($imgforext[0], 11);
                $type = explode(';', $ini);
                $extension = $type[0]; // results extension
                $img = str_replace('data:image/' . $extension . ';base64,', '', $base64_image);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $file = uniqid() . '.' . $extension;
                $allConfig ='images/company/' .$profile->id. '/gallery/' . $file;
                Storage::disk('public_uploads')->put($allConfig, $data);

                $gallery=CompanyGallery::create([
                    'company_id'=>$profile->id,
                    'image'=>$allConfig,
                    'status'=>'1'
                ]);
                CompanyGalleryImageCrop::dispatch($gallery)->delay(now()->addSeconds(3));
            }
            $i++;
        }

        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile gallery save successfully',
            'data'=>[]
        ])->setStatusCode(Response::HTTP_OK);
    }

    /**
    * Company Gallery show
    */
    public function show(Request $request, int $user_id)
    {
        $profile=CompanyProfile::byUser($user_id)->latest('id')->first();
        $company_id=$profile->id ?? '';
        $gallery=CompanyGallery::where('company_id', $company_id)->select('id as gallery_id', 'image')->latest('id')->get();
        if ($gallery->isEmpty()) {
            return response()->json([
            'statusCode'=>404,
            'message' =>'profile gallery not found',
            'data'=>[]
            ])->setStatusCode(404);
        }
        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile gallery fetch successfully',
            'data'=>$gallery
        ])->setStatusCode(Response::HTTP_OK);
    }


    /**
    * Company Gallery delete
    */
    public function remove(Request $request, CompanyGallery $gallery_id)
    {
        if(File::exists(public_path($gallery_id->image))){
            File::delete(public_path($gallery_id->image));
        }
        $gallery_id->forceDelete();

        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile gallery image delete successfully',
            'data'=>[]
        ])->setStatusCode(Response::HTTP_OK);
    }
}
