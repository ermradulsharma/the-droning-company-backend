<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColuToSubscriptionPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_payment_histories', function (Blueprint $table) {
            $table->string('coupon_code')->nullable();
            $table->decimal('plan_amount', 10, 2)->nullable();
            $table->decimal('coupon_discount_amount', 10, 2)->nullable();
            $table->decimal('final_pay', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_payment_histories', function (Blueprint $table) {
            //
        });
    }
}
