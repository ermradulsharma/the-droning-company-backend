<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            {{ trans('panel.site_title') }}
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        @can('user_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/permissions*") ? "c-show" : "" }} {{ request()->is("admin/roles*") ? "c-show" : "" }} {{ request()->is("admin/users*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                     @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    @endcan
                    <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.subscriptions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/subscriptions") || request()->is("admin/subscriptions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                Subscription
                            </a>
                        </li>
                    @can('permission_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.permissions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('role_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                    @endcan
                   
                </ul>
            </li>
        @endcan
        @can('pilot_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/pilot*") ? "c-show" : "" }} ">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.pilotManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('pilot_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.pilot.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/pilot") || request()->is("admin/pilot/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon"></i>
                                {{ trans('cruds.pilotProfile.title') }}
                            </a>
                        </li>
                        
                    @endcan
                    <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.pilot-galleries.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/pilot-galleries") || request()->is("admin/pilot-galleries/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-asterisk c-sidebar-nav-icon"></i>
                                Pilot Gallery
                            </a>
                        </li>
                </ul>
            </li>
        @endcan


        @can('company_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/company*") || request()->is("admin/services*") ? "c-show" : "" }} ">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon"></i> {{ trans('cruds.CompanyManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('company_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.company.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/company") || request()->is("admin/company/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon"></i>
                                {{ trans('cruds.CompanyManagement.profile_title') }}
                            </a>
                        </li>
                    @endcan
                    @can('services_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.company-galleries.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/company-galleries") || request()->is("admin/company-galleries/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-photo c-sidebar-nav-icon"></i>
                                {{ trans('cruds.CompanyManagement.CompanyGallery') }}
                            </a>
                        </li>
                    @endcan
                    @can('services_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.services.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/services") || request()->is("admin/services/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-tags c-sidebar-nav-icon"></i>
                                {{ trans('cruds.CompanyManagement.services') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
		
		
		<li class="c-sidebar-nav-dropdown {{ request()->is("admin/award-category*") || request()->is("admin/award-company*") ? "c-show" : "" }} ">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-award c-sidebar-nav-icon"></i> Award
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.award-company.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/award-company") || request()->is("admin/company/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon"></i>
                                Award Nominee
                            </a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.award-category.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/award-category") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-list c-sidebar-nav-icon"></i>
                                Award Categories
                            </a>
                        </li>
                </ul>
            </li>
        @endcan

        <li class="c-sidebar-nav-item">
        <a href="{{ route("admin.skills.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/pilot") || request()->is("admin/pilot/*") ? "c-active" : "" }}">
            <i class="fa-fw fas fa-asterisk c-sidebar-nav-icon"></i>
            {{ trans('cruds.pilotSkills.title') }}
        </a>
    </li>
        @can('pilot_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/pilot*") ? "c-show" : "" }} ">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.website.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('pilot_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.photo_gallery.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/pilot") || request()->is("admin/pilot/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon"></i>
                                {{ trans('cruds.photo_gallery.title') }}
                            </a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.gear_review.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/pilot") || request()->is("admin/pilot/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-asterisk c-sidebar-nav-icon"></i>
                                {{ trans('cruds.gear_review.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('pilot_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/pilot*") ? "c-show" : "" }} ">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.Coupons.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('pilot_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.coupons.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/pilot") || request()->is("admin/pilot/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon"></i>
                                Coupon
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('content_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/content-categories*") ? "c-show" : "" }} {{ request()->is("admin/content-tags*") ? "c-show" : "" }} {{ request()->is("admin/content-pages*") ? "c-show" : "" }}{{ request()->is("admin/favel-footnote-boxes*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-book c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.contentManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('content_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.content-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/content-categories") || request()->is("admin/content-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-folder c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.contentCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    
                    @can('content_page_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.content-pages.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/content-pages") || request()->is("admin/content-pages/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-file c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.contentPage.title') }}
                            </a>
                        </li>
                    @endcan

                    @can('content_page_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.ads.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/ads") || request()->is("admin/ads/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-file c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.banner.title') }}
                            </a>
                        </li>
                    @endcan

                    @can('content_page_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.favel-footnote-boxes.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/favel-footnote-boxes") || request()->is("admin/favel-footnote-boxes/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-file c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.favelBoxes.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('blog_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/blog-categories*") ? "c-show" : "" }} {{ request()->is("admin/blogs*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fab fa-blogger c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.blogManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('blog_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.blog-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/blog-categories") || request()->is("admin/blog-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-rss-square c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.blogCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('blog_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.blogs.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/blogs") || request()->is("admin/blogs/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-blogger c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.blog.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('event_access')
        <li class="c-sidebar-nav-item {{ request()->is('admin/events/*') ? 'c-show' : '' }}">
            <a class="c-sidebar-nav-link {{ request()->is('admin/events/*') ? 'c-active' : '' }}" href="{{ route('admin.events.index') }}">
                <i class="fa-fw fas fa-calendar c-sidebar-nav-icon"></i> Events
            </a>
        </li>
        @endcan
        
        @can('faq_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/faq-categories*") ? "c-show" : "" }} {{ request()->is("admin/faq-questions*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-question c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.faqManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('faq_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.faq-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/faq-categories") || request()->is("admin/faq-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.faqCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('faq_question_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.faq-questions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/faq-questions") || request()->is("admin/faq-questions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-question c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.faqQuestion.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.pilot-jobs.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/pilot-jobs") || request()->is("admin/pilot-jobs/*") ? "c-active" : "" }}">
                <i class="c-sidebar-nav-icon fa-fw fas fa-briefcase">

                </i>
                Jobs
            </a>
        </li>
         <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.image-cdn.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/image-cdn") || request()->is("admin/image-cdn/*") ? "c-active" : "" }}">
                <i class="c-sidebar-nav-icon fa-fw fas fa-briefcase">

                </i>
                Image Cdn
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.setting.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/setting") || request()->is("admin/setting/*") ? "c-active" : "" }}">
                <i class="c-sidebar-nav-icon fa-fw fas fa-briefcase">

                </i>
                Setting
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.systemCalendar") }}" class="c-sidebar-nav-link {{ request()->is("admin/system-calendar") || request()->is("admin/system-calendar/*") ? "c-active" : "" }}">
                <i class="c-sidebar-nav-icon fa-fw fas fa-calendar">

                </i>
                {{ trans('global.systemCalendar') }}
            </a>
        </li>
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}" href="{{ route('profile.password.edit') }}">
                        <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                        </i>
                        {{ trans('global.change_password') }}
                    </a>
                </li>
            @endcan
        @endif
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>
