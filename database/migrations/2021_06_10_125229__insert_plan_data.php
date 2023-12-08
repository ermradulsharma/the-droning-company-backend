<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertPlanData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $plans = array(
           array('plan_name' =>'basic plan',
                    'plan_amount'=>49,
                    'short_description'=>"short_description",
                    'description'=>"description",
                    'status'=>true,
                    'created_at'=>now(),
                    'updated_at'=>now()
                ),
        );
        DB::table('plans')->insert($plans);
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
