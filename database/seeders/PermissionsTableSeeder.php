<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'content_management_access',
            ],
            [
                'id'    => 18,
                'title' => 'content_category_create',
            ],
            [
                'id'    => 19,
                'title' => 'content_category_edit',
            ],
            [
                'id'    => 20,
                'title' => 'content_category_show',
            ],
            [
                'id'    => 21,
                'title' => 'content_category_delete',
            ],
            [
                'id'    => 22,
                'title' => 'content_category_access',
            ],
            [
                'id'    => 23,
                'title' => 'content_tag_create',
            ],
            [
                'id'    => 24,
                'title' => 'content_tag_edit',
            ],
            [
                'id'    => 25,
                'title' => 'content_tag_show',
            ],
            [
                'id'    => 26,
                'title' => 'content_tag_delete',
            ],
            [
                'id'    => 27,
                'title' => 'content_tag_access',
            ],
            [
                'id'    => 28,
                'title' => 'content_page_create',
            ],
            [
                'id'    => 29,
                'title' => 'content_page_edit',
            ],
            [
                'id'    => 30,
                'title' => 'content_page_show',
            ],
            [
                'id'    => 31,
                'title' => 'content_page_delete',
            ],
            [
                'id'    => 32,
                'title' => 'content_page_access',
            ],
            [
                'id'    => 33,
                'title' => 'blog_category_create',
            ],
            [
                'id'    => 34,
                'title' => 'blog_category_edit',
            ],
            [
                'id'    => 35,
                'title' => 'blog_category_show',
            ],
            [
                'id'    => 36,
                'title' => 'blog_category_delete',
            ],
            [
                'id'    => 37,
                'title' => 'blog_category_access',
            ],
            [
                'id'    => 38,
                'title' => 'blog_create',
            ],
            [
                'id'    => 39,
                'title' => 'blog_edit',
            ],
            [
                'id'    => 40,
                'title' => 'blog_show',
            ],
            [
                'id'    => 41,
                'title' => 'blog_delete',
            ],
            [
                'id'    => 42,
                'title' => 'blog_access',
            ],
            [
                'id'    => 43,
                'title' => 'blog_management_access',
            ],
            [
                'id'    => 44,
                'title' => 'faq_management_access',
            ],
            [
                'id'    => 45,
                'title' => 'faq_category_create',
            ],
            [
                'id'    => 46,
                'title' => 'faq_category_edit',
            ],
            [
                'id'    => 47,
                'title' => 'faq_category_show',
            ],
            [
                'id'    => 48,
                'title' => 'faq_category_delete',
            ],
            [
                'id'    => 49,
                'title' => 'faq_category_access',
            ],
            [
                'id'    => 50,
                'title' => 'faq_question_create',
            ],
            [
                'id'    => 51,
                'title' => 'faq_question_edit',
            ],
            [
                'id'    => 52,
                'title' => 'faq_question_show',
            ],
            [
                'id'    => 53,
                'title' => 'faq_question_delete',
            ],
            [
                'id'    => 54,
                'title' => 'faq_question_access',
            ],
            [
                'id'    => 55,
                'title' => 'profile_password_edit',
            ],
            [
                'id'    => 56,
                'title' => 'pilot_access',
            ],
            [
                'id'    => 57,
                'title' => 'pilot_profile_edit',
            ],
            [
                'id'    => 58,
                'title' => 'pilot_profile_add',
            ],
        ];

        Permission::insert($permissions);
    }
}
