<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardVote extends Model
{
    use HasFactory;
	
	protected $fillable = [
        'category_id',
		'voter_id',
        'company_id'
    ];
}
