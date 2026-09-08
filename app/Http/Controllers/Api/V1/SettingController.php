<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SettingResource;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    /**
     * Get public site settings & branding.
     */
    public function index(): JsonResponse
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        $appLogo = get_setting('app_logo');
        $appFavicon = get_setting('app_favicon');

        $data = [
            'app_name' => $settings['app_name'] ?? 'The Azura Hotel & Suites',
            'hotel_tagline' => $settings['hotel_tagline'] ?? 'A place where thoughtful design, genuine hospitality and unforgettable experiences come together.',
            'app_logo' => $appLogo ? asset('storage/' . ltrim($appLogo, '/')) : null,
            'app_favicon' => $appFavicon ? asset('storage/' . ltrim($appFavicon, '/')) : null,
            'contact_email' => $settings['contact_email'] ?? 'reservation.theazura@gmail.com',
            'contact_phone' => $settings['contact_phone'] ?? '+880 1401 777 888',
            'address' => $settings['address'] ?? 'Marine Drive Road, Cox\'s Bazar, Bangladesh',
            'facebook_url' => $settings['facebook_url'] ?? 'https://facebook.com',
            'instagram_url' => $settings['instagram_url'] ?? 'https://instagram.com',
            'twitter_url' => $settings['twitter_url'] ?? 'https://twitter.com',
            'youtube_url' => $settings['youtube_url'] ?? 'https://youtube.com',
        ];

        return response()->json([
            'data' => new SettingResource($data),
        ]);
    }
}
