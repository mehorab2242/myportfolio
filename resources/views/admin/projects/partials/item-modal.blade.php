{{-- Portfolio item create / edit modal --}}
<div id="item-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="item-modal-title">
    <div class="absolute inset-0 bg-slate-900/40" data-modal-close="item-modal"></div>
    <div class="relative mx-auto flex min-h-full max-w-2xl items-start p-4 sm:items-center">
        <div class="max-h-[90vh] w-full overflow-y-auto rounded-2xl border border-slate-200 bg-white p-6 shadow-xl">
            <div class="mb-5 flex items-start justify-between gap-3">
                <div>
                    <h2 id="item-modal-title" class="text-lg font-semibold text-slate-900">Add Item</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Works for projects, case studies, works, or publications.</p>
                </div>
                <button type="button" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600" data-modal-close="item-modal" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="item-form" class="space-y-4" novalidate>
                <input type="hidden" id="item-id" value="">

                <div>
                    <label for="item-title" class="mb-1.5 block text-sm font-medium text-slate-700">Title</label>
                    <input type="text" id="item-title" maxlength="200" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                        placeholder="e.g. Portfolio redesign">
                    <p id="item-title-error" class="mt-1 hidden text-xs text-rose-600"></p>
                </div>

                <div>
                    <label for="item-short-description" class="mb-1.5 block text-sm font-medium text-slate-700">Short description</label>
                    <input type="text" id="item-short-description" maxlength="300"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                        placeholder="One-line summary for cards">
                </div>

                <div>
                    <label for="item-description" class="mb-1.5 block text-sm font-medium text-slate-700">Description</label>
                    <textarea id="item-description" rows="4"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                        placeholder="Full details…"></textarea>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label for="item-category-id" class="mb-1.5 block text-sm font-medium text-slate-700">Category</label>
                        <select id="item-category-id"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2">
                            <option value="">None</option>
                        </select>
                    </div>
                    <div>
                        <label for="item-client-name" class="mb-1.5 block text-sm font-medium text-slate-700">Client</label>
                        <input type="text" id="item-client-name" maxlength="150"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                            placeholder="Optional">
                    </div>
                </div>

                <div>
                    <label for="item-project-url" class="mb-1.5 block text-sm font-medium text-slate-700">URL</label>
                    <input type="url" id="item-project-url" maxlength="255"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                        placeholder="https://…">
                    <p id="item-url-error" class="mt-1 hidden text-xs text-rose-600"></p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label for="item-start-date" class="mb-1.5 block text-sm font-medium text-slate-700">Start date</label>
                        <input type="text" id="item-start-date" placeholder="Select start date" autocomplete="off"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2">
                    </div>
                    <div>
                        <label for="item-end-date" class="mb-1.5 block text-sm font-medium text-slate-700">End date</label>
                        <input type="text" id="item-end-date" placeholder="Select end date" autocomplete="off"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2">
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="flex items-center justify-between rounded-xl border border-slate-200 px-3.5 py-3">
                        <div>
                            <p class="text-sm font-medium text-slate-700">Featured</p>
                            <p class="text-xs text-slate-500">Highlight on public portfolio.</p>
                        </div>
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input type="checkbox" id="item-is-featured" class="peer sr-only">
                            <span class="h-6 w-11 rounded-full bg-slate-200 transition peer-checked:bg-primary-token after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition after:content-[''] peer-checked:after:translate-x-5"></span>
                        </label>
                    </div>
                    <div class="flex items-center justify-between rounded-xl border border-slate-200 px-3.5 py-3">
                        <div>
                            <p class="text-sm font-medium text-slate-700">Active</p>
                            <p class="text-xs text-slate-500">Inactive items stay hidden.</p>
                        </div>
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input type="checkbox" id="item-is-active" class="peer sr-only" checked>
                            <span class="h-6 w-11 rounded-full bg-slate-200 transition peer-checked:bg-primary-token after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition after:content-[''] peer-checked:after:translate-x-5"></span>
                        </label>
                    </div>
                </div>

                <div id="item-media-section" class="hidden space-y-3 rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between gap-2">
                        <div>
                            <p class="text-sm font-medium text-slate-700">Images</p>
                            <p class="text-xs text-slate-500">Select images to upload. Drag to reorder saved images.</p>
                        </div>
                        <label class="cursor-pointer rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">
                            Upload
                            <input type="file" id="item-media-input" class="hidden" accept="image/*" multiple>
                        </label>
                    </div>
                    <div id="item-media-list" class="grid grid-cols-3 gap-2 sm:grid-cols-4"></div>
                    <p id="item-media-status" class="text-xs text-slate-500"></p>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-modal-close="item-modal" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" id="item-save-btn" class="rounded-xl bg-primary-token px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover-token disabled:opacity-70">
                        Save item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
