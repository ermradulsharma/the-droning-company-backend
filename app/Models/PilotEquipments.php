<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;
use App\Models\User;
use Illuminate\Support\Str;

class PilotEquipments extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'pilot_equipments';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'pilot_profile_id',
        'title',
        'image',
        'manufacturer',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function getTitleAttribute($value)
    {
        return Str::ucfirst($value);
    }

    public function getManufacturerAttribute($value)
    {
        return Str::ucfirst($value);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getImageAttribute($value)
    {
        //dd($value, $value=="NULL");
        if (!$value || $value=="NULL") {
            return asset('pilotNoImage.png');
        }

       
        $cropped_image_name=pathinfo($value, PATHINFO_DIRNAME).'/'.pathinfo($value, PATHINFO_FILENAME).'-400x400.'.pathinfo($value, PATHINFO_EXTENSION);
     
        if (file_exists(public_path($cropped_image_name))) {
            return asset($cropped_image_name);
        }

        return asset($value);
    }
    
    public function userOne()
    {
        return $this->hasOne(PilotProfile::class, 'id', 'pilot_profile_id');
    }
}
