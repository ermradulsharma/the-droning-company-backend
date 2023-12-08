<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavelBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favel_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('box_name')->nullable();
            $table->string('slug')->nullable();
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
        });
    }

   
}
