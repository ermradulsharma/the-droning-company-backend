<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public $table = 'events';

    const STATUS_SELECT = [
        '0' => 'Draft',
        '2' => 'Just Paid',
        '1' => 'Approved',
        '3' => 'Declined',
    ];

    const EVENT_TYPES = ["Tradeshow", "Virtual Event", "Product Launch", "Conference", "Grand Opening", "Charity Event", "Educational Event", "Networking Event", "Workshop", "Other"];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'event_type',
        'other_event_type',
        'description',
        'image', 'gallery_img_1', 'gallery_img_2', 'gallery_img_3', 'gallery_img_4', 'gallery_img_5', 'gallery_img_6',
        'event_video',
        'phone_number',
        'contact_email',
        'event_link',
        'event_start',
        'event_end',
        'cost',
        'street_add',
        'suite',
        'city',
        'state',
        'payment_info',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'is_featured',
        'status'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function scopeJustpaid($query)
    {
        return $query->where('status', '2');
    }

    public function scopeHomeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getImageThumbAttribute($value)
    {
        $ext = pathinfo($this->attributes['image'], PATHINFO_EXTENSION);
        $baseName=basename($this->attributes['image'], ".".$ext);
        $imgsize="-160x160.";
        $base_path = str_replace($baseName.".".$ext,"", $this->attributes['image'])."resize/".$baseName;
        $imaeLink=$base_path.$imgsize.$ext;
        if (file_exists(public_path($imaeLink))) {
            return asset($imaeLink);
        }
    }

    public function getImageMediumAttribute($value)
    {
        $ext = pathinfo($this->attributes['image'], PATHINFO_EXTENSION);
        $baseName=basename($this->attributes['image'], ".".$ext);
        $imgsize="-520x390.";
        $base_path = str_replace($baseName.".".$ext,"", $this->attributes['image'])."resize/".$baseName;
        $imaeLink=$base_path.$imgsize.$ext;
        if (file_exists(public_path($imaeLink))) {
            return asset($imaeLink);
        }
    }

    public function getImageLargeAttribute($value)
    {
        $ext = pathinfo($this->attributes['image'], PATHINFO_EXTENSION);
        $baseName=basename($this->attributes['image'], ".".$ext);
        $imgsize="-915x686.";
        $base_path = str_replace($baseName.".".$ext,"", $this->attributes['image'])."resize/".$baseName;
        $imaeLink=$base_path.$imgsize.$ext;
        if (file_exists(public_path($imaeLink))) {
            return asset($imaeLink);
        }
    }
}
