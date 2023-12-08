<?php

use App\Models\BlogCategory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertCategoryProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        BlogCategory::updateOrCreate(['title'=>'Product News'], [
                'status'=>'1',
                'slug'=>'product-news',
                'meta_keyword'=>'Product News','meta_title'=>'Product News',
                'meta_description'=>'Product News'
        ]);
    }
}
