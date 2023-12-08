<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BlogCategory;

class BlogCategoryFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = BlogCategory::class;
    //\App\Models\BlogCategory::factory()->count(50)->create();
    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->word(),
            'status' => $this->faker->boolean(),
            'slug' => $this->faker->slug(),
            'meta_keyword' => $this->faker->word(),
            'meta_title' => $this->faker->word(),
            'meta_description' => $this->faker->text(),
        ];
    }
}
