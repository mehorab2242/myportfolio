@extends('layouts.dashboard')

@section('title', 'Settings — ' . config('app.name', 'MyPortfolio'))
@section('page', 'settings')
@section('heading', 'Settings')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div>
        <p class="text-sm text-slate-500">
            Admin panel brand colours only. Frontend Theme is managed separately.
        </p>
    </div>

    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-slate-900">Admin brand colours</h2>
            <p class="mt-1 text-sm text-slate-500">
                Primary accents (active nav, buttons). Secondary fills the sidebar background.
                Applied across the admin panel after saving.
            </p>
        </div>

        <div
            id="settings-form-error"
            class="mb-4 hidden rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-2.5 text-sm text-rose-700"
            role="alert"
        ></div>

        <div
            id="settings-form-success"
            class="mb-4 hidden rounded-xl border border-emerald-200 bg-emerald-50 px-3.5 py-2.5 text-sm text-emerald-700"
            role="status"
        ></div>

        <form id="admin-brand-form" class="space-y-6" novalidate>
            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="admin_primary" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Primary <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex items-center gap-3">
                        <input
                            id="admin_primary_color"
                            type="color"
                            value="#0d9488"
                            class="h-11 w-14 cursor-pointer rounded-lg border border-slate-200 bg-white p-1"
                            aria-label="Primary colour picker"
                        >
                        <input
                            id="admin_primary"
                            name="admin_primary"
                            type="text"
                            maxlength="7"
                            value="#0d9488"
                            class="block w-full rounded-xl border-slate-200 bg-slate-50 font-mono text-sm focus:border-primary focus:ring-ring"
                            placeholder="#0d9488"
                        >
                    </div>
                    <p id="admin_primary_error" class="mt-1.5 hidden text-sm text-rose-600"></p>
                </div>

                <div>
                    <label for="admin_secondary" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Secondary <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex items-center gap-3">
                        <input
                            id="admin_secondary_color"
                            type="color"
                            value="#14b8a6"
                            class="h-11 w-14 cursor-pointer rounded-lg border border-slate-200 bg-white p-1"
                            aria-label="Secondary colour picker"
                        >
                        <input
                            id="admin_secondary"
                            name="admin_secondary"
                            type="text"
                            maxlength="7"
                            value="#14b8a6"
                            class="block w-full rounded-xl border-slate-200 bg-slate-50 font-mono text-sm focus:border-primary focus:ring-ring"
                            placeholder="#14b8a6"
                        >
                    </div>
                    <p id="admin_secondary_error" class="mt-1.5 hidden text-sm text-rose-600"></p>
                </div>
            </div>

            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Preview</p>
                <div class="flex flex-wrap gap-3">
                    <span id="preview-primary" class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold text-white" style="background:#0d9488">
                        Primary
                    </span>
                    <span id="preview-secondary" class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold text-white" style="background:#14b8a6">
                        Secondary
                    </span>
                    <span id="preview-gradient" class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold text-white" style="background-image:linear-gradient(to right,#0d9488,#14b8a6)">
                        Gradient
                    </span>
                </div>
            </div>

            <div class="flex justify-end border-t border-slate-100 pt-5">
                <button
                    id="settings-save-button"
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary-token px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-hover-token focus:outline-none focus:ring-2 focus:ring-primary-token focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-70"
                >
                    <span id="settings-save-label">Save colours</span>
                    <span id="settings-save-spinner" class="hidden" aria-hidden="true">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </section>
</div>
@endsection
