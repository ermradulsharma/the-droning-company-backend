<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            //
            $table->renameColumn('name', 'first_name');
            $table->string('last_name')->after('name');
            $table->integer('country_id')->nullable()->after('remember_token');
            $table->enum('registration_source', array('Normal', 'Social'))->after('country_id');
            $table->enum('active_status', array('0', '1'))->default('1')->after('registration_source');
            $table->enum('yes_send_email', array('0', '1'))->nullable()->after('active_status');
            $table->enum('yes_i_agree', array('0', '1'))->nullable()->after('yes_send_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
