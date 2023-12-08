<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FavelBox extends Model
{
    use HasFactory, InteractsWithMedia;

    protected $appends = [
        'image_full_path',
    ];

    protected $fillable = [
        'box_name',
        'slug',
        'link',
        'image'
    ];


    public function getImageFullPathAttribute($value)
    {
        if(isset($this->image) && $this->image != null){
            return asset($this->image);
        }
        else{
            return "";
        }
    }

    public function favelBoxDetails()
    {
        return $this->hasMany(FavelBoxDetail::class);
    }
}
