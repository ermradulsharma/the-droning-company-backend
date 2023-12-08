<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwardCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('award_categories', function (Blueprint $table) {
            $table->id();
			$table->string('title', 255);
			$table->string('slug', 255);
            $table->timestamps();
        });
    }
	
	public function down()
    {
        Schema::dropIfExists('award_categories');
    }

   
}
