<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Blog::factory()->count(50)->create();

        // Get all the roles attaching up to 3 random roles to each user
        $categories =BlogCategory::all();

        // Populate the pivot table
        \App\Models\Blog::all()->each(function ($blog) use ($categories) {
            $blog->blog_categories()->attach(
                $categories->random(rand(2, 5))->pluck('id')->toArray()
            );
        });
    }
}
