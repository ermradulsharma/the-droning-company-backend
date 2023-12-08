<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BlogBlogCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_blog_category', function (Blueprint $table) {
            $table->unsignedBigInteger('blog_id');
            $table->foreign('blog_id', 'blog_id_fk_4124548')->references('id')->on('blogs')->onDelete('cascade');
            $table->unsignedBigInteger('blog_category_id');
            $table->foreign('blog_category_id', 'blog_category_id_fk_4124548')->references('id')->on('blog_categories')->onDelete('cascade');
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
