<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Models\PilotProfile;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::get();
        return view('admin.setting.index', compact('settings'));
    }

    public function edit(Setting $setting)
    {
        return view('admin.setting.edit', compact('setting'));
    }

    public function update(Request $request, Setting $setting)
    {
        $data = $request->all();
        if ($request->hasFile('block_image')) {
            $data['block_image'] = Storage::disk('public_uploads')
                ->put('images/setting', $request->block_image);
        }
        $setting->update($data);

        return redirect()->route('admin.setting.index')
            ->with('success', 'Setting update successfully');;
    }

    public function allLicenseImages()
    {
        $profiles = PilotProfile::select(['license_image'])->get();
        foreach ($profiles as $profile) {
            $img = $profile->license_image;
            if ($img) {
                $urlparts = parse_url($img);
                file_put_contents(public_path('robots.txt'), "Disallow: " . $urlparts['path'] . "\n",  FILE_APPEND | LOCK_EX);
            }
        }
    }
}
