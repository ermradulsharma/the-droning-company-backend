<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToBlogsTable extends Migration
{
    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->unsignedBigInteger('blog_category_id')->nullable();
            $table->foreign('blog_category_id', 'blog_category_fk_3609475')->references('id')->on('blog_categories');
        });
    }
}
