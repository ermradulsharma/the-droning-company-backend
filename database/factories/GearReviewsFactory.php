<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\GearReviews;

class GearReviewsFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = GearReviews::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'video' => $this->faker->text,
            'video_key' => $this->faker->word,
            'status' => $this->faker->randomElement(['0', '1']),
        ];
    }
}
