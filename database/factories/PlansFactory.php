<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\plans;

class PlansFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Plans::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'plan_name' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'plan_amount' => $this->faker->randomFloat(2, 0, 99999999.99),
            'short_description' => $this->faker->text,
            'description' => $this->faker->text,
            'status' => $this->faker->boolean,
        ];
    }
}
