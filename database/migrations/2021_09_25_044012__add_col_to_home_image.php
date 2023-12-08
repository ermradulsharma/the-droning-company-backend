<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToHomeImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photo_gallery', function (Blueprint $table) {
            $table->string('image_text')->nullable();
            $table->string('image_link')->nullable();
        });
    }
}
