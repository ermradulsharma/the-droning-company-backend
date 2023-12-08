<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class ImageCdn extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'image_cdn';

   
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'image',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


    public function getImageAttribute($value)
    {
        if (!$value) {
            return '';
        }


        return asset($value);
    }

    public function imageToOptimized()
    {
        if (!$this->image) {
            return '';
        }


        return $this->image;
    }
}
