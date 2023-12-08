<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class CompanyVideo extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['company_id', 'type', 'video', 'video_key', 'position','status'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function company_profile()
    {
        return $this->hasOne('App\Models\CompanyProfile', 'id', 'company_id');
    }

    public function scopeByUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }
}
