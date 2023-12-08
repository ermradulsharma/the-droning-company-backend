<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\PilotSkills;
use App\Models\PilotAddress;
use App\Models\PilotProfile;
use App\Models\PilotGallery;
use App\Models\PilotEquipments;
use App\Models\PilotVideos;
use Illuminate\Database\Seeder;

class PilotSeeder extends Seeder
{
    public function run()
    {
        User::factory(50)->create()->each(function ($user) {
            $user->roles()->sync(3);
            PilotProfile::factory(1)->create([
                'user_id' => $user->id
            ])->each(function ($profile) {
                PilotAddress::factory(rand(1, 3))->create([
                    'pilot_profile_id'=>$profile->id
                ]);
            })->each(function ($profile) {
                PilotSkills::factory(mt_rand(1, 5))->create([
                     'pilot_profile_id' =>$profile->id,
                ]);
            })->each(function ($profile) {
                PilotEquipments::factory(mt_rand(1, 5))->create([
                     'pilot_profile_id' =>$profile->id,
                ]);
            })->each(function ($profile) {
                PilotVideos::factory(mt_rand(1, 5))->create([
                     'pilot_profile_id' =>$profile->id,
                ]);
            })->each(function ($profile) {
                PilotGallery::factory(mt_rand(1, 5))->create([
                     'pilot_profile_id' =>$profile->id,
                ]);
            });
        });
    }
}
