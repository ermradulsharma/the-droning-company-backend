<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\pilotJob;

class PilotJobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PilotJob::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'job_title' => $this->faker->realText($maxNbChars =50),
            'skill_category_id' =>\App\Models\Skill::inRandomOrder()->first(),
            'job_description' => $this->faker->text,
            'file_attachment' =>'images/jobPost/XBCumbflFzgSAKj64iFTfkKFarsGjpJ2Z66QvHrt.png',
            'job_budget' =>mt_rand(100, 1000),
            'user_id' => \App\Models\User::whereHas('roles', function ($q) {
                $q->where('id', 2);
            })->inRandomOrder()->first(),
            'role_id' =>2,
            'status' => mt_rand(1, 8),
            'enquiry_type'=>mt_rand(1, 2),
        ];
    }
}
