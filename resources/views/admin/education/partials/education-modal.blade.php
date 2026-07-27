{{-- Education create / edit modal --}}
<div id="education-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="education-modal-title">
    <div class="absolute inset-0 bg-slate-900/40" data-modal-close="education-modal"></div>
    <div class="relative mx-auto flex min-h-full max-w-lg items-start p-4 sm:items-center">
        <div class="max-h-[90vh] w-full overflow-y-auto rounded-2xl border border-slate-200 bg-white p-6 shadow-xl">
            <div class="mb-5 flex items-start justify-between gap-3">
                <div>
                    <h2 id="education-modal-title" class="text-lg font-semibold text-slate-900">Add Education</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Degrees, diplomas, medical school, law school, and more.</p>
                </div>
                <button type="button" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600" data-modal-close="education-modal" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="education-form" class="space-y-4" novalidate>
                <input type="hidden" id="education-id" value="">

                <div>
                    <label for="education-degree" class="mb-1.5 block text-sm font-medium text-slate-700">Degree</label>
                    <input type="text" id="education-degree" maxlength="200" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                        placeholder="e.g. BSc Computer Science">
                    <p id="education-degree-error" class="mt-1 hidden text-xs text-rose-600"></p>
                </div>

                <div>
                    <label for="education-institution" class="mb-1.5 block text-sm font-medium text-slate-700">Institution</label>
                    <input type="text" id="education-institution" maxlength="200" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                        placeholder="e.g. University of Dhaka">
                    <p id="education-institution-error" class="mt-1 hidden text-xs text-rose-600"></p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label for="education-field" class="mb-1.5 block text-sm font-medium text-slate-700">Field of study</label>
                        <input type="text" id="education-field" maxlength="200"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                            placeholder="Optional">
                    </div>
                    <div>
                        <label for="education-location" class="mb-1.5 block text-sm font-medium text-slate-700">Location</label>
                        <input type="text" id="education-location" maxlength="200"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                            placeholder="Optional">
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label for="education-start-date" class="mb-1.5 block text-sm font-medium text-slate-700">Start date</label>
                        <input type="text" id="education-start-date" placeholder="Select start date" autocomplete="off"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2">
                    </div>
                    <div>
                        <label for="education-end-date" class="mb-1.5 block text-sm font-medium text-slate-700">End date</label>
                        <input type="text" id="education-end-date" placeholder="Select end date" autocomplete="off"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2">
                    </div>
                </div>

                <div class="flex items-center justify-between rounded-xl border border-slate-200 px-3.5 py-3">
                    <div>
                        <p class="text-sm font-medium text-slate-700">Currently studying</p>
                        <p class="text-xs text-slate-500">Hides end date and shows as Present.</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" id="education-is-current" class="peer sr-only">
                        <span class="h-6 w-11 rounded-full bg-slate-200 transition peer-checked:bg-primary-token after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition after:content-[''] peer-checked:after:translate-x-5"></span>
                    </label>
                </div>

                <div>
                    <label for="education-grade" class="mb-1.5 block text-sm font-medium text-slate-700">Grade</label>
                    <input type="text" id="education-grade" maxlength="100"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                        placeholder="e.g. 3.8 GPA / First Class">
                </div>

                <div>
                    <label for="education-description" class="mb-1.5 block text-sm font-medium text-slate-700">Description</label>
                    <textarea id="education-description" rows="3"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none ring-primary-token transition focus:border-primary-token focus:ring-2"
                        placeholder="Optional details…"></textarea>
                </div>

                <div class="flex items-center justify-between rounded-xl border border-slate-200 px-3.5 py-3">
                    <div>
                        <p class="text-sm font-medium text-slate-700">Active</p>
                        <p class="text-xs text-slate-500">Inactive entries stay hidden publicly.</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" id="education-is-active" class="peer sr-only" checked>
                        <span class="h-6 w-11 rounded-full bg-slate-200 transition peer-checked:bg-primary-token after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition after:content-[''] peer-checked:after:translate-x-5"></span>
                    </label>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-modal-close="education-modal" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" id="education-save-btn" class="rounded-xl bg-primary-token px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover-token disabled:opacity-70">
                        Save education
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
