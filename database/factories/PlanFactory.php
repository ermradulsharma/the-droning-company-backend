<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Plan;

class PlanFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = Plan::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'plan_name' => $this->faker->word,
            'plan_amount' => $this->faker->randomFloat(),
            'short_description' => $this->faker->text,
            'description' => $this->faker->text,
            'status' => $this->faker->boolean,
        ];
    }
}
