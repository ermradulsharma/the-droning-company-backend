<?php

namespace App\Models;

use App\Models\User;
use App\Models\Skill;
use \DateTimeInterface;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PilotProfile extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'pilot_profile';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'slug',
        'image',
        'description',
        'short_description',
        'is_certified',
        'travel_option',
        'is_featured',
        'metatitle',
        'metakeyword',
        'metadescription',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
        'home_featured',
		'home_featured_updated_at',
        'user_id',
        'is_insured',
        'license_image',
        'address',
        'zip_code',
        'instagram_name'
    ];


  
    protected $casts = ['is_insured'=>'bool'];


    public function setTravelOptionAttribute($value)
    {
        if (is_bool($value)) {
            $travel_option=$value==true ? 'Yes': 'No';
        } else {
            $travel_option=$value;
        }

        $this->attributes['travel_option'] =$travel_option;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function users()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->withDefault();
    }
    public function userOne()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function hourlyRate()
    {
        return $this->hasOne(PilotRate::class);
    }

    public function userSkill()
    {
        return $this->hasMany(PilotSkills::class, 'pilot_profile_id');
    }

    public function pilotLocations()
    {
        return $this->hasMany(PilotAddress::class, 'pilot_profile_id');
    }
    
    public function getImageAttribute($value)
    {
       /* if (!$value || $value==null) {
            return asset('pilotNoImage.png');
        }

        if (!file_exists(public_path($value))) {
            return asset('pilotNoImage.png');
        }

        return asset($value); 
		*/
		
		
		if (!$value || $value==null || $value=='NULL') {
            return asset('pilotNoImage.png');
        }
        $path=(parse_url(@$_SERVER['REQUEST_URI'], PHP_URL_PATH));

        $reuestPath=str_replace("dron/droningproject/", "", $path);
		//dd($reuestPath);
        if ($reuestPath=="/api/v1/pilot/feature") {
            $ext = pathinfo($value, PATHINFO_EXTENSION);
            $baseName=basename($value, ".".$ext);
            $imgsize="-160x160.";
            $imaeLink='/images/'.$this->id.'/profile/'.$baseName.$imgsize.$ext;
			
			//dd(file_exists(public_path($imaeLink)));
            if (file_exists(public_path($imaeLink))) {
                return asset($imaeLink);
            }
        }
		
		 if ($reuestPath=="/api/v1/home/pilot-feature") {
            $ext = pathinfo($value, PATHINFO_EXTENSION);
            $baseName=basename($value, ".".$ext);
            $imgsize="-275x275.";
            $imaeLink='/images/'.$this->id.'/profile/'.$baseName.$imgsize.$ext;
			
			//dd(file_exists(public_path($imaeLink)));
            if (file_exists(public_path($imaeLink))) {
                return asset($imaeLink);
            }
        }
		
		 if ($reuestPath=="/api/v1/category/pilot-feature") {
            $ext = pathinfo($value, PATHINFO_EXTENSION);
            $baseName=basename($value, ".".$ext);
            $imgsize="-275x275.";
            $imaeLink='/images/'.$this->id.'/profile/'.$baseName.$imgsize.$ext;
			
			//dd(file_exists(public_path($imaeLink)));
            if (file_exists(public_path($imaeLink))) {
                return asset($imaeLink);
            }
        }
		
		 if ($reuestPath=="/api/v1/pilot-profile") {
            $ext = pathinfo($value, PATHINFO_EXTENSION);
            $baseName=basename($value, ".".$ext);
            $imgsize="-275x275.";
            $imaeLink='/images/'.$this->id.'/profile/'.$baseName.$imgsize.$ext;
			
			//dd(file_exists(public_path($imaeLink)));
            if (file_exists(public_path($imaeLink))) {
                return asset($imaeLink);
            }
        }

        return asset($value);
    }

    public function getLicenseImageAttribute($value)
    {
        if (!$value) {
            return '';
        }

        return asset($value);
    }

    public function getTitleAttribute($value)
    {
        return Str::ucfirst($value);
    }

    

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 'Yes');
    }

    public function scopeByUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function scopeHomeFeatured($query)
    {
        return $query->where('home_featured', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }


    public function pilot_skills()
    {
        return $this->belongsToMany(Skill::class)->withPivot('pilot_skills');
    }


    public function stringToBoolean($value)
    {
        return $value =='Yes' ? true:false;
    }


    public static function hasPilotImageExist($user_id)
    {
        $profile=self::where('user_id', $user_id)->latest('id')->first();

        if (!$profile) {
            return asset('pilotNoImage.png');
        }

        if (!$profile->image) {
            return asset('pilotNoImage.png');
        }

        return asset($profile->image);
    }

    public function setSlugAttribute($value)
    {
        if (static::whereSlug($slug = \Str::slug($value))->exists()) {
            $slug = $this->incrementSlug($slug);
        }

        $this->attributes['slug'] = $slug;
    }

    public function incrementSlug($slug)
    {
        $original = $slug;

        $count =1;

        while (static::whereSlug($slug)->exists()) {
            $slug = "{$original}-" . $count++;
        }

        return $slug;
    }

    public function scopeSubscribed($query)
    {
        return $query->whereHas('users', function ($r) {
            $r->whereHas('subscriptions', function ($s) {
                $s->whereNested(function ($t) {
                    $t->whereIn('name', ['Monthly', 'Annual']) // name of subscription
                        ->whereNull('ends_at')
                        ->orWhere('ends_at', '>', \Carbon\Carbon::now())
                        ->orWhereNotNull('trial_ends_at')
                        ->where('trial_ends_at', '>', \Carbon\Carbon::today());
                });
            });
        });
    }
    
}
