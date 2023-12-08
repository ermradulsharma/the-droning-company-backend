<?php
namespace App\Models;

use \DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Blog extends Model
{
    use SoftDeletes,HasFactory;

    public $table = 'blogs';

    const STATUS_SELECT = [
        '1' => 'published',
        '0' => 'DRAFT',
    ];

   

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'image',
        'description',
        'slug',
        'meta_keyword',
        'meta_description',
        'status',
        'no_of_view',
        'created_at',
        'updated_at',
        'deleted_at',
        'excerpt',
        'is_featured'
    ];
    

    public function getTitleAttribute($value)
    {
        return Str::ucfirst($value);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function blog_categories()
    {
        return $this->belongsToMany(BlogCategory::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function scopeHomeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getImageAttribute($value)
    {
        if (!$value) {
            return '';
        }
        $path=(parse_url(@$_SERVER['REQUEST_URI'], PHP_URL_PATH));

        $reuestPath=str_replace("dron/droningproject/", "", $path);
		//dd( $reuestPath,$path);

        if (strpos($reuestPath, '/api/v1/company-profile/') === 0) {
            $ext = pathinfo($value, PATHINFO_EXTENSION);
            $baseName=basename($value, ".".$ext);
            $imgsize="-160x160.";
            $imaeLink='/images/blog/resize/'.$baseName.$imgsize.$ext;
            if (file_exists(public_path($imaeLink))) {
                return asset($imaeLink);
            }
        }
		
		if ($reuestPath=="/api/v1/recentblogpost") {
            $ext = pathinfo($value, PATHINFO_EXTENSION);
            $baseName=basename($value, ".".$ext);
            $imgsize="-160x160.";
            $imaeLink='/images/blog/resize/'.$baseName.$imgsize.$ext;

            if (file_exists(public_path($imaeLink))) {
                return asset($imaeLink);
            }
        }
		
        if ($reuestPath=="/api/v1/blogs" || $reuestPath=="/api/v1/get-blogs-by-category") {
            $ext = pathinfo($value, PATHINFO_EXTENSION);
            $baseName=basename($value, ".".$ext);
            $imgsize="-520x390.";
            $imaeLink='/images/blog/resize/'.$baseName.$imgsize.$ext;

            if (file_exists(public_path($imaeLink))) {
                return asset($imaeLink);
            }
        }
		
		if ($reuestPath=="/api/v1/home/featured-blog") {
            $ext = pathinfo($value, PATHINFO_EXTENSION);
            $baseName=basename($value, ".".$ext);
            $imgsize="-520x390.";
            $imaeLink='/images/blog/resize/'.$baseName.$imgsize.$ext;

            if (file_exists(public_path($imaeLink))) {
                return asset($imaeLink);
            }
        }
		
		if ($reuestPath=="/api/v1/home/blog") {
			//dd('ddd');
			
			
            $ext = pathinfo($value, PATHINFO_EXTENSION);
            $baseName=basename($value, ".".$ext);
            $imgsize="-520x390.";
            $imaeLink='/images/blog/resize/'.$baseName.$imgsize.$ext;
			//
			
			if($this->id==117){
				//dd($imaeLink,file_exists(public_path($imaeLink)));
			}
			
            if (file_exists(public_path($imaeLink))) {
                return asset($imaeLink);
            }
        }

        return asset($value);
    }

    


    private function fileChecked($value=null)
    {
        if (!$value) {
            return '';
        }
    
        if (!file_exists(public_path($value))) {
            return '';
        } else {
            return asset($value);
        }
    }





    

    public function getListingImageAttribute()
    {
        if (!$this->image) {
            return asset('pilotNoImage.png');
        }

        $ext = pathinfo($this->image, PATHINFO_EXTENSION);
        $baseName=basename($this->image, ".".$ext);
        $imgsize="-520x390.";
        return asset('/img/blogpost/resize/'.$baseName.$imgsize.$ext);
    }
}
