<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // echo "Booting AppServiceProvider\n";
        Gate::before(function ($user, $ability) {
            // echo "Checking ability: {$ability} for user: {$user->email}\n";
            // Super-admin bypass
            if (method_exists($user, 'hasRole') && $user->hasRole('super-admin')) {
                return true;
            }

            // Check if Tyro has this privilege
            if (method_exists($user, 'hasPrivilege') && $user->hasPrivilege($ability)) {
                return true;
            }

            // Check if Tyro has this role (some checks might use role name as ability)
            if (method_exists($user, 'hasRole') && $user->hasRole($ability)) {
                return true;
            }

            return null; // Fallback to other gates/policies
        });

        if (Schema::hasTable('settings')) {
            $appLogo = get_setting('app_logo');
            $appName = get_setting('app_name', config('app.name'));

            if ($appLogo) {
                $appLogoPath = trim($appLogo, '/');
                $logoUrl = asset('storage/' . $appLogoPath);

                config([
                    'tyro-login.branding.logo' => $logoUrl,
                    'tyro-dashboard.branding.logo' => $logoUrl,
                ]);
            }

            config([
                'tyro-login.branding.app_name' => $appName,
                'tyro-dashboard.branding.app_name' => $appName,
            ]);
        }
    }
}
