@extends('admin.layouts.master')

@section('title', 'Application & Hotel Settings')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <ul class="flex items-center space-x-2 rtl:space-x-reverse text-xs text-slate-400 mb-1">
            <li><a href="{{ route('tyro-dashboard.index') }}" class="hover:underline">Dashboard</a></li>
            <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2"><span>Settings</span></li>
        </ul>
        <h2 class="text-xl font-semibold uppercase">Hotel & General Settings</h2>
        <p class="text-xs text-slate-400 mt-1">Configure hotel name, contact details, address, social channels, and brand assets</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-6">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT 2 COLS: General & Contact Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Brand & Identity -->
            <div class="panel">
                <h5 class="text-md font-bold uppercase tracking-wider text-slate-700 dark:text-white mb-5 pb-3 border-b border-slate-100 dark:border-slate-800">
                    Hotel Identity & Brand
                </h5>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="app_name" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                            Hotel Brand Name
                        </label>
                        <input id="app_name" type="text" name="app_name"
                            value="{{ old('app_name', $settings['app_name'] ?? 'The Azura Hotel & Suites') }}"
                            class="form-input text-sm" placeholder="e.g. The Azura Hotel & Suites" />
                    </div>

                    <div class="md:col-span-2">
                        <label for="hotel_tagline" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                            Hotel Tagline / Short Summary
                        </label>
                        <input id="hotel_tagline" type="text" name="hotel_tagline"
                            value="{{ old('hotel_tagline', $settings['hotel_tagline'] ?? 'A place where thoughtful design, genuine hospitality and unforgettable experiences come together.') }}"
                            class="form-input text-sm" placeholder="Short tagline shown in footer & metadata" />
                    </div>
                </div>
            </div>

            <!-- Contact & Address -->
            <div class="panel">
                <h5 class="text-md font-bold uppercase tracking-wider text-slate-700 dark:text-white mb-5 pb-3 border-b border-slate-100 dark:border-slate-800">
                    Contact & Location Information
                </h5>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="contact_phone" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                            Contact Phone Number
                        </label>
                        <input id="contact_phone" type="text" name="contact_phone"
                            value="{{ old('contact_phone', $settings['contact_phone'] ?? '+880 1401 777 888') }}"
                            class="form-input text-sm" placeholder="+880 1401 777 888" />
                    </div>

                    <div>
                        <label for="contact_email" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                            Contact Email Address
                        </label>
                        <input id="contact_email" type="email" name="contact_email"
                            value="{{ old('contact_email', $settings['contact_email'] ?? 'reservation.theazura@gmail.com') }}"
                            class="form-input text-sm" placeholder="reservation.theazura@gmail.com" />
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                            Physical Address / Location
                        </label>
                        <textarea id="address" name="address" rows="3" class="form-input text-sm"
                            placeholder="e.g. Marine Drive Road, Cox's Bazar, Bangladesh">{{ old('address', $settings['address'] ?? 'Marine Drive Road, Cox\'s Bazar, Bangladesh') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Social Media Links -->
            <div class="panel">
                <h5 class="text-md font-bold uppercase tracking-wider text-slate-700 dark:text-white mb-5 pb-3 border-b border-slate-100 dark:border-slate-800">
                    Social Media Profiles
                </h5>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="facebook_url" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                            Facebook Page URL
                        </label>
                        <input id="facebook_url" type="url" name="facebook_url"
                            value="{{ old('facebook_url', $settings['facebook_url'] ?? 'https://facebook.com') }}"
                            class="form-input text-sm" placeholder="https://facebook.com/..." />
                    </div>

                    <div>
                        <label for="instagram_url" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                            Instagram Profile URL
                        </label>
                        <input id="instagram_url" type="url" name="instagram_url"
                            value="{{ old('instagram_url', $settings['instagram_url'] ?? 'https://instagram.com') }}"
                            class="form-input text-sm" placeholder="https://instagram.com/..." />
                    </div>

                    <div>
                        <label for="twitter_url" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                            X / Twitter URL
                        </label>
                        <input id="twitter_url" type="url" name="twitter_url"
                            value="{{ old('twitter_url', $settings['twitter_url'] ?? 'https://twitter.com') }}"
                            class="form-input text-sm" placeholder="https://x.com/..." />
                    </div>

                    <div>
                        <label for="youtube_url" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                            YouTube Channel URL
                        </label>
                        <input id="youtube_url" type="url" name="youtube_url"
                            value="{{ old('youtube_url', $settings['youtube_url'] ?? 'https://youtube.com') }}"
                            class="form-input text-sm" placeholder="https://youtube.com/..." />
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT 1 COL: Logos & Save -->
        <div class="space-y-6">
            <!-- Brand Assets -->
            <div class="panel">
                <h5 class="text-md font-bold uppercase tracking-wider text-slate-700 dark:text-white mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                    Logos & Favicon
                </h5>

                <div class="space-y-5">
                    <div>
                        <label for="app_logo" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300 block mb-1">
                            Main Logo
                        </label>
                        <input id="app_logo" type="file" name="app_logo" accept="image/*"
                            class="form-input file:py-1.5 file:px-3 file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 text-xs" />
                        @if(get_setting('app_logo'))
                            <div class="mt-3 p-3 bg-slate-900 rounded-lg flex items-center justify-center">
                                <img src="{{ asset('storage/' . get_setting('app_logo')) }}" alt="Logo" class="max-h-16 w-auto object-contain" />
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                        <label for="app_favicon" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300 block mb-1">
                            Favicon Icon
                        </label>
                        <input id="app_favicon" type="file" name="app_favicon" accept="image/*"
                            class="form-input file:py-1.5 file:px-3 file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 text-xs" />
                        @if(get_setting('app_favicon'))
                            <div class="mt-3 p-3 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center">
                                <img src="{{ asset('storage/' . get_setting('app_favicon')) }}" alt="Favicon" class="h-8 w-8 object-contain" />
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Save Panel -->
            <div class="panel">
                <h5 class="text-md font-bold uppercase tracking-wider text-slate-700 dark:text-white mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                    Save Changes
                </h5>
                <p class="text-xs text-slate-400 mb-4">
                    Updates to hotel contact and social info are instantly reflected on the website header, footer, and contact page.
                </p>
                <button type="submit" class="btn btn-primary w-full">
                    Save Settings
                </button>
            </div>
        </div>
    </div>
</form>
@endsection