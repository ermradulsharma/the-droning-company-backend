<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class TempPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'id'    => 59,
                'title' => 'banner_create',
            ],
            [
                'id'    => 60,
                'title' => 'banner_edit',
            ],
            [
                'id'    => 61,
                'title' => 'banner_show',
            ],
            [
                'id'    => 62,
                'title' => 'banner_delete',
            ],
            [
                'id'    => 63,
                'title' => 'banner_access',
            ],
        ];

        Permission::insert($permissions);
    }
}