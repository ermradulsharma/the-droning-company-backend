<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;
use Illuminate\Support\Str;

class State extends Model
{
    use  HasFactory;

    public $table = 'state';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'country_id',
        'name',
        'code',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getNameAttribute($value)
    {
        return Str::ucfirst($value);
    }
}
