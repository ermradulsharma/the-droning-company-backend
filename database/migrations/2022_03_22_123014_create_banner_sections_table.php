<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banner_page_list_id')->nullable();
            $table->string('section_name')->nullable();
            $table->string('section_name_slug')->nullable();
            $table->timestamps();
        });
    }

   
}
