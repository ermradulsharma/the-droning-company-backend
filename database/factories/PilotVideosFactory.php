<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PilotVideos;

class PilotVideosFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = PilotVideos::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['Youtube', 'Self Hosted', 'Vimeo']),
            'video' =>'https://www.youtube.com/watch?v=CXa0f4-dWi4',
            'video_key' =>'CXa0f4-dWi4',
            'position' => $this->faker->randomElement(['Main', 'Gallery']),
            'status' => $this->faker->randomElement(['0', '1']),
        ];
    }
}
