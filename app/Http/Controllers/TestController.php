<?php

namespace App\Http\Controllers;

use Image;
use Storage;
use Illuminate\Http\Request;
use App\Models\PilotEquipments;

class TestController extends Controller
{
    public function image()
    {
        $equipment=PilotEquipments::find(147);
        $image = $equipment->image;
        $directory=pathinfo($image, PATHINFO_DIRNAME);
        $base_image_name=pathinfo($image, PATHINFO_FILENAME);
        $extension=pathinfo($image, PATHINFO_EXTENSION);

       
       
        //let's build the size of array
        $sizes_arr=["400,400"];
     
        //original image
        $image_name =$base_image_name.'.' . $extension;

        $destinationPath=public_path('/images/'.$equipment->pilot_profile_id.'/equipment');

      
        $realPah=pathinfo($image, PATHINFO_DIRNAME).'/'.pathinfo($image, PATHINFO_FILENAME).'.'.pathinfo($image, PATHINFO_EXTENSION);

        $resize_image = Image::make($realPah);

        foreach ($sizes_arr as $size_value) {
            $array = explode(',', $size_value);
            $new_width = $array[0];
            $new_height = $array[1];
            $new_image_name =$base_image_name.'-'.$new_width.'x'.$new_height.'.'.$extension;
            $resize_image->resize($new_width, $new_height, function ($constraint) {
                //$constraint->aspectRatio();
            })->save($destinationPath . '/' . $new_image_name);
        }
    }
}
