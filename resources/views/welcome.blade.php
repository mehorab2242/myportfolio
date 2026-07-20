<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MyPortfolio') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased" data-page="welcome">
    <div class="relative flex min-h-screen flex-col overflow-hidden">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top,_#ccfbf1_0%,_transparent_50%),radial-gradient(ellipse_at_bottom,_#e2e8f0_0%,_transparent_40%)]"></div>

        <header class="relative z-10 mx-auto flex w-full max-w-5xl items-center justify-between px-4 py-6 sm:px-6">
            <span class="text-lg font-semibold tracking-tight">{{ config('app.name', 'MyPortfolio') }}</span>
            <a
                href="{{ route('login') }}"
                class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-800"
            >
                Log in
            </a>
        </header>

        <main class="relative z-10 mx-auto flex w-full max-w-5xl flex-1 flex-col justify-center px-4 pb-20 sm:px-6">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-teal-700">SaaS Portfolio Platform</p>
            <h1 class="mt-4 max-w-2xl text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">
                Build and manage your portfolio with a clean admin foundation.
            </h1>
            <p class="mt-4 max-w-xl text-base text-slate-600">
                API-first authentication with Sanctum. Sign in to open your dashboard.
            </p>
            <div class="mt-8">
                <a
                    href="{{ route('login') }}"
                    class="inline-flex rounded-lg bg-teal-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-800"
                >
                    Go to login
                </a>
            </div>
        </main>
    </div>
</body>
</html>
