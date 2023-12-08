<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class JobLocation extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'job_locations';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city',
        'state',
        'country',
        'address',
        'pilot_job_id',
        'latitude',
        'longitude'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pilot_job_id' => 'integer',
    ];


    public function pilotJob()
    {
        return $this->belongsTo(PilotJob::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


    public function getImpoadedLocation()
    {
        return $this->city.','.$this->state.','.$this->country.','.$this->address;
    }
}
