<section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Professional meta</h2>
            <p class="mt-1 text-sm text-slate-500">Custom key/value fields (e.g. specialization, github_username).</p>
        </div>
        <button
            type="button"
            id="add-meta-field"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
        >
            + Add field
        </button>
    </div>

    <div id="meta-fields-list" class="space-y-3"></div>
    <p id="meta-fields-empty" class="text-sm text-slate-400">No custom fields yet.</p>
    <p data-error-for="meta" class="mt-2 hidden text-sm text-rose-600"></p>

    <template id="meta-field-template">
        <div class="meta-field-row grid gap-3 rounded-xl border border-slate-100 bg-slate-50/80 p-3 sm:grid-cols-[1fr_1fr_auto]">
            <input type="text" class="meta-key rounded-lg border-slate-200 bg-white font-mono text-sm focus:border-primary focus:ring-ring" placeholder="key_name">
            <input type="text" class="meta-value rounded-lg border-slate-200 bg-white text-sm focus:border-primary focus:ring-ring" placeholder="Value">
            <button type="button" class="remove-meta-field inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                Remove
            </button>
        </div>
    </template>
</section>
