<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavelBoxDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favel_box_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('favel_box_id')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('page_video_link')->nullable();
            $table->timestamps();
        });
    }

   
}
