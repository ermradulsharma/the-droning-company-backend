<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Coupon;

class CouponFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = Coupon::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'coupon_name' => $this->faker->word,
            'coupon_type' => $this->faker->word,
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'status' => $this->faker->boolean,
            'discount' => $this->faker->word,
        ];
    }
}
