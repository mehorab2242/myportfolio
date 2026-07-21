{{-- Category create / edit modal --}}
<div id="category-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="category-modal-title">
    <div class="absolute inset-0 bg-slate-900/40" data-modal-close="category-modal"></div>
    <div class="relative mx-auto flex min-h-full max-w-md items-center p-4">
        <div class="w-full rounded-2xl border border-slate-200 bg-white p-6 shadow-xl">
            <div class="mb-5 flex items-start justify-between gap-3">
                <div>
                    <h2 id="category-modal-title" class="text-lg font-semibold text-slate-900">Add Category</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Group related skills (e.g. Frontend, Clinical, Litigation).</p>
                </div>
                <button type="button" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600" data-modal-close="category-modal" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="category-form" class="space-y-4" novalidate>
                <input type="hidden" id="category-id" name="id" value="">
                <div>
                    <label for="category-name" class="mb-1.5 block text-sm font-medium text-slate-700">Name</label>
                    <input
                        type="text"
                        id="category-name"
                        name="name"
                        maxlength="120"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                        placeholder="e.g. Frontend"
                    >
                    <p id="category-name-error" class="mt-1 hidden text-xs text-rose-600"></p>
                </div>
                <div class="flex items-center justify-between rounded-xl border border-slate-200 px-3.5 py-3">
                    <div>
                        <p class="text-sm font-medium text-slate-700">Active</p>
                        <p class="text-xs text-slate-500">Inactive categories stay hidden on the public portfolio.</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" id="category-is-active" class="peer sr-only" checked>
                        <span class="h-6 w-11 rounded-full bg-slate-200 transition peer-checked:bg-primary-token after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition after:content-[''] peer-checked:after:translate-x-5"></span>
                    </label>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-modal-close="category-modal" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" id="category-save-btn" class="rounded-xl bg-primary-token px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover-token disabled:opacity-70">
                        Save category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
