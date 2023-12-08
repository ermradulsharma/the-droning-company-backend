<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'blog_categories';

    const STATUS_SELECT = [
        '1' => 'Active',
        '0' => 'Inactive',
    ];

    protected $hidden=['pivot'];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'status',
        'slug',
        'meta_keyword',
        'meta_title',
        'meta_description',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_promoted'
    ];

    public function getTitleAttribute($value)
    {
        return Str::ucfirst($value);
    }

    public function scopePromoted($query)
    {
        return $query->where('is_promoted', true);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function blog_post()
    {
        return $this->belongsToMany(Blog::class);
    }
}
