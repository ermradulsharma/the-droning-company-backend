<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerPageListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_page_lists', function (Blueprint $table) {
            $table->id();
            $table->string('page_name')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }

   
}
