<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;
use Carbon\Carbon;

class PilotJob extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'pilot_jobs';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    const STATUS=[
        '1'=>'Pending Approval',
        '2'=>'Active',
        '3'=>'Rejected',
        '4'=>'Pilot Hired',
        '5'=>'Completed',
        '6'=>'Cancelled',
        '7'=>'Archived',
        '8'=>'Under Review'

    ];

    const STATUS_SELECT=[
        '1'=>'Pending Approval',
        '2'=>'Active',
        '3'=>'Rejected',

    ];

    const STATUS_AS_TEXT=[
        'pending-approval'=>1,
        'active'=>2,
        'rejected'=>3,
        'pilot-hired'=>4,
        'completed'=>5,
        'cancelled'=>6,
        'archived'=>7,
        'under-review'=>8

    ];
    const ENQUIRY_TYPE=[
        '1'=>'Contact',
        '2'=>'Bid',
    ];

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_title',
        'skill_category_id',
        'job_description',
        'file_attachment',
        'job_budget',
        'user_id',
        'role_id',
        'status',
        'enquiry_type',
        'contact_via_phone_number',
        'contact_via_email',
        'company_name',
        'rejection_reason',
        'slug'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'skill_category_id' => 'integer',
        'user_id' => 'integer',
        'role_id' => 'integer',
        'contact_via_phone_number'=>'bool',
        'contact_via_email'=>'bool',
        'job_budget'=>'integer'
    ];


    public function skillCategory()
    {
        return $this->belongsTo(Skill::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function role()
    {
        return $this->belongsTo(Role::class)->withDefault();
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


    public function getFileAttachmentAttribute($value)
    {
        if (!$value) {
            return '';
        }

        return asset($value);
    }

    public function getStatusAttribute($value)
    {
        if (!$value) {
            return '';
        }

        return self::STATUS[$value] ?? '';
    }

    public function getEnquiryTypeAttribute($value)
    {
        if (!$value) {
            return '';
        }

        return self::ENQUIRY_TYPE[$value];
    }

    public function location()
    {
        return $this->hasMany(JobLocation::class);
    }

    public function scopeLast30days($query)
    {
        return $query->where('created_at', '>', Carbon::now()->subDays(30));
    }

    public function scopeApprovedJob($query)
    {
        return $query->where('status', 2);
    }

    public function job_categoires()
    {
        return $this->belongsToMany(Skill::class);
    }


    public function contactPreference()
    {
        if ($this->contact_via_email && $this->contact_via_phone_number) {
            return 'Phone Number & Email';
        }

        if ($this->contact_via_phone_number) {
            return 'Phone Number';
        }

        if ($this->contact_via_email) {
            return 'Email';
        }
    }

    public function getCreatedAtAttribute($value)
    {
        if (!$value) {
            return '';
        }

        return Carbon::parse($value)->format('m-d-Y');
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
}
