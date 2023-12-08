<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banner_section_id')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('image_resolution')->nullable();
            $table->text('link')->nullable();
            $table->timestamps();
        });
    }

   
}
