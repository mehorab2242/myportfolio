@extends('layouts.dashboard')

@section('title', 'Dashboard — ' . config('app.name', 'MyPortfolio'))
@section('page', 'dashboard')
@section('heading', 'Dashboard')

@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm text-slate-500">Your account overview.</p>
            <p id="dashboard-welcome" class="mt-1 text-lg font-semibold text-slate-900">Welcome</p>
        </div>
        <a
            id="view-portfolio-btn"
            href="#"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex items-center justify-center rounded-xl bg-primary-token px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-hover-token"
        >
            View Portfolio
        </a>
    </div>

    <div id="dashboard-loading" class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="flex items-center gap-3 text-slate-500">
            <svg class="h-5 w-5 animate-spin text-primary-token" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span class="text-sm font-medium">Loading your profile…</span>
        </div>
    </div>

    <div id="dashboard-content" class="hidden">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Name</p>
                <p id="user-name" class="mt-2 text-lg font-semibold text-slate-900">—</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Username</p>
                <p id="user-username" class="mt-2 text-lg font-semibold text-slate-900">—</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Email</p>
                <p id="user-email" class="mt-2 break-all text-lg font-semibold text-slate-900">—</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Portfolio URL</p>
                <p id="user-portfolio-url" class="mt-2 break-all text-sm font-medium text-primary-token">—</p>
            </div>
        </div>
    </div>
</div>
@endsection
