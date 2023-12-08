<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class JobSkillPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilot_job_skill', function (Blueprint $table) {
            $table->unsignedBigInteger('pilot_job_id');
            $table->foreign('pilot_job_id', 'pilot_job_id_fk_4124548')->references('id')->on('pilot_jobs')->onDelete('cascade');
            $table->unsignedBigInteger('skill_id');
            $table->foreign('skill_id', 'bskill_id_fk_4124548')->references('id')->on('skills')->onDelete('cascade');
        });
    }
}
