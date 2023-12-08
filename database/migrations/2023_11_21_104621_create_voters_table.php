<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voters', function (Blueprint $table) {
            $table->id();
			$table->string('name', 255);
			$table->string('address', 255);
			$table->string('suite', 255);
			$table->string('city', 255);
			$table->string('state', 255);
			$table->string('zip_code', 255);
			$table->string('phone', 255);
			$table->string('email', 255);
			$table->string('instagram', 255);
			$table->string('facebook', 255);
			$table->string('youtube', 255);
			$table->string('webpage', 255);
            $table->timestamps();
        });
    }
	
	public function down()
    {
        Schema::dropIfExists('voters');
    }

   
}
