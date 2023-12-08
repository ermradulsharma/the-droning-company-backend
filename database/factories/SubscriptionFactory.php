<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Subscription;

class SubscriptionFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = Subscription::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'plan_id' => \App\Models\Plan::factory(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'status' => $this->faker->boolean,
            'is_trial_period' => $this->faker->boolean,
        ];
    }
}
