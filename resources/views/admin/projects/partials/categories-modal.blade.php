{{-- Portfolio categories manage modal --}}
<div id="categories-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="categories-modal-title">
    <div class="absolute inset-0 bg-slate-900/40" data-modal-close="categories-modal"></div>
    <div class="relative mx-auto flex min-h-full max-w-lg items-center p-4">
        <div class="max-h-[90vh] w-full overflow-y-auto rounded-2xl border border-slate-200 bg-white p-6 shadow-xl">
            <div class="mb-5 flex items-start justify-between gap-3">
                <div>
                    <h2 id="categories-modal-title" class="text-lg font-semibold text-slate-900">Manage Categories</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Group portfolio items (e.g. Web, Mobile, Case Studies).</p>
                </div>
                <button type="button" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600" data-modal-close="categories-modal" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="category-form" class="mb-5 flex gap-2" novalidate>
                <input type="hidden" id="category-id" value="">
                <input
                    type="text"
                    id="category-name"
                    maxlength="120"
                    required
                    class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                    placeholder="Category name"
                >
                <button type="submit" id="category-save-btn" class="shrink-0 rounded-xl bg-primary-token px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover-token disabled:opacity-70">
                    Add
                </button>
            </form>
            <p id="category-name-error" class="mb-3 hidden text-xs text-rose-600"></p>

            <ul id="categories-manage-list" class="space-y-2"></ul>

            <p id="categories-empty-hint" class="hidden rounded-xl border border-dashed border-slate-200 px-3 py-8 text-center text-sm text-slate-400">
                No categories yet.
            </p>
        </div>
    </div>
</div>
