<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;
use App\Models\PilotProfile;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class PilotAddress extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'pilot_address';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'pilot_profile_id',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
        'zip',
        'created_at',
        'updated_at',
        'deleted_at',
        'latitude',
        'longitude'
    ];


    public function getStateAttribute($value)
    {
        return Str::ucfirst($value);
    }

    public function getCityAttribute($value)
    {
        return Str::ucfirst($value);
    }


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function pilot_profile()
    {
        return $this->belongsTo(PilotProfile::class, 'pilot_profile_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state', 'id');
    }

    public function countryRelation()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }

    public function stateRelation()
    {
        return $this->belongsTo(State::class, 'state', 'id');
    }


    public function userOne()
    {
        return $this->hasOne('App\Models\PilotProfile', 'id', 'pilot_profile_id');
    }


    public function getLatitudeAndLongitude()
    {

       // API Key - Direction APIAIzaSyA6VS1D0sRhGLa3J7h3yNUlJVdp_dNfhyI
        $getStateName=$this->stateRelation->name ?? '';
        $address=$getStateName.','.$this->city;
        $apiKey  ='AIzaSyCLfVtYfTOVAcjsMpVDVltEu7SJIP007Uw';
        $address = urlencode($address);
        $url     = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=".$apiKey;
        $resp    = json_decode(file_get_contents($url), true);

        // Latitude and Longitude (PHP 7 syntax)
        $lat    = $resp['results'][0]['geometry']['location']['lat'] ?? '';
        $long   = $resp['results'][0]['geometry']['location']['lng'] ?? '';

        return  [$lat,$long];
    }


    public function scopeNearestTo(Builder $builder, $lat, $lng)
    {
        return $builder
            ->select()
            ->orderByRaw(
                'POW(69.1 * (latitude - ?), 2) + POW(69.1 * (? - longitude) * COS(latitude / 57.3), 2) < 50 ',
                [$lat, $lng]
            );
    }
}
