<?php

Route::get('v1/get-banner/{page_slug}', 'Api\V1\BannerController@index');
Route::get('v1/get-favel-boxes', 'Api\V1\FavelBoxController@index');
Route::post('v1/login', 'Api\V1\LoginAuthController@login');
Route::post('v1/register/pilot', 'Api\V1\RegistrationController@pilot');
Route::post('v1/register/user', 'Api\V1\RegistrationController@user');
Route::post('v1/register/event_user', 'Api\V1\RegistrationController@eventUser');
Route::post('v1/register/company', 'Api\V1\RegistrationController@company');
Route::post('v1/user/step2', 'Api\V1\SubscriptionController@step2');
Route::post('v1/coupon/verify', 'Api\V1\ValidateCouponController@verify');
Route::post('v1/subscription/create', 'Api\V1\SubscriptionController@stripeSubscriptionCreate');
Route::get('v1/subscription/show/{user_id}', 'Api\V1\SubscriptionController@stripeInvoice');
Route::get('v1/subscription/cancel/{user_id}', 'Api\V1\SubscriptionController@subscriptionCancelled');
Route::any('v1/subscription/resume/{user_id}', 'Api\V1\SubscriptionController@subscriptionResume');

Route::post('v1/event-payment', 'Api\V1\EventApiController@stripeEventPayment');
Route::post('v1/update-payment-method', 'Api\V1\SubscriptionController@updatePaymentMethod');

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1'], function () {
    Route::get('blog-categories', 'BlogCategoryApiController@index');
    
    Route::get('home/featured-category', 'BlogApiController@featuredHomeCategory');
    Route::get('home/featured-blog', 'BlogApiController@home');
    Route::get('home/blog', 'BlogApiController@homeFeatured');
    Route::get('get-blogs-by-category', 'BlogApiController@getBlogsByCategory');
    Route::get('recentblogpost', 'BlogApiController@recent');
    Route::get('blog-Feed', 'BlogApiController@BlogRssFeed');
    Route::get('blog/sitemap', 'BlogApiController@sitemap');
    Route::get('blog/{slug}', 'BlogApiController@show');
   
    Route::apiResource('blogs', 'BlogApiController', ['only'=>['index']]);
    
    Route::get('pilot-profile/{slug}', 'PilotProfileApiController@show');
    
    Route::get('pilot-profile/portfolio/{id}', 'PilotProfileApiController@portfolio');
    Route::get('pilot-profile/portfolio-new/{id}', 'PilotProfileApiController@portfolioNew');
    Route::get('pilot-profile/reel/{id}', 'PilotProfileApiController@reel');
    Route::get('pilot-profile/service-address/{profile_id}', 'PilotProfileApiController@serviceAddress');
    Route::get('home/pilot-feature', 'FeaturePilotController@homeFeatured');
    Route::get('pilot/sitemap', 'FeaturePilotController@sitemap');
    Route::get('category/pilot-feature', 'FeaturePilotController@categoryFeature');
    Route::get('pilot/feature', 'FeaturePilotController@index');
    Route::get('states', 'StateApiController@index');
    Route::get('country', 'CountryApiController@index');
    Route::get('photo-galleries', 'PhotoGallaryApiController@index');
    Route::get('gear-reviews', 'GearReviewsApiController@index');
    Route::get('photo-galleries-new', 'PhotoGallaryApiController@new');
    Route::get('gear-reviews-all', 'GearReviewsApiController@allGearReview');
    Route::post('news-subscribe', 'NewsSubscriptionApiController@store');
    Route::post('search', 'SearchApiController@index');
    Route::post('contact-us', 'ContactUsApiController@store');
    Route::get('skill-categories', 'SkillsApiController@index');
    Route::get('city', 'CityApiController@index');
    Route::get('cms/{slug}', 'CmsApiController@index');
    Route::get('faqs', 'FaqListApiController@allFaqList');
    Route::get('verify-email/{token}', 'VerifyEmailController@fixed');
    Route::post('forgot-password', 'ForgotPasswordController@reset');
    Route::post('update-password/{token}', 'ForgotPasswordController@update');
 
    Route::post('job/create', 'JobApiController@store');
    Route::post('job/update/{job_id}', 'JobApiController@edit');

    Route::get('job/publishing-states', 'JobPublishApiController@JobPublishingStates');
    Route::get('job/myJobs', 'JobPublishApiController@myJobs');
    Route::get('job/recently', 'JobPublishApiController@recently');

    Route::get('job/show/{job_id}', 'JobApiController@show');
    Route::get('job/list', 'JobPublicApiController@index');
    Route::get('job/list/show/{job_id}/{slug?}', 'JobPublicApiController@show');
    Route::get('job/list/sitemap', 'JobPublicApiController@sitemap');
    Route::post('change-password', 'ChangePasswordController@update');
    Route::post('profile/update', 'ProfileController@update');
    Route::get('profile/show/{user_id}', 'ProfileController@show');

    //profile delete
    Route::get('profile/delete/{user_id}', 'ProfileController@deactive');

    Route::post('profile/remove-picture', 'ProfileController@removePicture');
    Route::post('company-profile/remove-picture', 'ProfileController@removeCompanyPicture');
    

    Route::post('resend-verification/email', 'ReSendVerifyEmailController');

    Route::post('pilot-dashboard/basic-profile/create', 'PilotProfileBuildController@store');
    Route::post('pilot-dashboard/basic-profile/update/{user_id}', 'PilotProfileBuildController@update');
    Route::get('pilot-dashboard/basic-profile/show/{user_id}', 'PilotProfileBuildController@show');

    Route::post('pilot-dashboard/service-area/{user_id}', 'PilotServiceAreaController@store');

    Route::get('pilot-dashboard/service-area/show/{user_id}', 'PilotServiceAreaController@show');
    Route::post('pilot-dashboard/service-area/delete/{service_id}', 'PilotServiceAreaController@deleteServiceArea');
    Route::post('pilot-dashboard/reel-video/{user_id}', 'PilotVideoController@store');

    Route::get('pilot-dashboard/reel-video/show/{user_id}', 'PilotVideoController@show');
    Route::post('pilot-dashboard/reel-video/delete/{pilot_video_id}', 'PilotVideoController@remove');
    Route::post('pilot-dashboard/reel-video/mark-as-main/{pilot_video_id}', 'PilotVideoController@markAsMainVideo');

    Route::post('pilot-dashboard/equipment/create/{user_id}', 'PilotEquipmentController@store');

    Route::post('pilot-dashboard/equipment/update/{user_id}', 'PilotEquipmentController@update');

    Route::post('pilot-dashboard/equipment/delete/{equipment_id}', 'PilotEquipmentController@remove');

    Route::get('pilot-dashboard/equipment/show/{user_id}', 'PilotEquipmentController@show');

    Route::post('pilot-dashboard/gallery/{user_id}', 'PilotGalleryController@store');

    Route::get('pilot-dashboard/gallery/show/{user_id}', 'PilotGalleryController@show');

    Route::post('pilot-dashboard/gallery/remove/{gallery_id}', 'PilotGalleryController@remove');

    Route::get('pilot-dashboard/index/{user_id}', 'PilotDashboardLandingController');

    Route::get('setting', 'SettingController');
	
	Route::get('cmslist', 'CmsApiController@list');

    
    Route::get('company-dashboard/index/{user_id}', 'CompanyDashboardLandingController');
    Route::post('company-dashboard/basic-profile/create', 'CompanyProfileBuildController@store');
    Route::post('company-dashboard/basic-profile/update/{user_id}', 'CompanyProfileBuildController@update');
    Route::get('company-dashboard/basic-profile/show/{user_id}', 'CompanyProfileBuildController@show');
    Route::get('services', 'CompanyProfileBuildController@services');
    Route::post('company-dashboard/profile/remove/{profile}', 'CompanyProfileBuildController@remove');

    Route::post('company-dashboard/reel-video/{user_id}', 'CompanyVideoController@store');
    Route::get('company-dashboard/reel-video/show/{user_id}', 'CompanyVideoController@show');
    Route::post('company-dashboard/reel-video/delete/{pilot_video_id}', 'CompanyVideoController@remove');
    Route::post('company-dashboard/reel-video/mark-as-main/{pilot_video_id}', 'CompanyVideoController@markAsMainVideo');

    Route::post('company-dashboard/gallery/{user_id}', 'CompanyGalleryController@store');
    Route::get('company-dashboard/gallery/show/{user_id}', 'CompanyGalleryController@show');
    Route::post('company-dashboard/gallery/remove/{gallery_id}', 'CompanyGalleryController@remove');

    Route::get('home/company-feature', 'FeatureCompanyController@homeFeatured');
    Route::get('company/sitemap', 'FeatureCompanyController@sitemap');
    Route::get('category/company-feature', 'FeatureCompanyController@categoryFeature');
    Route::get('company/feature', 'FeatureCompanyController@index');

    Route::get('company-profile/{slug}', 'CompanyProfileApiController@show');
    Route::get('company-profile/portfolio/{id}', 'CompanyProfileApiController@portfolio');
    Route::get('company-profile/portfolio-new/{id}', 'CompanyProfileApiController@portfolioNew');
    Route::get('company-profile/reel/{id}', 'CompanyProfileApiController@reel');

    Route::get('recent-events', 'EventApiController@recent');
    Route::get('event/{slug}', 'EventApiController@show');
    Route::post('event/create', 'EventApiController@store');
    Route::apiResource('events', 'EventApiController', ['only'=>['index']]);

});
