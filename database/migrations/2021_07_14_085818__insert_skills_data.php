<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertSkillsData extends Migration
{
    public function up()
    {
        $skills = array(
           array('skill_name' =>'drone',
                    'status'=>1,
                    'created_at'=>now(),
                    'updated_at'=>now()
                ),
           array('skill_name' =>'Photography',
                    'status'=>1,
                    'created_at'=>now(),
                    'updated_at'=>now()
                ),
            array('skill_name' =>'Videography',
                    'status'=>1,
                    'created_at'=>now(),
                    'updated_at'=>now()
                ),
              array('skill_name' =>'Video Editing',
                    'status'=>1,
                    'created_at'=>now(),
                    'updated_at'=>now()
                ),
              array('skill_name' =>'Adobe PhotoShop',
                    'status'=>1,
                    'created_at'=>now(),
                    'updated_at'=>now()
                ),
        );
        DB::table('skills')->insert($skills);
    }
}
