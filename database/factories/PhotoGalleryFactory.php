<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PhotoGallery;

class PhotoGalleryFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = PhotoGallery::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'image' => $this->faker->text,
            'status' => $this->faker->randomElement(['0', '1']),
        ];
    }
}
