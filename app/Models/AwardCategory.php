<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardCategory extends Model
{
    use HasFactory;
	
	protected $fillable = [
        'title',
        'slug'
    ];
	
	public function companies()
    {
		return $this->hasMany('App\Models\AwardCompanyCategory', 'category_id', 'id')->select(['id', 'category_id', 'company_id']);
    }
}
