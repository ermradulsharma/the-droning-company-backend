<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'banner_page_list_id',
        'section_name',
        'section_name_slug'
    ];


    public function bannerPageList()
    {
        return $this->belongsTo(BannerPageList::class);
    }

    public function banner()
    {
        return $this->hasMany(Banner::class);
    }
}
