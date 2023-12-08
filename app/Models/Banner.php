<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Banner extends Model
{
    use HasFactory , InteractsWithMedia;

    protected $appends = [
        'banner_image_full_path',
    ];

    protected $fillable = [
        'banner_section_id',
        'banner_image',
        'image_resolution',
        'link'
    ];


    public function getBannerImageFullPathAttribute($value)
    {
        if(isset($this->banner_image) && $this->banner_image != null){
            return asset($this->banner_image);
        }
        else{
            return asset('pilotNoImage.png');
        }
    }

    public function bannerSection()
    {
        return $this->belongsTo(BannerSection::class);
    }


    


}
