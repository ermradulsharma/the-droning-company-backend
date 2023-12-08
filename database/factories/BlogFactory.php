<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Blog;

class BlogFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = Blog::class;
    ///\App\Models\Blog::factory()->count(10)->create();
    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'image' =>"/images/blog/1623221514.jpg",
            'description' => $this->faker->paragraph($nbSentences =10, $variableNbSentences = true),
            'slug' => $this->faker->slug(),
            'meta_keyword' => $this->faker->word(),
            'meta_description' => $this->faker->text(),
            'status' => $this->faker->boolean(),
            'no_of_view' => $this->faker->randomNumber(),
            'excerpt' => $this->faker->paragraph($nbSentences =1, $variableNbSentences = true)
        ];
    }
}
