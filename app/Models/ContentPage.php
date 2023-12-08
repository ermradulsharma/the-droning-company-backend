<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use \DateTimeInterface;

class ContentPage extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'content_pages';

    protected $appends = [
        'featured_image',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'page_text',
        'excerpt',
        'created_at',
        'updated_at',
        'deleted_at',
        'image',
        'slug',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'email'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function categories()
    {
        return $this->belongsToMany(ContentCategory::class);
    }

    public function tags()
    {
        return $this->belongsToMany(ContentTag::class);
    }

    public function getFeaturedImageAttribute()
    {
        $file = $this->getMedia('featured_image')->last();

        if ($file) {
            $file->url       =str_replace("storage/", "images/media/", $file->getUrl());

            $file->thumbnail =str_replace("storage/", "images/media/", $file->getUrl('thumb'));

            $file->preview   = str_replace("storage/", "images/media/", $file->getUrl('preview')) ;
        }
        
        return $file;
    }


    public function getImageAttribute($value)
    {
        if (!$value || $value==null) {
            return asset('pilotNoImage.png');
        }

        if (!file_exists(public_path($value))) {
            return asset('pilotNoImage.png');
        }

        return asset($value);
    }
}
