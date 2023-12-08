<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*Route::get('/mapupdate', function () {
    $addressLists = PilotAddress::join('pilot_profile', 'pilot_profile.id', '=', 'pilot_address.pilot_profile_id')
        ->select('pilot_address.*')
        ->get();


    foreach ($addressLists as $key => $value) {
        $newGeo = PilotAddress::find($value->id);

        [$latitude, $longitude] = $newGeo->getLatitudeAndLongitude();

        $newGeo->latitude = $latitude;
        $newGeo->longitude = $longitude;
        $newGeo->save();
    }
    return "all done";
});


Route::get('/jobLocationMapUpdate', function () {
    $addressLists = JobLocation::join('pilot_jobs', 'pilot_jobs.id', '=', 'job_locations.pilot_job_id')
        ->where('pilot_jobs.status', '=', 2)
        ->select('job_locations.*')
        ->get();




    foreach ($addressLists as $key => $value) {
        $newGeo = JobLocation::find($value->id);

        [$latitude, $longitude] = (new CommonService())->findLatitudeAndLongitude($value->city);

        $newGeo->latitude = $latitude;
        $newGeo->longitude = $longitude;
        $newGeo->save();
    }
    return "all done";
});

Route::get('/sub-test', function (Request $request) {
    $user = \App\Models\User::where('id', 65)->first();

    $invoices = $user->invoices();

    dd($invoices->toArray(), $user->findInvoice('in_1JV84fBbrKa9p7qI0lFfOWSS')->toArray());
    return $user->downloadInvoice('in_1JV84fBbrKa9p7qI0lFfOWSS', [], 'my-invoice');
});


Route::get('/mailtest', function () {
    $job = PilotJob::find(1607);
    $user = \App\Models\User::where('id', 65)->first();


    //return new \App\Mail\SubscriptionPaymentConfirmation($user, 'in_1JV84fBbrKa9p7qI0lFfOWSS');
    //return \Illuminate\Support\Facades\Mail::to('md.saif@outlook.com')->send(new \App\Mail\JobPostToAdmin($job));

    return \Illuminate\Support\Facades\Mail::to('md.saif@outlook.com')->send(new \App\Mail\BrowserTest());
});*/

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Route::any('contact-us', [HomeController::class, 'contactForm'])->name('contact-us');


Route::any('award-voting', 'HomeController@awardVoter')->name('award.voting');
Route::any('award-voting/{voter}', 'HomeController@awardVoting')->name('award.voting.poll');
Route::any('award-voting/success/{voter}', 'HomeController@awardVotingSuccess')->name('award.voting.success');
/*
Route::get('/migrateui', function () {
    \Artisan::call('migrate');
    return true;
});

Route::get('/clear', function () {
    \Artisan::call('view:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('route:clear');
    \Artisan::call('config:clear');
    \Artisan::call('optimize:clear');
    \Artisan::call('clear-compiled');
    \Artisan::call('key:generate');
    return true;
});*/

