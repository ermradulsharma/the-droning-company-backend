<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->unique();
            $table->string('status')->nullable();
            $table->string('slug')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
