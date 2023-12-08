<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('image')->nullable();
            $table->longText('description')->nullable();
            $table->string('slug')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->longText('meta_description')->nullable();
            $table->string('status')->nullable();
            $table->integer('no_of_view')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
