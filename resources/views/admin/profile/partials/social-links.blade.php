@php
    $platforms = ['linkedin', 'github', 'facebook', 'twitter', 'instagram', 'youtube', 'dribbble', 'behance', 'other'];
@endphp

<section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Social links</h2>
            <p class="mt-1 text-sm text-slate-500">Add platforms you want to show on your portfolio.</p>
        </div>
        <button
            type="button"
            id="add-social-link"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
        >
            + Add link
        </button>
    </div>

    <div id="social-links-list" class="space-y-3"></div>
    <p id="social-links-empty" class="text-sm text-slate-400">No social links yet.</p>
    <p data-error-for="social" class="mt-2 hidden text-sm text-rose-600"></p>

    <template id="social-link-template">
        <div class="social-link-row grid gap-3 rounded-xl border border-slate-100 bg-slate-50/80 p-3 sm:grid-cols-[160px_1fr_auto]" data-id="">
            <select class="social-platform rounded-lg border-slate-200 bg-white text-sm focus:border-primary focus:ring-ring">
                @foreach ($platforms as $platform)
                    <option value="{{ $platform }}">{{ ucfirst($platform) }}</option>
                @endforeach
            </select>
            <input type="url" class="social-url rounded-lg border-slate-200 bg-white text-sm focus:border-primary focus:ring-ring" placeholder="https://...">
            <button type="button" class="remove-social-link inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                Remove
            </button>
        </div>
    </template>
</section>
