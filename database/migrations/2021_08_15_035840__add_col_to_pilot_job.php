<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToPilotJob extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pilot_jobs', function (Blueprint $table) {
            $table->string('enquiry_type', 10)->default(1);
        });
    }
}
