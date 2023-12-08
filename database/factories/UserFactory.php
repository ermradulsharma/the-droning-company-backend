<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = User::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'slug' => $this->faker->slug,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' =>now(),
            'password' => bcrypt($this->faker->password),
            'remember_token' => Str::random(10),
          //  'country_id' => $this->faker->randomNumber(),
            'registration_source' => $this->faker->randomElement(['Admin', 'Frontend']),
            'active_status' => $this->faker->randomElement(['0', '1']),
            'yes_send_email' => $this->faker->randomElement(['0', '1']),
            'yes_i_agree' => $this->faker->randomElement(['0', '1']),
            'hear_about_us' =>User::HEAR_ABOUT_US[mt_rand(1, 5)]
        ];
    }
}
