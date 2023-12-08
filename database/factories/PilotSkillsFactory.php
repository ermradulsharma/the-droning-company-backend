<?php

namespace Database\Factories;

use App\Models\Skill;
use App\Models\PilotSkills;
use App\Models\PilotProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class PilotSkillsFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = PilotSkills::class;

    //\App\Models\PilotSkills::factory()->count(10)->create();

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        return [
            'pilot_profile_id' =>PilotProfile::inRandomOrder()->first(),
            'skill_id' =>Skill::inRandomOrder()->first(),
            'status' =>'1',
            'created_at'=>now(),
            'updated_at'=>now()
        ];
    }
}
