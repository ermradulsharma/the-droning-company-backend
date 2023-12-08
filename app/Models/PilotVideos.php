<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;
use App\Models\User;

class PilotVideos extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'pilot_videos';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'pilot_profile_id',
        'type',
        'video',
        'video_key',
        'position',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

//    public function users()
//    {
//        return $this->belongsTo('App\Models\User', 'user_id');
//    }
    public function pilot_profile()
    {
        return $this->hasOne('App\Models\PilotProfile', 'id', 'pilot_profile_id');
    }
}
