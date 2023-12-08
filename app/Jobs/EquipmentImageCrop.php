<?php

namespace App\Jobs;

use Image;
use Storage;
use ImageOptimizer;
use Illuminate\Bus\Queueable;
use App\Models\PilotEquipments;
use App\Jobs\EquipmentImageOptimized;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class EquipmentImageCrop implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    protected $equipment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PilotEquipments $pilotEquipments)
    {
        $this->equipment=$pilotEquipments;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $image = $this->equipment->image;
        $directory=pathinfo($image, PATHINFO_DIRNAME);
        $base_image_name=pathinfo($image, PATHINFO_FILENAME);
        $extension=pathinfo($image, PATHINFO_EXTENSION);

        //let's build the size of array
        $sizes_arr=["400,400"];
     
        //original image
        $image_name =$base_image_name.'.' . $extension;

        $destinationPath=public_path('/images/'.$this->equipment->pilot_profile_id.'/equipment');

      
        $imageRealPath=pathinfo($image, PATHINFO_DIRNAME).'/'.pathinfo($image, PATHINFO_FILENAME).'.'.pathinfo($image, PATHINFO_EXTENSION);

        $resize_image = Image::make($imageRealPath);

        foreach ($sizes_arr as $size_value) {
            $array = explode(',', $size_value);
            $new_width = $array[0];
            $new_height = $array[1];
            $new_image_name =$base_image_name.'-'.$new_width.'x'.$new_height.'.'.$extension;
            $resize_image->resize($new_width, $new_height, function ($constraint) {
                //$constraint->aspectRatio();
            })->save($destinationPath . '/' . $new_image_name);
        }

        ImageOptimizer::optimize(public_path($imageRealPath));
        ImageOptimizer::optimize(public_path($destinationPath . '/' . $new_image_name));
    }
}
