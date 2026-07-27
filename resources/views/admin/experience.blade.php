@extends('layouts.dashboard')

@section('title', 'Experience — ' . config('app.name', 'MyPortfolio'))
@section('page', 'experience')
@section('heading', 'Experience')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-500">
            Roles and work history — companies, hospitals, firms, studios, and more.
        </p>
        <button
            type="button"
            id="add-experience-btn"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary-token px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-hover-token focus:outline-none focus:ring-2 focus:ring-primary-token focus:ring-offset-2"
        >
            Add Experience
        </button>
    </div>

    <div id="experience-loading" class="space-y-4">
        @for ($i = 0; $i < 2; $i++)
            <div class="flex animate-pulse gap-4">
                <div class="flex w-4 flex-col items-center">
                    <div class="h-3 w-3 rounded-full bg-slate-200"></div>
                    <div class="w-px flex-1 bg-slate-200"></div>
                </div>
                <div class="mb-2 flex-1 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-3 h-4 w-1/2 rounded bg-slate-200"></div>
                    <div class="mb-2 h-3 w-1/3 rounded bg-slate-100"></div>
                    <div class="h-3 w-2/3 rounded bg-slate-100"></div>
                </div>
            </div>
        @endfor
    </div>

    <div id="experience-empty" class="hidden rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">
        <p class="text-base font-semibold text-slate-800">No experience entries yet</p>
        <p class="mt-1 text-sm text-slate-500">Add your first role or position.</p>
        <button
            type="button"
            id="empty-add-experience-btn"
            class="mt-6 inline-flex items-center justify-center rounded-xl bg-primary-token px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-hover-token"
        >
            Add your first experience
        </button>
    </div>

    <ol id="experience-timeline" class="hidden space-y-0"></ol>
</div>

@include('admin.experience.partials.experience-modal')
@endsection
