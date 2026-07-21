<section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Profile header</h2>
            <p class="mt-1 text-sm text-slate-500">Avatar, name, title, and short bio.</p>
        </div>
        <div class="w-full sm:w-56">
            <div class="mb-1 flex items-center justify-between text-xs font-medium text-slate-500">
                <span>Completion</span>
                <span id="profile-completion-label">0%</span>
            </div>
            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                <div id="profile-completion-bar" class="h-full rounded-full bg-primary-token transition-all duration-300" style="width: 0%"></div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[auto_1fr]">
        <div class="flex flex-col items-center gap-3">
            <div class="relative">
                <img
                    id="avatar-preview"
                    src="https://ui-avatars.com/api/?name=User&background=0d9488&color=fff"
                    alt="Avatar preview"
                    class="h-28 w-28 rounded-2xl object-cover ring-2 ring-slate-100"
                >
                <label
                    for="avatar-input"
                    class="absolute -bottom-2 -right-2 inline-flex cursor-pointer items-center justify-center rounded-xl bg-primary-token px-2.5 py-1.5 text-xs font-semibold text-white shadow hover:bg-primary-hover-token"
                >
                    Upload
                </label>
                <input id="avatar-input" type="file" accept="image/*" class="hidden">
            </div>
            <p id="avatar-status" class="text-xs text-slate-400">JPG, PNG, WEBP · max 5MB</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="profile-name" class="mb-1.5 block text-sm font-medium text-slate-700">
                    Name <span class="text-rose-500">*</span>
                </label>
                <input id="profile-name" type="text" required class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-primary focus:ring-ring" placeholder="Your full name">
                <p data-error-for="name" class="mt-1 hidden text-sm text-rose-600"></p>
            </div>

            <div>
                <label for="profile-title" class="mb-1.5 block text-sm font-medium text-slate-700">
                    Profession / title <span class="text-rose-500">*</span>
                </label>
                <input id="profile-title" type="text" required class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-primary focus:ring-ring" placeholder="e.g. Full Stack Developer">
                <p data-error-for="title" class="mt-1 hidden text-sm text-rose-600"></p>
            </div>

            <div class="sm:col-span-2">
                <label for="profile-bio" class="mb-1.5 block text-sm font-medium text-slate-700">Short bio</label>
                <textarea id="profile-bio" rows="3" maxlength="2000" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-primary focus:ring-ring" placeholder="One or two sentences about you"></textarea>
            </div>
        </div>
    </div>
</section>
