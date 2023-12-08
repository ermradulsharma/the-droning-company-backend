<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PilotRate;

class PilotRateFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = PilotRate::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'pilot_profile_id' => $this->faker->randomNumber(),
            'rate' => $this->faker->randomFloat(),
            'status' => $this->faker->randomElement(['0', '1']),
        ];
    }
}
