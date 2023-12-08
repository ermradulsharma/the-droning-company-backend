<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CouponModified extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('coupon_code')->nullable();
            $table->string('stripe_coupon_id')->nullable();
            $table->string('stripe_promotion_id')->nullable();
        });
    }
}
