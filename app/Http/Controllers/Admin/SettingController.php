<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->all();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'hotel_tagline' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'facebook_url' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255',
            'twitter_url' => 'nullable|string|max:255',
            'youtube_url' => 'nullable|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'app_favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:2048',
        ]);

        $keys = [
            'app_name',
            'hotel_tagline',
            'contact_email',
            'contact_phone',
            'address',
            'facebook_url',
            'instagram_url',
            'twitter_url',
            'youtube_url',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(['key' => $key], ['value' => $request->input($key)]);
            }
        }

        if ($request->hasFile('app_logo')) {
            $logoSetting = Setting::firstOrCreate(['key' => 'app_logo']);
            $logoSetting->clearMediaCollection('app_logo');
            $media = $logoSetting->addMediaFromRequest('app_logo')
                ->toMediaCollection('app_logo');

            $logoSetting->value = $media->getPathRelativeToRoot();
            $logoSetting->save();
        }

        if ($request->hasFile('app_favicon')) {
            $faviconSetting = Setting::firstOrCreate(['key' => 'app_favicon']);
            $faviconSetting->clearMediaCollection('app_favicon');
            $media = $faviconSetting->addMediaFromRequest('app_favicon')
                ->toMediaCollection('app_favicon');

            $faviconSetting->value = $media->getPathRelativeToRoot();
            $faviconSetting->save();
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}