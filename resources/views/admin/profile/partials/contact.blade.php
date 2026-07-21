<section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-slate-900">Contact info</h2>
        <p class="mt-1 text-sm text-slate-500">How visitors can reach you. Toggle public visibility where available.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <div class="mb-1.5 flex items-center justify-between gap-3">
                <label for="profile-email" class="text-sm font-medium text-slate-700">Email</label>
                <label class="inline-flex items-center gap-2 text-xs text-slate-500">
                    <input id="email-public" type="checkbox" class="rounded border-slate-300 text-primary focus:ring-ring">
                    Public
                </label>
            </div>
            <input id="profile-email" type="email" readonly class="block w-full cursor-not-allowed rounded-xl border-slate-200 bg-slate-100 text-slate-600" placeholder="Loaded from account">
            <p class="mt-1 text-xs text-slate-400">Uses your login email. Visibility only.</p>
        </div>

        <div>
            <div class="mb-1.5 flex items-center justify-between gap-3">
                <label for="profile-phone" class="text-sm font-medium text-slate-700">Phone</label>
                <label class="inline-flex items-center gap-2 text-xs text-slate-500">
                    <input id="phone-public" type="checkbox" class="rounded border-slate-300 text-primary focus:ring-ring">
                    Public
                </label>
            </div>
            <input id="profile-phone" type="text" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-primary focus:ring-ring" placeholder="+8801...">
        </div>

        <div>
            <label for="profile-location" class="mb-1.5 block text-sm font-medium text-slate-700">Location</label>
            <input id="profile-location" type="text" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-primary focus:ring-ring" placeholder="City, Country">
        </div>

        <div>
            <label for="profile-website" class="mb-1.5 block text-sm font-medium text-slate-700">Website</label>
            <input id="profile-website" type="url" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-primary focus:ring-ring" placeholder="https://example.com">
            <p data-error-for="website" class="mt-1 hidden text-sm text-rose-600"></p>
        </div>
    </div>
</section>
