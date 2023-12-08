<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\RoleUser;

class RoleUserFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = RoleUser::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'role_id' => $this->faker->randomNumber(),
        ];
    }
}
