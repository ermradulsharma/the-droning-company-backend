<?php
namespace Database\Seeders;

use App\Models\PilotJob;
use App\Models\JobLocation;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    public function run()
    {
        PilotJob::factory(500)->create()->each(function ($job) {
            JobLocation::factory(rand(1, 2))->create([
                'pilot_job_id' => $job->id
            ]);
        });
    }
}
