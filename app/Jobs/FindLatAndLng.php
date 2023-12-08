<?php

namespace App\Jobs;

use App\Models\PilotAddress;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class FindLatAndLng implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $address;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PilotAddress $pilotAddress)
    {
        $this->address=$pilotAddress;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $newGeo=PilotAddress::find($this->address->id);

        $geoExist=PilotAddress::where('city', $this->address->city)
                        ->whereNotIn('id', [$this->address->id])
                        ->whereNotNull('latitude')
                        ->whereNotNull('longitude')
                        ->first();

        if ($geoExist) {
            $latitude=$geoExist->latitude;
            $longitude=$geoExist->longitude;
        } else {
            [$latitude,$longitude]=$newGeo->getLatitudeAndLongitude();
        }
     
        $newGeo->latitude=$latitude;
        $newGeo->longitude=$longitude;
        $newGeo->save();

        return true;
    }
}
