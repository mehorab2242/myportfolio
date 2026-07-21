@extends('layouts.dashboard')

@section('title', ($title ?? 'Page') . ' — ' . config('app.name', 'MyPortfolio'))
@section('page', 'admin')
@section('heading', $title ?? 'Page')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">
        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-teal-50 text-teal-700">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path d="M12 6v6l4 2"/><circle cx="12" cy="12" r="9"/>
            </svg>
        </div>
        <h2 class="text-xl font-semibold text-slate-900">{{ $title ?? 'Coming soon' }}</h2>
        <p class="mt-2 text-sm text-slate-500">
            This module is ready in the sidebar. Content management will be added next.
        </p>
        <a
            href="{{ route('dashboard') }}"
            class="mt-6 inline-flex rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-800"
        >
            Back to dashboard
        </a>
    </div>
</div>
@endsection
