<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Plan;
use App\Subscription;
use App\subscriptionPaymentHistories;

class SubscriptionPaymentHistoriesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubscriptionPaymentHistories::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subscription_id' => Subscription::factory(),
            'plan_id' => Plan::factory(),
            'amount' => $this->faker->randomFloat(2, 0, 99999999.99),
            'payment_date' => $this->faker->date(),
            'payment_id' => $this->faker->word,
            'payment_refrence' => $this->faker->word,
            'status' => $this->faker->boolean,
        ];
    }
}
