<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyGallery extends Model
{
    use SoftDeletes, HasFactory;

    public const STATUS_SELECT = [
        '1' => 'Active',
        '0' => 'Inactive',
    ];

    protected $fillable = ['company_id', 'image', 'status'];

    public function company_profile()
    {
        return $this->hasOne('App\Models\CompanyProfile', 'id', 'company_id');
    }
}
