<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToPilotProfileIs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('pilot_profile', function (Blueprint $table) {
            $table->boolean('is_insured')->default(false);
        });
    }

    
}
