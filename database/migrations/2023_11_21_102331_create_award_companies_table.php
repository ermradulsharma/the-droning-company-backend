<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwardCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('award_companies', function (Blueprint $table) {
            $table->id();
			$table->string('title', 255);
			$table->string('slug', 255);
			$table->string('url', 255);
            $table->timestamps();
        });
    }
	
	public function down()
    {
        Schema::dropIfExists('award_companies');
    }

   
}
