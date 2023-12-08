<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class PhotoGallery extends Model
{
    use SoftDeletes, HasFactory;

    public const STATUS_SELECT = [
        '1' => 'Active',
        '0' => 'Inactive',
    ];
    
    public $table = 'photo_gallery';

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
        'image_text',
        'image_link'
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

       
        $cropped_image_name=pathinfo($value, PATHINFO_DIRNAME).'/'.pathinfo($value, PATHINFO_FILENAME).'-400x400.'.pathinfo($value, PATHINFO_EXTENSION);
     
        if (file_exists(public_path($cropped_image_name))) {
            return asset($cropped_image_name);
        }

        return asset($value);
    }
}
