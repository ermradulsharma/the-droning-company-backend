<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertCaategoryData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $blog_categories = array(
           array('title' =>'Category 1','status'=>1,'slug'=>'category-1'),
           array('title' =>'Category 2','status'=>1,'slug'=>'category-2'),
           array('title' =>'Category 3','status'=>1,'slug'=>'category-3'),
           array('title' =>'Category 4','status'=>1,'slug'=>'category-4'),
           array('title' =>'Category 5','status'=>1,'slug'=>'category-5'),
           array('title' =>'Category 6','status'=>1,'slug'=>'category-6'),
           array('title' =>'Category 7','status'=>1,'slug'=>'category-7'),
           array('title' =>'Category 8','status'=>1,'slug'=>'category-8'),
           array('title' =>'Category 9','status'=>1,'slug'=>'category-9'),
           array('title' =>'Category 10','status'=>1,'slug'=>'category-10'),
           
        );
        DB::table('blog_categories')->insert($blog_categories);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
