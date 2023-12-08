<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BannerSection;
use App\Models\BannerPageList;
use Illuminate\Support\Facades\Log;


class BannerSectionTableSeeder extends Seeder
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
                'page' => 'home-page',
                'section_name' => 'Underneath The Main Banner',
                'section_name_slug' => 'underneath-the-main-banner',
            ],
            [   
                'page' => 'home-page', 
                'section_name' => 'Above Gallery Items',
                'section_name_slug' => 'above-gallery-items',
            ],
            [
                'page' => 'home-page',
                'section_name' => 'Above Gear Reviews',
                'section_name_slug' => 'above-gear-reviews',
            ],
            [   
                'page' => 'home-page', 
                'section_name' => 'Under Gear Reviews',
                'section_name_slug' => 'under-gear-reviews',
            ],
            [   
                'page' => 'blog-list',
                'section_name' => 'In List 4th Item',
                'section_name_slug' => 'in-list-4th-item',
            ],
            [   
                'page' => 'blog-list',
                'section_name' => 'In List 8th Item',
                'section_name_slug' => 'in-list-8th-item',
            ],
            [   
                'page' => 'blog-list',
                'section_name' => 'Side Bar',
                'section_name_slug' => 'side-bar',
            ],
            [   
                'page' => 'blog-list',
                'section_name' => 'Above Footer',
                'section_name_slug' => 'above-footer',
            ],
            [   
                'page' => 'blog-details',
                'section_name' => 'Underneath The Main Heading',
                'section_name_slug' => 'Underneath-the-main-heading',
            ],
            [   
                'page' => 'blog-details',
                'section_name' => 'Bottom Of The Blog',
                'section_name_slug' => 'bottom-of-the-blog',
            ],
            [   
                'page' => 'blog-details',
                'section_name' => 'Middle Area Of The Sidebar',
                'section_name_slug' => 'middle-area-of-the-sidebar',
            ],
            [   
                'page' => 'blog-details',
                'section_name' => 'Above Related Post',
                'section_name_slug' => 'above-related-post',
            ],
            [
                'page' => 'job-list',
                'section_name' => 'Above Footer',
                'section_name_slug' => 'above-footer',
            ],
            [   
                'page' => 'job-list', 
                'section_name' => 'Above Gear Reviews',
                'section_name_slug' => 'above-gear-reviews',
            ],
            [   
                'page' => 'job-list', 
                'section_name' => 'Under Gear Reviews',
                'section_name_slug' => 'under-gear-reviews',
            ],
            [   
                'page' => 'about-us',
                'section_name' => 'Above Footer',
                'section_name_slug' => 'above-footer',
            ],
            [   
                'page' => 'about-us',
                'section_name' => 'Under Banner',
                'section_name_slug' => 'under-banner',
            ],
            [   
                'page' => 'about-us',
                'section_name' => 'Above Our Team',
                'section_name_slug' => 'above-our-team',
            ],
            [   
                'page' => 'about-us',
                'section_name' => 'Above Meet The Best Section',
                'section_name_slug' => 'above-meet-the-best-section',
            ],
            [   
                'page' => 'gear-reviews',
                'section_name' => 'Above Footer 1',
                'section_name_slug' => 'above-footer-1',
            ],
            [   
                'page' => 'gear-reviews',
                'section_name' => 'Above Footer 2',
                'section_name_slug' => 'above-footer-2',
            ],
            [   
                'page' => 'gear-reviews',
                'section_name' => 'Under Banner 1',
                'section_name_slug' => 'under-banner-1',
            ],
            [   
                'page' => 'gear-reviews',
                'section_name' => 'Under Banner 2',
                'section_name_slug' => 'under-banner-2',
            ],
            [   
                'page' => 'contact-us',
                'section_name' => 'Above Mail Us',
                'section_name_slug' => 'above-mail-us',
            ],
            [   
                'page' => 'contact-us',
                'section_name' => 'Under Mail Us',
                'section_name_slug' => 'under-mail-us',
            ],
            [   
                'page' => 'contact-us',
                'section_name' => 'Under Banner 1',
                'section_name_slug' => 'under-banner-1',
            ],
            [   
                'page' => 'contact-us',
                'section_name' => 'Under Banner 2',
                'section_name_slug' => 'under-banner-2',
            ],
            [   
                'page' => 'faq',
                'section_name' => 'Under Banner 1',
                'section_name_slug' => 'under-banner-1',
            ],
            [   
                'page' => 'faq',
                'section_name' => 'Above Footer 1',
                'section_name_slug' => 'above-footer-1',
            ],
        ];

        foreach($banner_sections as $banner_section){
            Log::debug("message".print_r($banner_section,true));
            $page = BannerPageList::where('slug',$banner_section['page'])->first();
            BannerSection::create(['banner_page_list_id'=> $page->id,
            'section_name'=> $banner_section['section_name'],
            'section_name_slug'=> $banner_section['section_name_slug']]);
        }

    }
}
