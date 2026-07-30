<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'MyPortfolio'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased"
    data-page="portfolio"
    data-username="{{ $username ?? request()->route('username') }}"
>
    <div id="portfolio-loading" class="flex min-h-screen items-center justify-center">
        <div class="flex items-center gap-3 text-slate-500">
            <svg class="h-5 w-5 animate-spin text-teal-600" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span class="text-sm font-medium">Loading portfolio…</span>
        </div>
    </div>

    <div id="portfolio-error" class="hidden flex min-h-screen flex-col items-center justify-center px-6 text-center">
        <p class="text-2xl font-semibold text-slate-900">Portfolio not found</p>
        <p id="portfolio-error-message" class="mt-2 max-w-md text-sm text-slate-500">This username does not exist.</p>
        <a href="{{ route('login') }}" class="mt-6 rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-700">Go to login</a>
    </div>

    <div id="portfolio-content" class="hidden">
        <header class="relative overflow-hidden border-b border-slate-200 bg-white">
            <div id="portfolio-cover" class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-teal-900 opacity-90"></div>
            <div class="relative mx-auto max-w-4xl px-4 pb-10 pt-16 sm:px-6">
                <div class="flex flex-col items-start gap-5 sm:flex-row sm:items-end">
                    <img id="portfolio-avatar" src="" alt="" class="h-24 w-24 rounded-2xl border-4 border-white object-cover shadow-lg">
                    <div class="text-white">
                        <h1 id="portfolio-name" class="text-3xl font-semibold tracking-tight">—</h1>
                        <p id="portfolio-title" class="mt-1 text-teal-100"></p>
                        <p id="portfolio-location" class="mt-2 text-sm text-slate-300"></p>
                    </div>
                </div>
                <p id="portfolio-bio" class="relative mt-6 max-w-2xl text-sm leading-relaxed text-slate-200"></p>
                <div id="portfolio-links" class="relative mt-4 flex flex-wrap gap-3 text-sm"></div>
            </div>
        </header>

        <main class="mx-auto max-w-4xl space-y-12 px-4 py-12 sm:px-6">
            <section id="section-about" class="hidden">
                <h2 class="text-lg font-semibold text-slate-900">About</h2>
                <p id="portfolio-about" class="mt-3 whitespace-pre-wrap text-sm leading-relaxed text-slate-600"></p>
            </section>

            <section id="section-skills" class="hidden">
                <h2 class="text-lg font-semibold text-slate-900">Skills</h2>
                <div id="portfolio-skills" class="mt-4 space-y-6"></div>
            </section>

            <section id="section-projects" class="hidden">
                <h2 class="text-lg font-semibold text-slate-900">Projects</h2>
                <div id="portfolio-projects" class="mt-4 grid gap-4 sm:grid-cols-2"></div>
            </section>

            <section id="section-experience" class="hidden">
                <h2 class="text-lg font-semibold text-slate-900">Experience</h2>
                <ol id="portfolio-experience" class="mt-4 space-y-4"></ol>
            </section>

            <section id="section-education" class="hidden">
                <h2 class="text-lg font-semibold text-slate-900">Education</h2>
                <ol id="portfolio-education" class="mt-4 space-y-4"></ol>
            </section>
        </main>

        <footer class="border-t border-slate-200 py-8 text-center text-xs text-slate-400">
            Powered by {{ config('app.name', 'MyPortfolio') }}
        </footer>
    </div>
</body>
</html>
