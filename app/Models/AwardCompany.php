<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardCompany extends Model
{
    use HasFactory;
	
	protected $fillable = [
        'title',
        'slug',
		'url'
    ];
}
