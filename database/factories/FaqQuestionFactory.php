<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FaqQuestion;

class FaqQuestionFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = FaqQuestion::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'question' => $this->faker->text,
            'answer' => $this->faker->text,
            'category_id' => \App\Models\FaqCategory::factory(),
        ];
    }
}
