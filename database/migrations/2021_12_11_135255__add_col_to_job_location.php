<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToJobLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_locations', function (Blueprint $table) {
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
        });
    }
}
