<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\coupons;

class CouponsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Coupons::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'coupon_name' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'coupon_type' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'status' => $this->faker->boolean,
        ];
    }
}
