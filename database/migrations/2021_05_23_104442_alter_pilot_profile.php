<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPilotProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('pilot_profile', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->string('short_description')->nullable()->after('description');
            $table->string('metatitle')->nullable()->after('is_featured');
            $table->string('metakeyword')->nullable()->after('metatitle');
            $table->string('metadescription')->nullable()->after('metakeyword');
        });
        \DB::statement("ALTER TABLE `pilot_profile` CHANGE `is_certified` `is_certified` ENUM('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ;");
        \DB::statement("ALTER TABLE `pilot_profile` CHANGE `is_featured` `is_featured` ENUM('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ;");
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
