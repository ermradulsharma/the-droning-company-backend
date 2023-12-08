<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('address', 191)->nullable();
            $table->string('suite', 191)->nullable();
            $table->string('city', 55)->nullable();
            $table->string('state', 55)->nullable();
            $table->string('country', 55)->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->string('contact_person', 191)->nullable();
            $table->string('website', 191)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('phone', 191)->nullable();
            $table->text('working_hours')->nullable();
            $table->string('service_1', 191)->nullable();
            $table->string('service_2', 191)->nullable();
            $table->string('service_3', 191)->nullable();
            $table->text('logo')->nullable();
            $table->text('featured_image')->nullable();
            $table->string('profile_img_1', 191)->nullable();
            $table->string('profile_img_2', 191)->nullable();
            $table->string('profile_img_3', 191)->nullable();
            $table->string('profile_img_4', 191)->nullable();
            $table->string('profile_img_5', 191)->nullable();
            $table->string('profile_img_6', 191)->nullable();
            $table->text('pic_desc_1')->nullable();
            $table->text('pic_desc_2')->nullable();
            $table->text('pic_desc_3')->nullable();
            $table->text('pic_desc_4')->nullable();
            $table->text('pic_desc_5')->nullable();
            $table->text('pic_desc_6')->nullable();
            $table->text('press_release_1')->nullable();
            $table->text('press_release_2')->nullable();
            $table->text('press_release_3')->nullable();
            $table->text('dc_articles')->nullable();
            $table->string('facebook', 191)->nullable();
            $table->string('twitter', 191)->nullable();
            $table->string('linkedin', 191)->nullable();
            $table->string('youtube', 191)->nullable();
            $table->string('instagram', 191)->nullable();
            $table->enum('is_featured', ['No', 'Yes'])->default('No');
            $table->enum('home_featured', ['No', 'Yes'])->default('No');
            $table->enum('status', ['0', '1'])->default('1');
            $table->string('metatitle', 255)->nullable();
            $table->string('metakeyword', 255)->nullable();
            $table->string('metadescription', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_profiles');
    }

   
}
