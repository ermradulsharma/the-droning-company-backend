<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_videos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->enum('type',['Youtube','Self Hosted','Vimeo']);
            $table->text('video');
            $table->string('video_key', 255)->nullable();
            $table->enum('position',['Main','Gallery']);
            $table->enum('status', ['0', '1'])->default('1');;
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::table('company_videos', function($table) {
            $table->foreign('company_id')->references('id')->on('company_profiles')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_videos');
    }

   
}
