@extends('layouts.guest')

@section('title', 'Sign in — ' . config('app.name', 'MyPortfolio'))
@section('page', 'login')

@section('content')
<div class="flex min-h-screen">
    {{-- Brand panel --}}
    <aside class="relative hidden w-[52%] flex-col justify-between overflow-hidden bg-[#07111f] px-12 py-10 lg:flex xl:px-16">
        <div class="auth-hero-grid pointer-events-none absolute inset-0 opacity-[0.35]"></div>
        <div class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-teal-500/20 blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-0 left-1/3 h-64 w-64 rounded-full bg-cyan-400/10 blur-3xl"></div>

        <div class="relative z-10">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-500 text-white shadow-lg shadow-teal-500/30">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M3 9h18M9 9v11" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                </span>
                <span class="text-lg font-semibold tracking-tight text-white">{{ config('app.name', 'MyPortfolio') }}</span>
            </a>
        </div>

        <div class="relative z-10 max-w-lg">
            <h1 class="text-4xl font-semibold leading-tight tracking-tight text-white xl:text-5xl">
                Shape your digital presence from one workspace.
            </h1>
            <p class="mt-5 text-base leading-relaxed text-slate-300">
                Projects, profile, and client-ready pages — managed together in a secure control center built for creators.
            </p>

            <ul class="mt-10 space-y-4">
                <li class="flex items-start gap-3 text-slate-200">
                    <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-500/20 text-teal-300 ring-1 ring-teal-400/40">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 011.414-1.414L8.5 11.586l6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    <span class="text-sm font-medium">Curate projects and case studies in minutes</span>
                </li>
                <li class="flex items-start gap-3 text-slate-200">
                    <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-500/20 text-teal-300 ring-1 ring-teal-400/40">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 011.414-1.414L8.5 11.586l6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    <span class="text-sm font-medium">Publish updates instantly to your live site</span>
                </li>
                <li class="flex items-start gap-3 text-slate-200">
                    <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-500/20 text-teal-300 ring-1 ring-teal-400/40">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 011.414-1.414L8.5 11.586l6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    <span class="text-sm font-medium">Keep access secure with role-based controls</span>
                </li>
            </ul>
        </div>

        <p class="relative z-10 text-xs text-slate-500">
            &copy; {{ date('Y') }} {{ config('app.name', 'MyPortfolio') }}. All rights reserved.
        </p>
    </aside>

    {{-- Form panel --}}
    <section class="relative flex w-full flex-1 flex-col bg-[#f7f8fa] lg:w-[48%] lg:bg-white">
        <div class="flex items-center justify-between px-6 py-5 lg:absolute lg:inset-x-0 lg:top-0 lg:px-10">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 lg:hidden">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-teal-600 text-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M3 9h18M9 9v11" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                </span>
                <span class="text-base font-semibold text-slate-900">{{ config('app.name', 'MyPortfolio') }}</span>
            </a>
            <span class="hidden lg:block"></span>
        </div>

        <div class="flex flex-1 items-center justify-center px-6 py-10 sm:px-10">
            <div class="w-full max-w-[400px]">
                <div class="mb-8 text-center">
                    <div class="mx-auto mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#0f172a] text-white shadow-md">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M3 9h18M9 9v11" stroke="currentColor" stroke-width="1.8"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Sign in</h2>
                    <p class="mt-2 text-sm text-slate-500">Welcome back — enter your details to continue.</p>
                </div>

                <div
                    id="login-error"
                    class="mb-4 hidden rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-2.5 text-sm text-rose-700"
                    role="alert"
                ></div>

                <form id="login-form" class="space-y-5" novalidate>
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">
                            Email address <span class="text-rose-500">*</span>
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            class="block w-full rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-600 focus:bg-white focus:ring-teal-600"
                            placeholder="you@example.com"
                        >
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                required
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2.5 pr-11 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-600 focus:bg-white focus:ring-teal-600"
                                placeholder="••••••••"
                            >
                            <button
                                type="button"
                                id="toggle-password"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 transition hover:text-slate-700"
                                aria-label="Show password"
                            >
                                <svg id="icon-eye" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg id="icon-eye-off" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M3 3l18 18M10.7 10.7a3 3 0 004.2 4.2M9.9 5.1A10.5 10.5 0 0112 5c6.5 0 10 7 10 7a17.6 17.6 0 01-4.2 4.8M6.1 6.1A17.3 17.3 0 002 12s3.5 7 10 7c1.4 0 2.7-.3 3.9-.7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                            <input
                                id="remember"
                                name="remember"
                                type="checkbox"
                                class="rounded border-slate-300 text-teal-600 focus:ring-teal-600"
                            >
                            Remember me
                        </label>
                        <span class="text-sm font-medium text-teal-700/70" title="Password reset coming soon">
                            Forgot password?
                        </span>
                    </div>

                    <button
                        id="login-button"
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-teal-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-70"
                    >
                        <span id="login-button-label">Sign in</span>
                        <span id="login-button-spinner" class="hidden" aria-hidden="true">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                        </span>
                    </button>
                </form>

                <p class="mt-8 text-center text-xs text-slate-400">
                    Authorized users only. Access is secured with API tokens.
                </p>
            </div>
        </div>
    </section>
</div>
@endsection
