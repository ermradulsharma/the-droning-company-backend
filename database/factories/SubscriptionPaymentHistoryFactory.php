<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SubscriptionPaymentHistory;

class SubscriptionPaymentHistoryFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = SubscriptionPaymentHistory::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'subscription_id' => \App\Models\Subscription::factory(),
            'plan_id' => \App\Models\Plan::factory(),
            'payment_date' => $this->faker->date(),
            'payment_id' => $this->faker->word,
            'payment_refrence' => $this->faker->word,
            'status' => $this->faker->boolean,
            'coupon_code' => $this->faker->word,
            'plan_amount' => $this->faker->randomFloat(),
            'coupon_discount_amount' => $this->faker->randomFloat(),
            'final_pay' => $this->faker->randomFloat(),
        ];
    }
}