Route::post('stripe/webhook', '\App\Http\Controllers\WebhookController@handleWebhook');


Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');



    // Photo Gallery
    Route::resource('photo_gallery', 'PhotoGalleryController');

    // Gear Review
    Route::resource('gear_review', 'GearReviewController');

    // Pilot Profile
    Route::delete('pilot.massDestroy', 'PilotProfileController@massDestroy')->name('pilot.massDestroy');
    Route::put('pilot.active_status', 'PilotProfileController@massActiveStatus')->name('pilot.massActiveStatus');
    Route::put('pilot.inactive_status', 'PilotProfileController@massInActiveStatus')->name('pilot.massInActiveStatus');
    Route::put('pilot.massCertifiedYes', 'PilotProfileController@massCertifiedYes')->name('pilot.massCertifiedYes');
    Route::put('pilot.massCertifiedNo', 'PilotProfileController@massCertifiedNo')->name('pilot.massCertifiedNo');
    Route::put('pilot.massFeatureYes', 'PilotProfileController@massFeatureYes')->name('pilot.massFeatureYes');
    Route::put('pilot.massFeatureNo', 'PilotProfileController@massFeatureNo')->name('pilot.massFeatureNo');
    Route::get('pilot.getSlug', 'PilotProfileController@getSlug')->name('pilot.getSlug');
    Route::resource('pilot', 'PilotProfileController');

    // Pilot Videos
    Route::post('pilot_videos.edit/{id}', 'PilotVideosController@edit');
    Route::get('pilot_videos.add_more', 'PilotVideosController@add_more')->name('pilot_videos.add_more');
    Route::resource('pilot_videos', 'PilotVideosController');


    // Pilot Equipment
    Route::resource('pilot_equipments', 'PilotEquipmentsController');

    //Pilot Address
    Route::post('pilot_address.get-states', 'PilotAddressController@getStates')->name('pilot_address.get-states');
    Route::resource('pilot_address', 'PilotAddressController');

    //Pilot Skills
    Route::resource('skills', 'SkillController');


    // Coupon

    Route::delete('coupons.massDestroy', 'CouponsController@massDestroy')->name('coupons.massDestroy');
    Route::put('coupons.active_status', 'CouponsController@massActiveStatus')->name('coupons.massActiveStatus');
    Route::put('coupons.inactive_status', 'CouponsController@massInActiveStatus')->name('coupons.massInActiveStatus');
    Route::resource('coupons', 'CouponsController');


    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::put('users/active_status', 'UsersController@massActiveStatus')->name('users.massActiveStatus');
    Route::put('users/inactive_status', 'UsersController@massInActiveStatus')->name('users.massInActiveStatus');
    Route::resource('users', 'UsersController');

    //job
    Route::get('job-status-update/{status_id}/{job_id}', 'JobController@jobStatusUpdate');
    // Route::get('jobs/show/{id}', 'JobController@show');
    Route::get('jobs/show/{id}', 'JobController@show');
    Route::put('pilot-jobs/update/{id}', 'JobController@update');
    Route::resource('pilot-jobs', 'JobController');

    //subscription
    Route::get('subscriptions/show/{id}', 'SubscriptionController@show');
    Route::get('subscriptions/cancel/{user}/{subscription?}', 'SubscriptionController@cancelSubscription')->name('subscriptions.cancel');
    Route::resource('subscriptions', 'SubscriptionController');
    // Content Categories
    Route::delete('content-categories/destroy', 'ContentCategoryController@massDestroy')->name('content-categories.massDestroy');
    Route::resource('content-categories', 'ContentCategoryController');

    // Content Tags
    Route::delete('content-tags/destroy', 'ContentTagController@massDestroy')->name('content-tags.massDestroy');
    Route::resource('content-tags', 'ContentTagController');

    // Content Pages
    Route::delete('content-pages/destroy', 'ContentPageController@massDestroy')->name('content-pages.massDestroy');
    Route::post('content-pages/media', 'ContentPageController@storeMedia')->name('content-pages.storeMedia');
    Route::post('content-pages/ckmedia', 'ContentPageController@storeCKEditorImages')->name('content-pages.storeCKEditorImages');
    Route::resource('content-pages', 'ContentPageController');

    // banner route
    Route::resource('ads', 'BannerController');
    Route::resource('favel-footnote-boxes', 'FavelFootnoteController')->only([
        'index', 'edit', 'update'
    ]);

    Route::resource('favel-footnote-boxes.content', 'FavelFootnoteContentController');

    Route::post('ads/delete', 'BannerController@massDestroy')->name('ads.massDestroy');
    Route::post('ads-sections', 'BannerController@fetchSection')->name('ads.sections');

    // Blog Categories
    Route::delete('blog-categories/destroy', 'BlogCategoryController@massDestroy')->name('blog-categories.massDestroy');
    Route::resource('blog-categories', 'BlogCategoryController');

    // Blog
    Route::delete('blogs/destroy', 'BlogController@massDestroy')->name('blogs.massDestroy');
    Route::post('blogs/media', 'BlogController@storeMedia')->name('blogs.storeMedia');
    Route::post('blogs/ckmedia', 'BlogController@storeCKEditorImages')->name('blogs.storeCKEditorImages');
    Route::resource('blogs', 'BlogController');


    // Faq Categories
    Route::delete('faq-categories/destroy', 'FaqCategoryController@massDestroy')->name('faq-categories.massDestroy');
    Route::resource('faq-categories', 'FaqCategoryController');

    // Faq Questions
    Route::delete('faq-questions/destroy', 'FaqQuestionController@massDestroy')->name('faq-questions.massDestroy');
    Route::resource('faq-questions', 'FaqQuestionController');

    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');


    // Pilot Gallery
    Route::delete('pilot-galleries/destroy', 'PilotGalleryController@massDestroy')->name('pilot-galleries.massDestroy');

    //Route::get('c/add/{id? }', 'PilotGalleryController@create');
    Route::resource('pilot-galleries', 'PilotGalleryController');

    Route::any('tinymceImageUpload', 'ImageCategoryController@store')->name('tinymceImageUpload');

    Route::resource('image-cdn', 'ImageCdnsController');

    Route::resource('setting', 'SettingController');

    Route::get('allLicenseImages', 'SettingController@allLicenseImages');

    // COMPANY PROFILE
    Route::delete('company.massDestroy', 'CompanyProfileController@massDestroy')->name('company.massDestroy');
    Route::put('company.active_status', 'CompanyProfileController@massActiveStatus')->name('company.massActiveStatus');
    Route::put('company.inactive_status', 'CompanyProfileController@massInActiveStatus')->name('company.massInActiveStatus');
    Route::put('company.massCertifiedYes', 'CompanyProfileController@massCertifiedYes')->name('company.massCertifiedYes');
    Route::put('company.massCertifiedNo', 'CompanyProfileController@massCertifiedNo')->name('company.massCertifiedNo');
    Route::put('company.massFeatureYes', 'CompanyProfileController@massFeatureYes')->name('company.massFeatureYes');
    Route::put('company.massFeatureNo', 'CompanyProfileController@massFeatureNo')->name('company.massFeatureNo');
    Route::get('company.getSlug', 'CompanyProfileController@getSlug')->name('company.getSlug');
    Route::resource('company', 'CompanyProfileController');
    Route::resource('services', 'ServiceController');
    Route::resource('company-galleries', 'CompanyGalleryController');
    Route::delete('pilot-galleries/destroy', 'CompanyGalleryController@massDestroy')->name('company-galleries.massDestroy');
    Route::resource('company-videos', 'CompanyVideoController');

    // EVENTS
    Route::resource('events', 'EventController');

    // AWARD
    Route::resource('award-category', 'AwardCategoryController');
    Route::resource('award-company', 'AwardCompanyController');
    Route::get('voting-result', 'AwardCompanyController@votingResult')->name('voting.result');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});


