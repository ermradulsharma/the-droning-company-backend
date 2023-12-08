<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFielsToJobTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pilot_jobs', function (Blueprint $table) {
            $table->boolean('contact_via_phone_number')->default(false);
            $table->boolean('contact_via_email')->default(false);
            $table->string('company_name')->nullable();
        });
    }
}
