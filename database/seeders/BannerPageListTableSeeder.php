<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BannerPageList;

class BannerPageListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banner_page_list = [
            [
                'page_name' => 'Home Page',
                'slug' => 'home-page',
            ],
            [
                'page_name' => 'Blog List',
                'slug' => 'blog-list',
            ],
            [
                'page_name' => 'Blog Details',
                'slug' => 'blog-details',
            ],
            [
                'page_name' => 'Job List',
                'slug' => 'job-list',
            ],
            [
                'page_name' => 'About Us',
                'slug' => 'about-us',
            ],
            [
                'page_name' => 'Gear Reviews',
                'slug' => 'gear-reviews',
            ],
            [
                'page_name' => 'Contact Us',
                'slug' => 'contact-us',
            ],
            [
                'page_name' => 'FAQ',
                'slug' => 'faq',
            ],
            [
                'page_name' => 'Pilot Dashboard',
                'slug' => 'pilot-dashboard',
            ],
            [
                'page_name' => 'Pilot Build Profile',
                'slug' => 'pilot-build-profile',
            ],
            [
                'page_name' => 'Pilot My Subscriptions',
                'slug' => 'pilot-my-subscriptions',
            ],
            [
                'page_name' => 'Pilot Service Locations',
                'slug' => 'pilot-service-locations',
            ],
            [
                'page_name' => 'Pilot Photo Gallery',
                'slug' => 'pilot-photo-gallery',
            ],
            [
                'page_name' => 'Pilot Video Gallery',
                'slug' => 'pilot-video-gallery',
            ],
            [
                'page_name' => 'Pilot My Equipment',
                'slug' => 'pilot-my-equipment',
            ],
            [
                'page_name' => 'Pilot Edit Profile',
                'slug' => 'pilot-edit-profile',
            ],
            [
                'page_name' => 'Pilot Change Password',
                'slug' => 'pilot-change-password',
            ],
        ];

        BannerPageList::insert($banner_page_list);
    }
}
