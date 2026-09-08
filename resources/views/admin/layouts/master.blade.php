<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title') | {{ get_setting('app_name', config('app.name')) }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/x-icon"
        href="{{ get_setting('app_favicon') ? asset('storage/' . get_setting('app_favicon')) : asset('favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/perfect-scrollbar.min.css') }}" />
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/style.css') }}" />
    <link defer rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/animate.css') }}" />
    @stack('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }
        .tippy-box[data-theme~="table-action"] {
            background-color: transparent !important;
            color: #1e293b !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
        }
        .dark .tippy-box[data-theme~="table-action"] {
            color: #f1f5f9 !important;
        }
        .tippy-box[data-theme~="table-action"] > .tippy-content {
            padding: 0 !important;
            color: inherit !important;
            background: transparent !important;
        }
        .tippy-box[data-theme~="table-action"] a:not([class*="text-"]),
        .tippy-box[data-theme~="table-action"] button:not([class*="text-"]) {
            color: #1e293b !important;
        }
        .dark .tippy-box[data-theme~="table-action"] a:not([class*="text-"]),
        .dark .tippy-box[data-theme~="table-action"] button:not([class*="text-"]) {
            color: #f1f5f9 !important;
        }
    </style>
</head>

<body x-data="main" class="relative overflow-x-hidden font-nunito text-sm font-normal antialiased"
    :class="[ $store.app.sidebar ? 'toggle-sidebar' : '', $store.app.theme === 'dark' || $store.app.isDarkMode ?  'dark' : '', $store.app.menu, $store.app.layout,$store.app.rtlClass]">

    @include('admin.layouts.partials.loader')
    @include('admin.layouts.partials.scroll-to-top')

    <div class="main-container min-h-screen text-black dark:text-white-dark" :class="[$store.app.navbar]">
        @include('admin.layouts.sidebar')

        <div class="main-content flex min-h-screen flex-col">
            @include('admin.layouts.header')

            <div class="animate__animated p-6" :class="[$store.app.animation]">
                @include('admin.layouts.partials.flash')
                @yield('content')
            </div>

            @include('admin.layouts.footer')
        </div>
    </div>

    @include('admin.layouts.scripts')
    @stack('scripts')
</body>

</html>