/*Route::get('/job-slug-update', function () {
    $jobs = \App\Models\PilotJob::get();

    foreach ($jobs as $key => $value) {
        $value->slug = \Str::slug($value->job_title);
        $value->save();
    }

    return 'all done';
});*/


if (config('app.artisan') == 1) {
    Route::get('laravel-log', function () {
        return file_get_contents(storage_path('logs/laravel.log'), true);
    });
    Route::get('migrate', function () {
        Artisan::call('migrate');
        return 'Migrate done.';
    });
    Route::get('migrate-fresh-seed', function () {
        Artisan::call('migrate:fresh --seed');
        return 'Migrate fresh and seeder done.';
    });
    Route::get('db-seed', function () {
        Artisan::call('db:seed');
        return 'Seeder done.';
    });
    Route::get('single-seed/{class_name}', function ($class_name) {
        Artisan::call('db:seed --class=' . $class_name);
        return 'seeder done.';
    });
    Route::get('migration-roleback/{file_name}', function ($file_name) {
        Artisan::call('migrate:rollback --path=/database/migrations/' . $file_name);
        return 'roleback done.';
    });
    Route::get('log-clear', function () {
        exec("truncate -s 0 " . storage_path('/logs/laravel.log'));
        return 'log clear done.';
    });
    Route::get('storage-link', function ($file_name) {
        Artisan::call('storage:link');
        return 'storage link generate.';
    });
}
