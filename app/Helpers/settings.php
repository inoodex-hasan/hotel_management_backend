<?php

use App\Models\Setting;

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        if (in_array($key, ['app_logo', 'app_favicon'], true) && $setting->hasMedia($key)) {
            return $setting->getFirstMedia($key)->getPathRelativeToRoot();
        }

        return $setting->value;
    }
}
