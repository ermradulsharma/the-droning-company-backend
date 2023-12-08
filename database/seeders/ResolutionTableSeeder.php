<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resolution;

class ResolutionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id'    => 1,
                'resolution' => '250 x 250',
            ],
            [
                'id'    => 2,
                'resolution' => '200 x 200',
            ],
            [
                'id'    => 3,
                'resolution' => '468 x 60',
            ],
            [
                'id'    => 4,
                'resolution' => '728 x 90',
            ],
            [
                'id'    => 5,
                'resolution' => '300 x 250',
            ],
            [
                'id'    => 6,
                'resolution' => '336 x 280',
            ],
            [
                'id'    => 7,
                'resolution' => '120 x 600',
            ],
            [
                'id'    => 8,
                'resolution' => '160 x 600',
            ],

        ];

        Resolution::insert($data);
    }
}
