<?php

namespace App\Jobs;

use Image;
use Storage;
use ImageOptimizer;
use App\Models\CompanyProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CompanyProfileImageCrop implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $profile;
    public $tries =10;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CompanyProfile $companyProfile)
    {
        $this->profile=$companyProfile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $image =$this->profile->image;
        $directory=pathinfo($image, PATHINFO_DIRNAME);
        $base_image_name=pathinfo($image, PATHINFO_FILENAME);
        $extension=pathinfo($image, PATHINFO_EXTENSION);
       
        //let's build the size of array
        $sizes_arr=[
                "275,275",
                "160,160"  //blog sidebar
             ];
      
        
        //original image
        $image_name =$base_image_name.'.' . $extension;

        $destinationPath=public_path('/images/company/'.$this->profile->id.'/profile');

        $imageRealPath=pathinfo($image, PATHINFO_DIRNAME).'/'.pathinfo($image, PATHINFO_FILENAME).'.'.pathinfo($image, PATHINFO_EXTENSION);

       
        $resize_image = Image::make($imageRealPath);

        foreach ($sizes_arr as $size_value) {
            $array = explode(',', $size_value);
            $new_width = $array[0];
            $new_height = $array[1];
            $new_image_name =$base_image_name.'-'.$new_width.'x'.$new_height.'.'.$extension;
            $resize_image->resize($new_width, $new_height, function ($constraint) {
            })->save($destinationPath . '/' . $new_image_name);

            $new_img_to_optimzed=str_replace(config('app.url'), "", $destinationPath . '/' . $new_image_name);
            ImageOptimizer::optimize(public_path($new_img_to_optimzed));
        }

        $relativePath=str_replace(config('app.url'), "", $imageRealPath);
        ImageOptimizer::optimize(public_path($relativePath));
    }
}
