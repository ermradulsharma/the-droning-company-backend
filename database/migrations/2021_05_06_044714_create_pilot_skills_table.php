<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilotSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilot_skills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pilot_profile_id')->unsigned();
            $table->integer('skill_id');
            $table->enum('status', ['0', '1'])->default('1');;
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::table('pilot_skills', function($table) {
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
        Schema::dropIfExists('pilot_skills');
    }
}
