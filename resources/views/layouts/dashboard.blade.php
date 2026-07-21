<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'MyPortfolio'))</title>

    {{-- Early admin brand theme from localStorage (admin panel only; reduces flash) --}}
    <script>
        (function () {
            try {
                var raw = localStorage.getItem('admin_brand_theme');
                if (!raw) return;
                var theme = JSON.parse(raw);
                var p = theme.admin_primary;
                var s = theme.admin_secondary;
                if (!p || !s) return;
                var root = document.documentElement;
                root.style.setProperty('--color-primary', p);
                root.style.setProperty('--color-primary-hover', 'color-mix(in srgb, ' + p + ' 85%, #000)');
                root.style.setProperty('--color-secondary', s);
                root.style.setProperty('--color-ring', p);
            } catch (e) {}
        })();
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased"
    data-page="@yield('page', 'admin')"
    data-layout="dashboard"
>
    @php
        // Role/modules can later come from API session or shared JS bootstrap.
        $navItems = \App\Support\AdminNavigation::items(
            role: null,
            modules: null,
        );
    @endphp

    <x-admin.sidebar :items="$navItems" />

    <div class="lg:pl-64">
        {{-- Top bar --}}
        <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/90 backdrop-blur">
            <div class="flex h-16 items-center gap-3 px-4 sm:px-6">
                <button
                    type="button"
                    id="admin-sidebar-toggle"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-2 text-slate-600 transition hover:bg-slate-50 lg:hidden"
                    aria-controls="admin-sidebar"
                    aria-expanded="false"
                    aria-label="Open menu"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div class="min-w-0 flex-1">
                    <h1 class="truncate text-base font-semibold text-slate-900">@yield('heading', 'Dashboard')</h1>
                </div>

                <div class="hidden text-sm text-slate-500 sm:block" id="sidebar-user-chip">
                    <span id="sidebar-user-name" class="font-medium text-slate-700">—</span>
                </div>
            </div>
        </header>

        <main class="px-4 py-8 sm:px-6 lg:px-8">
            @yield('content')
        </main>
    </div>

    <div
        id="toast-host"
        class="pointer-events-none fixed inset-x-0 top-4 z-50 flex flex-col items-end px-4 sm:px-6"
        aria-live="polite"
    ></div>
</body>
</html>
