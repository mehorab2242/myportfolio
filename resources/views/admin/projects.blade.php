@extends('layouts.dashboard')

@section('title', 'Projects — ' . config('app.name', 'MyPortfolio'))
@section('page', 'projects')
@section('heading', 'Projects')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-500">
            Portfolio items for any profession — projects, case studies, works, or research.
        </p>
        <div class="flex flex-wrap items-center gap-2">
            <button
                type="button"
                id="manage-categories-btn"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
            >
                Manage Categories
            </button>
            <button
                type="button"
                id="add-item-btn"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary-token px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-hover-token focus:outline-none focus:ring-2 focus:ring-primary-token focus:ring-offset-2"
            >
                Add Item
            </button>
        </div>
    </div>

    <div id="projects-loading" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @for ($i = 0; $i < 3; $i++)
            <div class="animate-pulse overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="aspect-[16/10] bg-slate-200"></div>
                <div class="space-y-3 p-4">
                    <div class="h-4 w-3/4 rounded bg-slate-200"></div>
                    <div class="h-3 w-full rounded bg-slate-100"></div>
                    <div class="h-3 w-2/3 rounded bg-slate-100"></div>
                </div>
            </div>
        @endfor
    </div>

    <div id="projects-empty" class="hidden rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">
        <p class="text-base font-semibold text-slate-800">No portfolio items yet</p>
        <p class="mt-1 text-sm text-slate-500">Add your first project, case study, or work sample.</p>
        <button
            type="button"
            id="empty-add-item-btn"
            class="mt-6 inline-flex items-center justify-center rounded-xl bg-primary-token px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-hover-token"
        >
            Add your first item
        </button>
    </div>

    <div id="items-grid" class="hidden grid gap-4 sm:grid-cols-2 lg:grid-cols-3"></div>
</div>

@include('admin.projects.partials.item-modal')
@include('admin.projects.partials.categories-modal')
@endsection
