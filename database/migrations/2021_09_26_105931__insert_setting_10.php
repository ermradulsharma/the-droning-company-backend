<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertSetting10 extends Migration
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
                'key_1'=>"FOUNDERS COLUMN",
                'key_2'=>'STUART SMITH',
                'value'=>'A weekly update from our founder, Stuart Smith, on all things drone.',
            'key_link'=>'https://laravel.com/docs/8.x/helpers#method-str-ordered-uuid'
            ],
    ];
        \DB::table('settings')->insert($skills);
    }
}
