<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PilotGallery extends Model
{
    use SoftDeletes;
    use HasFactory;

    public const STATUS_SELECT = [
        '1' => 'Active',
        '0' => 'Inactive',
    ];

    public $table = 'pilot_galleries';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'pilot_profile_id',
        'image',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function pilot_profile()
    {
        return $this->belongsTo(PilotProfile::class, 'pilot_profile_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


    public function getImageAttribute($value)
    {
        if (!$value) {
            return '';
        }

        $cropped_image_path=pathinfo($value, PATHINFO_DIRNAME).'/'.pathinfo($value, PATHINFO_FILENAME).'-400x400.'.pathinfo($value, PATHINFO_EXTENSION);
        
        // dd($cropped_image_path);
        if (file_exists(public_path($cropped_image_path))) {
            return asset($cropped_image_path);
        }

        return asset($value);
    }
}
