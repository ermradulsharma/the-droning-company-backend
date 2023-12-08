<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PilotAddress;

class PilotAddressFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = PilotAddress::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'pilot_profile_id' => \App\Models\PilotProfile::inRandomOrder()->first(),
            'address_line1' => $this->faker->text,
            'address_line2' => $this->faker->text,
            'city' => $this->faker->city,
            'state' =>\App\Models\State::inRandomOrder()->first(),
            'country' =>\App\Models\Country::inRandomOrder()->first(),
            'zip' => $this->faker->postcode,
            'status' => $this->faker->randomElement(['0', '1']),
        ];
    }
}
