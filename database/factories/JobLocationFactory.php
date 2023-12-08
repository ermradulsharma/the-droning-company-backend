<?php

namespace Database\Factories;

use App\Models\JobLocation;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobLocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobLocation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'city' => $this->faker->city,
            'state' =>\App\Models\State::inRandomOrder()->first()->name,
            'country' =>\App\Models\Country::inRandomOrder()->first()->name,
            'address' => $this->faker->secondaryAddress,
            'pilot_job_id' => \App\Models\PilotJob::inRandomOrder()->first()->id,
        ];
    }
}
