<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class Password extends Model
{
   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $table = 'password_resets';

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'email',
        'token',
        'created_at'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
