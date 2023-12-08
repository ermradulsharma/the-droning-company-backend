<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertNewCouponData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $coupons = array(
           array('coupon_name' =>'OFF10%',
                    'status'=>1,
                    'coupon_type'=>1,
                    'discount'=>10,
                    'start_date'=>now()->format('Y-m-d'),
                    'end_date'=>now()->addDays(60)->format('Y-m-d'),
                    'created_at'=>now(),
                     'updated_at'=>now()
                ),
           array('coupon_name' =>'FLAT$20OFF',
                    'status'=>1,
                    'coupon_type'=>2,
                    'discount'=>20,
                    'start_date'=>now()->format('Y-m-d'),
                    'end_date'=>now()->addDays(60)->format('Y-m-d'),
                    'created_at'=>now(),
                    'updated_at'=>now()
                )
           
        );
        DB::table('coupons')->insert($coupons);
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
