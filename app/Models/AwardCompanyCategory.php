<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardCompanyCategory extends Model
{
    use HasFactory;
	
	protected $fillable = [
        'category_id',
        'company_id'
    ];
	
	public function company_detail()
    {
		return $this->hasOne('App\Models\AwardCompany', 'id', 'company_id')->select(['id', 'title', 'url']);
    }
}
