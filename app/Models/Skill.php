<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;
use App\Models\PilotProfile;
use Illuminate\Support\Str;

class Skill extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'skills';

    protected $hidden=['pivot'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'skill_name',
        'created_at',
        'updated_at',
        'deleted_at',
        'status'
    ];


    public function getSkillNameAttribute($value)
    {
        return Str::ucfirst($value);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }
}
