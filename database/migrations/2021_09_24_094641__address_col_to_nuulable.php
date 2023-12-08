<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddressColToNuulable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pilot_address', function (Blueprint $table) {
            $table->string('address_line1')->change()->nullable();
            $table->string('address_line2')->change()->nullable();
            $table->string('city')->change()->nullable();
        });
    }
}
