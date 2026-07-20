<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'MyPortfolio'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased"
    data-page="@yield('page')"
>
    <nav class="border-b border-slate-200/80 bg-white/90 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-5xl items-center justify-between px-4 sm:px-6">
            <a href="{{ url('/') }}" class="text-lg font-semibold tracking-tight text-slate-900">
                {{ config('app.name', 'MyPortfolio') }}
            </a>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('login') }}"
                    data-guest-only
                    class="hidden rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                >
                    Log in
                </a>

                <a
                    href="{{ route('dashboard') }}"
                    data-auth-only
                    class="hidden rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                >
                    Dashboard
                </a>

                <button
                    type="button"
                    data-logout
                    data-auth-only
                    class="hidden rounded-lg bg-slate-900 px-3 py-2 text-sm font-medium text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    Log out
                </button>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>
</body>
</html>
