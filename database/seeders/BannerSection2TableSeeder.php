<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BannerSection;
use App\Models\BannerPageList;
use Illuminate\Support\Facades\Log;

class BannerSection2TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banner_sections = [
            [   
                'page' => 'blog-list',
                'section_name' => 'Above Recent Post',
                'section_name_slug' => 'above-recent-post',
            ],
            [   
                'page' => 'blog-list',
                'section_name' => 'Above Feature Pilot',
                'section_name_slug' => 'above-feature-pilot',
            ],
            [   
                'page' => 'blog-list',
                'section_name' => 'Under Sidebar',
                'section_name_slug' => 'under-sidebar',
            ],
            [   
                'page' => 'blog-details',
                'section_name' => 'Above Recent Post',
                'section_name_slug' => 'above-recent-post',
            ],
            [   
                'page' => 'blog-details',
                'section_name' => 'Above Categories',
                'section_name_slug' => 'above-categories',
            ],
            [   
                'page' => 'blog-details',
                'section_name' => 'Under Sidebar',
                'section_name_slug' => 'under-sidebar',
            ],
            [   
                'page' => 'pilot-dashboard',
                'section_name' => 'Above Dashboard Title',
                'section_name_slug' => 'above-dashboard-title',
            ],
            [   
                'page' => 'pilot-dashboard',
                'section_name' => 'Bottom Of The Page',
                'section_name_slug' => 'bottom-of-the-page',
            ],
            [   
                'page' => 'pilot-dashboard',
                'section_name' => 'Under Sidebar Menu',
                'section_name_slug' => 'under-sidebar-menu',
            ],
            [   
                'page' => 'pilot-build-profile',
                'section_name' => 'Above Build Your Profile Title',
                'section_name_slug' => 'above-build-your-profile-title',
            ],
            [   
                'page' => 'pilot-build-profile',
                'section_name' => 'Bottom Of The Page',
                'section_name_slug' => 'bottom-of-the-page',
            ],
            [   
                'page' => 'pilot-my-subscriptions',
                'section_name' => 'Above My Subscriptions Title',
                'section_name_slug' => 'above-my-subscriptions-title',
            ],
            [   
                'page' => 'pilot-my-subscriptions',
                'section_name' => 'Bottom Of The Page',
                'section_name_slug' => 'bottom-of-the-page',
            ],
            [   
                'page' => 'pilot-service-locations',
                'section_name' => 'Above Service Location Title',
                'section_name_slug' => 'above-service-location-title',
            ],
            [   
                'page' => 'pilot-service-locations',
                'section_name' => 'Bottom Of The Page',
                'section_name_slug' => 'bottom-of-the-page',
            ],
            [   
                'page' => 'pilot-photo-gallery',
                'section_name' => 'Above Photo Gallery Title',
                'section_name_slug' => 'above-photo-gallery-title',
            ],
            [   
                'page' => 'pilot-photo-gallery',
                'section_name' => 'Bottom Of The Page',
                'section_name_slug' => 'bottom-of-the-page',
            ],
            [   
                'page' => 'pilot-video-gallery',
                'section_name' => 'Above Videos Gallery Title',
                'section_name_slug' => 'above-videos-gallery-title',
            ],
            [   
                'page' => 'pilot-video-gallery',
                'section_name' => 'Bottom Of The Page',
                'section_name_slug' => 'bottom-of-the-page',
            ],            
            [   
                'page' => 'pilot-my-equipment',
                'section_name' => 'Above My Equipment Title',
                'section_name_slug' => 'above-my-equipment-title',
            ],
            [   
                'page' => 'pilot-my-equipment',
                'section_name' => 'Bottom Of The Page',
                'section_name_slug' => 'bottom-of-the-page',
            ],
            [   
                'page' => 'pilot-edit-profile',
                'section_name' => 'Above Edit Profile Title',
                'section_name_slug' => 'above-edit-profile-title',
            ],
            [   
                'page' => 'pilot-edit-profile',
                'section_name' => 'Bottom Of The Page',
                'section_name_slug' => 'bottom-of-the-page',
            ],
            [   
                'page' => 'pilot-change-password',
                'section_name' => 'Above Settings Title',
                'section_name_slug' => 'above-settings-title',
            ],
            [   
                'page' => 'pilot-change-password',
                'section_name' => 'Bottom Of The Page',
                'section_name_slug' => 'bottom-of-the-page',
            ],

        ];

        foreach($banner_sections as $banner_section){            
            $page = BannerPageList::where('slug',$banner_section['page'])->first();
            BannerSection::create(['banner_page_list_id'=> $page->id,
            'section_name'=> $banner_section['section_name'],
            'section_name_slug'=> $banner_section['section_name_slug']]);
        }
    }
}
