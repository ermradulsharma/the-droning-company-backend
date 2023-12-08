<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertValueInSetting11 extends Migration
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
                'key_1'=>"FAA UPDATES",
                'value'=>'Stay up to date with the most recent FAA news.',
            'key_link'=>'https://laravel.com/docs/8.x/helpers#method-str-ordered-uuid'
            ],
    ];
        \DB::table('settings')->insert($skills);
    }
}
