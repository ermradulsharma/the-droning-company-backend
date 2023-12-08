<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class OptionTableInsert extends Migration
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
                'key_1'=>'Racing Videos',
                'value'=>'Take a seat as co-pilot and experience the world of drone racing.',
            'key_link'=>'https://laravel.com/docs/8.x/helpers#method-str-ordered-uuid'
            ],
            ['uuid'=>(string) Str::orderedUuid(),
                'key_1'=>'Free Tools and Education',
                'value'=>"Learn more about droning, becoming a drone pilot, or advance your skills with The Droning Company's Free Tools and Education.",
                  'key_link'=>'https://laravel.com/docs/8.x/helpers#method-str-ordered-uuid'
            ]
    ];
        \DB::table('settings')->insert($skills);
    }
}
