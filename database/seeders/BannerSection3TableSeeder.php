<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BannerSection;
use App\Models\BannerPageList;
use Illuminate\Support\Facades\Log;

class BannerSection3TableSeeder extends Seeder
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
                'page' => 'pilot-dashboard',
                'section_name' => 'Above Dashboard Title 2',
                'section_name_slug' => 'above-dashboard-title-2',
            ],
            [   
                'page' => 'pilot-dashboard',
                'section_name' => 'Bottom Of The Page 2',
                'section_name_slug' => 'bottom-of-the-page-2',
            ],
            [   
                'page' => 'pilot-build-profile',
                'section_name' => 'Above Build Profile Title 2',
                'section_name_slug' => 'above-build-profile-title-2',
            ],
            [   
                'page' => 'pilot-build-profile',
                'section_name' => 'Bottom Of The Page 2',
                'section_name_slug' => 'bottom-of-the-page-2',
            ],
            [   
                'page' => 'pilot-my-subscriptions',
                'section_name' => 'Bottom Of The Page 2',
                'section_name_slug' => 'bottom-of-the-page-2',
            ],
            [   
                'page' => 'pilot-my-subscriptions',
                'section_name' => 'Above Subscription Title 2',
                'section_name_slug' => 'above-subscription-title-2',
            ],
            [   
                'page' => 'pilot-service-locations',
                'section_name' => 'Bottom Of The Page 2',
                'section_name_slug' => 'bottom-of-the-page-2',
            ],
            [   
                'page' => 'pilot-service-locations',
                'section_name' => 'Above Location Title 2',
                'section_name_slug' => 'above-location-title-2',
            ],
            [   
                'page' => 'pilot-photo-gallery',
                'section_name' => 'Bottom Of The Page 2',
                'section_name_slug' => 'bottom-of-the-page-2',
            ],
            [   
                'page' => 'pilot-photo-gallery',
                'section_name' => 'Above Photo Gallery Title 2',
                'section_name_slug' => 'above-photo-gallery-title-2',
            ],
            [   
                'page' => 'pilot-video-gallery',
                'section_name' => 'Bottom Of The Page 2',
                'section_name_slug' => 'bottom-of-the-page-2',
            ],
            [   
                'page' => 'pilot-video-gallery',
                'section_name' => 'Above Video Title 2',
                'section_name_slug' => 'above-video-title-2',
            ],
            [   
                'page' => 'pilot-my-equipment',
                'section_name' => 'Bottom Of The Page 2',
                'section_name_slug' => 'bottom-of-the-page-2',
            ],
            [   
                'page' => 'pilot-my-equipment',
                'section_name' => 'Above My Equipment Title 2',
                'section_name_slug' => 'above-my-equipment-title-2',
            ],
            [   
                'page' => 'pilot-edit-profile',
                'section_name' => 'Bottom Of The Page 2',
                'section_name_slug' => 'bottom-of-the-page-2',
            ],
            [   
                'page' => 'pilot-edit-profile',
                'section_name' => 'Above Edit Profile Title 2',
                'section_name_slug' => 'above-edit-profile-title-2',
            ],
            [   
                'page' => 'pilot-change-password',
                'section_name' => 'Bottom Of The Page 2',
                'section_name_slug' => 'bottom-of-the-page-2',
            ],
            [   
                'page' => 'pilot-change-password',
                'section_name' => 'Above Change Password Title 2',
                'section_name_slug' => 'above-change-password-title-2',
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
