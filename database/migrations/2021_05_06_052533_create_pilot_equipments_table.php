<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilotEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilot_equipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pilot_profile_id')->unsigned();
            $table->string('title', 255);
            $table->text('image');
            $table->string('manufacturer', 255);
            $table->enum('status', ['0', '1'])->default('1');;
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::table('pilot_equipments', function($table) {
            $table->foreign('pilot_profile_id')->references('id')->on('pilot_profile')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pilot_equipments');
    }
}
