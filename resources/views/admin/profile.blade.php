@extends('layouts.dashboard')

@section('title', 'Profile — ' . config('app.name', 'MyPortfolio'))
@section('page', 'profile')
@section('heading', 'Profile')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-500">
            Manage how your portfolio presents you. Changes sync via the Laravel API.
        </p>
        <div class="flex flex-wrap items-center gap-2">
            <button
                type="button"
                id="preview-portfolio-button"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
            >
                Preview portfolio
            </button>
            <button
                type="submit"
                form="profile-form"
                id="profile-save-button"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary-token px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-hover-token focus:outline-none focus:ring-2 focus:ring-primary-token focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-70"
            >
                <span id="profile-save-label">Save profile</span>
                <span id="profile-save-spinner" class="hidden" aria-hidden="true">
                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>

    <div id="profile-page-loading" class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="flex items-center gap-3 text-slate-500">
            <svg class="h-5 w-5 animate-spin text-primary-token" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span class="text-sm font-medium">Loading profile…</span>
        </div>
    </div>

    <form id="profile-form" class="hidden space-y-6" novalidate>
        @include('admin.profile.partials.header')
        @include('admin.profile.partials.about')
        @include('admin.profile.partials.contact')
        @include('admin.profile.partials.social-links')
        @include('admin.profile.partials.professional-meta')
    </form>
</div>
@endsection
