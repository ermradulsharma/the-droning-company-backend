<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableAddSlug extends Migration
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
            $table->string('slug')->after('last_name')->nullable();
        });
        \DB::statement("ALTER TABLE `users` CHANGE `registration_source` `registration_source` ENUM('Admin','Frontend') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL ;");

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
