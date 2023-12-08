<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\User;
use App\userAddress;

class UserAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address_1' => $this->faker->word,
            'address_2' => $this->faker->word,
            'country' => $this->faker->country,
            'state' => $this->faker->word,
            'city' => $this->faker->city,
            'zip_code' => $this->faker->word,
            'user_id' => User::factory(),
            'status' => $this->faker->word,
        ];
    }
}
