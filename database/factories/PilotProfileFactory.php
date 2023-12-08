<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PilotProfile;

class PilotProfileFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = PilotProfile::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::inRandomOrder()->first(),
            'title' => $this->faker->word,
            'slug' => $this->faker->slug,
            'image' =>'pilotseed.png',
            'description' => $this->faker->text,
            'short_description' => $this->faker->word,
            'is_certified' => $this->faker->randomElement(['Yes', 'No']),
            'travel_option' => $this->faker->randomElement(['No', 'Yes']),
            'is_featured' => $this->faker->randomElement(['Yes', 'No']),
            'metatitle' => $this->faker->word,
            'metakeyword' => $this->faker->word,
            'metadescription' => $this->faker->word,
            'status' => $this->faker->randomElement(['0', '1']),
        ];
    }
}
