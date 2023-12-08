<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwardCompanyCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('award_company_categories', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('category_id')->unsigned();
			$table->bigInteger('company_id')->unsigned();
            $table->timestamps();
        });
    }
	
	public function down()
    {
        Schema::dropIfExists('award_company_categories');
    }

   
}
