<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilotJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilot_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_title', 100);
            $table->unsignedBigInteger('skill_category_id')->nullable();
            $table->longText('job_description')->nullable();
            $table->string('file_attachment')->nullable();
            $table->string('job_budget')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        \DB::statement("ALTER TABLE pilot_jobs AUTO_INCREMENT =1000;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pilot_jobs');
    }
}
