<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->string('event_type', 255)->nullable();
            $table->string('other_event_type', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('event_video', 255)->nullable();
            $table->string('event_link', 255)->nullable();
            $table->string('event_start', 255)->nullable();
            $table->string('event_end', 255)->nullable();
            $table->integer('cost')->nullable();
            $table->string('phone_number', 255)->nullable();
            $table->string('contact_email', 255)->nullable();
            $table->string('street_add', 255)->nullable();
            $table->string('suite', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_keyword', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->string('image')->nullable();
            $table->string('gallery_img_1')->nullable();
            $table->string('gallery_img_2')->nullable();
            $table->string('gallery_img_3')->nullable();
            $table->string('gallery_img_4')->nullable();
            $table->string('gallery_img_5')->nullable();
            $table->string('gallery_img_6')->nullable();
            $table->text('payment_info')->nullable();
            $table->enum('is_featured', ['0', '1'])->default('0');
            $table->enum('status', ['0', '1', '2'])->default('0');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
}