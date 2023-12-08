<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CompanyProfile extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = ['user_id', 'title', 'slug', 'short_description', 'description', 'address', 'suite', 'city', 'state', 'country', 'zip_code', 'contact_person', 'website', 'email', 'phone', 'working_hours', 'logo', 'featured_image', 'is_featured', 'home_featured', 'status', 'metatitle', 'metakeyword', 'metadescription', 'service_1', 'service_2', 'service_3', 'profile_img_1', 'profile_img_2', 'profile_img_3', 'profile_img_4', 'profile_img_5', 'profile_img_6', 'facebook', 'twitter', 'linkedin', 'youtube', 'instagram', 'pic_desc_1', 'pic_desc_2', 'pic_desc_3', 'pic_desc_4', 'pic_desc_5', 'pic_desc_6', 'press_release_1', 'press_release_2', 'press_release_3', 'dc_articles'];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function services()
    {
        return $this->hasMany(\App\Models\CompanyService::class, 'company_id');
    }

    public function gallery()
    {
        return $this->hasMany(\App\Models\CompanyGallery::class, 'company_id');
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

    public function company_services()
    {
        return $this->belongsToMany(App\Models\CompanyService::class)->withPivot('company_services');
    }

    public function stringToBoolean($value)
    {
        return $value =='Yes' ? true:false;
    }

    public static function hasCompanyLogoExist($user_id)
    {
        $profile=self::where('user_id', $user_id)->latest('id')->first();
        if (!$profile) {
            return asset('pilotNoImage.png');
        }
        if (!$profile->logo) {
            return asset('pilotNoImage.png');
        }
        return asset($profile->logo);
    }

    public static function hasFeaturedImageExist($user_id)
    {
        $profile=self::where('user_id', $user_id)->latest('id')->first();
        if (!$profile) {
            return asset('pilotNoImage.png');
        }
        if (!$profile->image) {
            return asset('pilotNoImage.png');
        }
        return asset($profile->featured_image);
    }

    public function setSlugAttribute($value)
    {
        $count = static::whereSlug($slug = Str::slug($value))->count();
        if (static::whereSlug($slug = Str::slug($value))->exists() && $count > 1) {
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

    public function country_detail()
    {
        return $this->hasOne('App\Models\Country', 'id', 'country');
    }

    public function state_detail()
    {
        return $this->hasOne('App\Models\State', 'id', 'state');
    }

    public function scopeSubscribed($query)
    {
        return $query->whereHas('user', function ($r) {
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
