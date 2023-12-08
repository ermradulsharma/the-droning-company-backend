<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('country_id')->unsigned();
            $table->string('name', 255);
            $table->string('code', 50);          
            
            $table->enum('status', ['0', '1'])->default('1');;
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::table('state', function($table) {
            $table->foreign('country_id')->references('id')->on('country')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('state');
    }
}
