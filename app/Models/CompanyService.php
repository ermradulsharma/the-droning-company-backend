<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyService extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = ['company_id', 'service_id', 'status'];
    
    public function service()
    {
        return $this->hasOne('App\Models\Service', 'id', 'service_id');
    }
}





