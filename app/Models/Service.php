<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['title', 'status'];

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }
}
