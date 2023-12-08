<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertValueInSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $skills =[
            ['uuid'=>(string) Str::orderedUuid(),
                'key_1'=>"KEEPER'S CORNER",
                'value'=>'Stay up to date on all things droning with our resident expert and Chief Columnist, Michael Keeper.',
            'key_link'=>'https://laravel.com/docs/8.x/helpers#method-str-ordered-uuid'
            ],
    ];
        \DB::table('settings')->insert($skills);
    }
}
