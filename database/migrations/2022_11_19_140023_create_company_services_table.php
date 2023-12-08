<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_services', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->integer('service_id');
            $table->enum('status', ['0', '1'])->default('1');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('company_services', function($table) {
            $table->foreign('company_id')->references('id')->on('company_profiles')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_services');
    }

   
}
