<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;
use Illuminate\Support\Str;

class Setting extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'settings';


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'created_at',
        'updated_at',
        'deleted_at',
        'uuid',
        'key_1',
        'key_2',
        'key_3',
        'value',
        'key_link',
        'block_image'
        
    ];


    public function getRouteKeyName()
    {
        return 'uuid';
    }

    

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getBlockImageAttribute($value)
    {
        if (!$value) {
            return '';
        }

        return asset($value);
    }
}
