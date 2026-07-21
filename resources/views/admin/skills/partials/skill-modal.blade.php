{{-- Skill create / edit modal --}}
<div id="skill-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="skill-modal-title">
    <div class="absolute inset-0 bg-slate-900/40" data-modal-close="skill-modal"></div>
    <div class="relative mx-auto flex min-h-full max-w-md items-center p-4">
        <div class="w-full rounded-2xl border border-slate-200 bg-white p-6 shadow-xl">
            <div class="mb-5 flex items-start justify-between gap-3">
                <div>
                    <h2 id="skill-modal-title" class="text-lg font-semibold text-slate-900">Add Skill</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Attach a skill to a category with a flexible level format.</p>
                </div>
                <button type="button" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600" data-modal-close="skill-modal" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="skill-form" class="space-y-4" novalidate>
                <input type="hidden" id="skill-id" name="id" value="">

                <div>
                    <label for="skill-category-id" class="mb-1.5 block text-sm font-medium text-slate-700">Category</label>
                    <select
                        id="skill-category-id"
                        name="category_id"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                    >
                        <option value="">Select category…</option>
                    </select>
                    <p id="skill-category-error" class="mt-1 hidden text-xs text-rose-600"></p>
                </div>

                <div>
                    <label for="skill-name" class="mb-1.5 block text-sm font-medium text-slate-700">Name</label>
                    <input
                        type="text"
                        id="skill-name"
                        name="name"
                        maxlength="120"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                        placeholder="e.g. React"
                    >
                    <p id="skill-name-error" class="mt-1 hidden text-xs text-rose-600"></p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="skill-level-type" class="mb-1.5 block text-sm font-medium text-slate-700">Level type</label>
                        <select
                            id="skill-level-type"
                            name="level_type"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                        >
                            <option value="percentage">Percentage</option>
                            <option value="text">Text</option>
                            <option value="stars">Stars (1–5)</option>
                        </select>
                    </div>
                    <div>
                        <label for="skill-level" class="mb-1.5 block text-sm font-medium text-slate-700">Level</label>
                        <input
                            type="text"
                            id="skill-level"
                            name="level"
                            maxlength="50"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                            placeholder="90"
                        >
                        <p id="skill-level-error" class="mt-1 hidden text-xs text-rose-600"></p>
                    </div>
                </div>

                <div class="flex items-center justify-between rounded-xl border border-slate-200 px-3.5 py-3">
                    <div>
                        <p class="text-sm font-medium text-slate-700">Featured</p>
                        <p class="text-xs text-slate-500">Highlight on the public portfolio later.</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" id="skill-is-featured" class="peer sr-only">
                        <span class="h-6 w-11 rounded-full bg-slate-200 transition peer-checked:bg-primary-token after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition after:content-[''] peer-checked:after:translate-x-5"></span>
                    </label>
                </div>

                <div class="flex items-center justify-between rounded-xl border border-slate-200 px-3.5 py-3">
                    <div>
                        <p class="text-sm font-medium text-slate-700">Active</p>
                        <p class="text-xs text-slate-500">Inactive skills stay hidden publicly.</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" id="skill-is-active" class="peer sr-only" checked>
                        <span class="h-6 w-11 rounded-full bg-slate-200 transition peer-checked:bg-primary-token after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition after:content-[''] peer-checked:after:translate-x-5"></span>
                    </label>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-modal-close="skill-modal" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" id="skill-save-btn" class="rounded-xl bg-primary-token px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover-token disabled:opacity-70">
                        Save skill
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
