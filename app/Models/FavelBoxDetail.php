<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavelBoxDetail extends Model
{
    use HasFactory;

    protected $appends = [
        'image_full_path',
    ];

    protected $fillable = [
        'favel_box_id',
        'title',
        'description',
        'image',
        'page_video_link'
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


    public function favelBox()
    {
        return $this->belongsTo(FavelBox::class);
    }
}
