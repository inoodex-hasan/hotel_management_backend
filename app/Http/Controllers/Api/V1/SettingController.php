<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SettingResource;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Get public site settings & branding.
     */
    public function index(): JsonResponse
    {
        $data = Cache::remember('api.settings.all', 3600, function () {
            $settings = Setting::all()->pluck('value', 'key')->toArray();

            $appLogo = get_setting('app_logo');
            $appFavicon = get_setting('app_favicon');

            $expImage = $settings['experience_image'] ?? '/images/room2.avif';
            if ($expImage && (str_starts_with($expImage, '/storage/') || str_starts_with($expImage, 'storage/'))) {
                $expImage = url(ltrim($expImage, '/'));
            }

            return [
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

                // Experience Banner
                'experience_label' => $settings['experience_label'] ?? 'The Azura Experience',
                'experience_title' => $settings['experience_title'] ?? 'Where every stay becomes a memory.',
                'experience_subtitle' => $settings['experience_subtitle'] ?? 'Immerse yourself in panoramic coastal luxury, exceptional gastronomy, and refined seaside serenity.',
                'experience_image' => $expImage,
                'experience_video_url' => $settings['experience_video_url'] ?? null,
                'experience_button_text' => $settings['experience_button_text'] ?? 'Explore Suites',
                'experience_button_link' => $settings['experience_button_link'] ?? '/rooms',
            ];
        });

        return response()->json([
            'data' => new SettingResource($data),
        ]);
    }
}
