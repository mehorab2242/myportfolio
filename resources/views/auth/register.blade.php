@extends('layouts.guest')

@section('title', 'Create account — ' . config('app.name', 'MyPortfolio'))
@section('page', 'register')

@section('content')
<div class="flex min-h-screen">
    <aside class="relative hidden w-[52%] flex-col justify-between overflow-hidden bg-[#07111f] px-12 py-10 lg:flex xl:px-16">
        <div class="auth-hero-grid pointer-events-none absolute inset-0 opacity-[0.35]"></div>
        <div class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-teal-500/20 blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-0 left-1/3 h-64 w-64 rounded-full bg-cyan-400/10 blur-3xl"></div>

        <div class="relative z-10">
            <a href="{{ url('/login') }}" class="inline-flex items-center gap-3">
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
                Claim your portfolio URL in minutes.
            </h1>
            <p class="mt-5 text-base leading-relaxed text-slate-300">
                Your username becomes your public address — <span class="text-teal-300">myportfolio.com/you</span>.
            </p>
        </div>

        <p class="relative z-10 text-xs text-slate-500">
            &copy; {{ date('Y') }} {{ config('app.name', 'MyPortfolio') }}. All rights reserved.
        </p>
    </aside>

    <section class="relative flex w-full flex-1 flex-col bg-[#f7f8fa] lg:w-[48%] lg:bg-white">
        <div class="flex flex-1 items-center justify-center px-6 py-10 sm:px-10">
            <div class="w-full max-w-[400px]">
                <div class="mb-8 text-center">
                    <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Create account</h2>
                    <p class="mt-2 text-sm text-slate-500">Start building your portfolio workspace.</p>
                </div>

                <div
                    id="register-error"
                    class="mb-4 hidden rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-2.5 text-sm text-rose-700"
                    role="alert"
                ></div>

                <form id="register-form" class="space-y-4" novalidate>
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Name <span class="text-rose-500">*</span></label>
                        <input id="name" name="name" type="text" required maxlength="255"
                            class="block w-full rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-600 focus:bg-white focus:ring-teal-600"
                            placeholder="Your full name">
                    </div>

                    <div>
                        <label for="username" class="mb-1.5 block text-sm font-medium text-slate-700">Username <span class="text-rose-500">*</span></label>
                        <input id="username" name="username" type="text" required maxlength="30" autocomplete="username"
                            class="block w-full rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-600 focus:bg-white focus:ring-teal-600"
                            placeholder="lowercase-url-safe">
                        <p class="mt-1 text-xs text-slate-400">Becomes <span class="font-medium text-slate-600">/your-username</span></p>
                    </div>

                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email <span class="text-rose-500">*</span></label>
                        <input id="email" name="email" type="email" required autocomplete="email"
                            class="block w-full rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-600 focus:bg-white focus:ring-teal-600"
                            placeholder="you@example.com">
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Password <span class="text-rose-500">*</span></label>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="block w-full rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-600 focus:bg-white focus:ring-teal-600"
                            placeholder="••••••••">
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-slate-700">Confirm password <span class="text-rose-500">*</span></label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                            class="block w-full rounded-xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-600 focus:bg-white focus:ring-teal-600"
                            placeholder="••••••••">
                    </div>

                    <button
                        id="register-button"
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-teal-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-70"
                    >
                        <span id="register-button-label">Create account</span>
                        <span id="register-button-spinner" class="hidden" aria-hidden="true">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                        </span>
                    </button>
                </form>

                <p class="mt-8 text-center text-sm text-slate-500">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold text-teal-700 hover:text-teal-800">Sign in</a>
                </p>
            </div>
        </div>
    </section>
</div>
@endsection
