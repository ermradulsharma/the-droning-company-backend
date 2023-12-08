<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilotVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilot_videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pilot_profile_id')->unsigned();
            $table->enum('type',['Youtube','Self Hosted','Vimeo']);
            $table->text('video');
            $table->string('video_key', 255)->nullable();
            $table->enum('position',['Main','Gallery']);
            $table->enum('status', ['0', '1'])->default('1');;
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::table('pilot_videos', function($table) {
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
        Schema::dropIfExists('pilot_videos');
    }
}
