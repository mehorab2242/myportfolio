@extends('layouts.dashboard')

@section('title', 'Skills — ' . config('app.name', 'MyPortfolio'))
@section('page', 'skills')
@section('heading', 'Skills')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-500">
            Organize skills into categories. Works for any profession — levels can be %, text, or stars.
        </p>
        <div class="flex flex-wrap items-center gap-2">
            <button
                type="button"
                id="add-category-btn"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
            >
                Add Category
            </button>
            <button
                type="button"
                id="add-skill-btn"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary-token px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-hover-token focus:outline-none focus:ring-2 focus:ring-primary-token focus:ring-offset-2"
            >
                Add Skill
            </button>
        </div>
    </div>

    <div id="skills-loading" class="space-y-4">
        @for ($i = 0; $i < 2; $i++)
            <div class="animate-pulse rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 h-5 w-40 rounded bg-slate-200"></div>
                <div class="space-y-3">
                    <div class="h-10 rounded-xl bg-slate-100"></div>
                    <div class="h-10 rounded-xl bg-slate-100"></div>
                </div>
            </div>
        @endfor
    </div>

    <div id="skills-empty" class="hidden rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">
        <p class="text-base font-semibold text-slate-800">No skill categories yet</p>
        <p class="mt-1 text-sm text-slate-500">Create a category first, then add skills under it.</p>
        <button
            type="button"
            id="empty-add-category-btn"
            class="mt-6 inline-flex items-center justify-center rounded-xl bg-primary-token px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-hover-token"
        >
            Add your first category
        </button>
    </div>

    <div id="categories-list" class="hidden space-y-4"></div>
</div>

@include('admin.skills.partials.category-modal')
@include('admin.skills.partials.skill-modal')
@endsection
