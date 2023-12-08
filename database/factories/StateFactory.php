<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\State;

class StateFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = State::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'country_id' => $this->faker->randomNumber(),
            'name' => $this->faker->name,
            'code' => $this->faker->word,
            'status' => $this->faker->randomElement(['0', '1']),
            'deleted_at' => $this->faker->dateTime(),
        ];
    }
}
