<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PilotEquipments;

class PilotEquipmentsFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = PilotEquipments::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word,
            'image' =>'equipseed.png',
            'manufacturer' => $this->faker->word,
            'status' => $this->faker->randomElement(['0', '1']),
        ];
    }
}
