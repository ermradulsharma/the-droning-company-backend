<?php

namespace App\Models;

use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use \DateTimeInterface;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    use HasFactory;
    use HasApiTokens;
    use Billable;

    public $table = 'users';

    protected $hidden = [
        'remember_token',
        'password',

    ];
  
    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const HEAR_ABOUT_US=[
        '1'=>'internet',
        '2'=>'tv',
        '3'=>'posters',
        '4'=>'poster',
        '5'=>'other'
    ];

    const HEAR_ABOUT_US_TEXT=[
        'internet'=>1,
        'tv'=>2,
        'posters'=>3,
        'poster'=>4,
        'other'=>5
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'slug',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'country_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'active_status',
        'yes_i_agree',
        'yes_send_email',
        'registration_source',
        'hear_about_us',
        'profile_photo',
        'mobile'
    ];

    public function getProfilePhotoAttribute($value)
    {
        if (!$value) {
            return asset('pilotNoImage.png');
        }

        return asset($value);
    }

    public function getFirstNameAttribute($value)
    {
        return Str::ucfirst($value);
    }

    public function getLastNameAttribute($value)
    {
        return Str::ucfirst($value);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function getIsPilotAttribute()
    {
        return $this->roles()->where('id', 3)->exists();
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

     

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    
    public function pilot_roles()
    {
        return $this->belongsTo(RoleUser::class);
    }
    
    public function getPilotRole()
    {
        return $this->pilot_roles()->where('role_id', 3)->exists();
    }

    public function scopeActive($query)
    {
        return $query->where('active_status', "1");
    }

    public function getNameAttribute()
    {
        return $this->first_name .' '.$this->last_name;
    }
}
