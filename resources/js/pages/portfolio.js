import { api } from '../api';

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function formatDate(value) {
    if (! value) return '';
    const d = new Date(`${value}T00:00:00`);
    if (Number.isNaN(d.getTime())) return value;
    return d.toLocaleDateString(undefined, { year: 'numeric', month: 'short' });
}

function formatDuration(item) {
    const start = formatDate(item.start_date) || '—';
    const end = item.is_current ? 'Present' : (formatDate(item.end_date) || '—');
    return `${start} → ${end}`;
}

function formatLevel(skill) {
    const level = skill.level ?? '';
    if (skill.level_type === 'percentage') return level !== '' ? `${level}%` : '';
    if (skill.level_type === 'stars') {
        const n = Math.min(5, Math.max(0, Number(level) || 0));
        return '★'.repeat(n) + '☆'.repeat(5 - n);
    }
    return level;
}

function showError(message) {
    document.getElementById('portfolio-loading')?.classList.add('hidden');
    document.getElementById('portfolio-content')?.classList.add('hidden');
    const err = document.getElementById('portfolio-error');
    const msg = document.getElementById('portfolio-error-message');
    if (msg && message) msg.textContent = message;
    err?.classList.remove('hidden');
}

export async function initPortfolioPage() {
    const username = document.body.dataset.username;

    if (! username) {
        showError('Missing username.');
        return;
    }

    document.title = `${username} — Portfolio`;

    try {
        const { response, payload } = await api(`/portfolio/${encodeURIComponent(username)}`);

        if (response.status === 404 || ! payload.status) {
            showError(payload?.message || 'This portfolio could not be found.');
            return;
        }

        const data = payload.data;
        renderPortfolio(data);
    } catch {
        showError('Unable to load this portfolio.');
    }
}

function renderPortfolio(data) {
    const profile = data.profile || {};
    const name = profile.name || data.user?.name || data.user?.username || 'Portfolio';

    document.title = `${name} — Portfolio`;
    document.getElementById('portfolio-loading')?.classList.add('hidden');
    document.getElementById('portfolio-content')?.classList.remove('hidden');

    const avatar = document.getElementById('portfolio-avatar');
    if (avatar) {
        avatar.src = profile.avatar_url
            || `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=0d9488&color=fff`;
        avatar.alt = name;
    }

    if (profile.cover_image_url) {
        const cover = document.getElementById('portfolio-cover');
        if (cover) {
            cover.style.backgroundImage = `url(${profile.cover_image_url})`;
            cover.style.backgroundSize = 'cover';
            cover.style.backgroundPosition = 'center';
        }
    }

    document.getElementById('portfolio-name').textContent = name;
    document.getElementById('portfolio-title').textContent = profile.title || '';
    document.getElementById('portfolio-location').textContent = profile.location || '';
    document.getElementById('portfolio-bio').textContent = profile.bio || '';

    const links = document.getElementById('portfolio-links');
    const linkParts = [];
    if (profile.email) linkParts.push(`<a class="text-teal-200 underline" href="mailto:${escapeHtml(profile.email)}">${escapeHtml(profile.email)}</a>`);
    if (profile.phone) linkParts.push(`<span>${escapeHtml(profile.phone)}</span>`);
    if (profile.website) linkParts.push(`<a class="text-teal-200 underline" href="${escapeHtml(profile.website)}" target="_blank" rel="noopener">${escapeHtml(profile.website)}</a>`);
    (data.social_links || []).forEach((s) => {
        linkParts.push(`<a class="text-teal-200 underline" href="${escapeHtml(s.url)}" target="_blank" rel="noopener">${escapeHtml(s.platform)}</a>`);
    });
    if (links) links.innerHTML = linkParts.join('<span class="text-slate-500">·</span>');

    if (profile.about) {
        document.getElementById('section-about')?.classList.remove('hidden');
        document.getElementById('portfolio-about').textContent = profile.about;
    }

    const categories = data.skill_categories || [];
    if (categories.some((c) => (c.skills || []).length)) {
        document.getElementById('section-skills')?.classList.remove('hidden');
        document.getElementById('portfolio-skills').innerHTML = categories.map((cat) => `
            <div>
                <h3 class="text-sm font-semibold text-slate-800">${escapeHtml(cat.name)}</h3>
                <ul class="mt-2 space-y-1">
                    ${(cat.skills || []).map((s) => `
                        <li class="flex justify-between gap-3 text-sm text-slate-600">
                            <span>${escapeHtml(s.name)}${s.is_featured ? ' ★' : ''}</span>
                            <span class="text-slate-400">${escapeHtml(formatLevel(s))}</span>
                        </li>
                    `).join('')}
                </ul>
            </div>
        `).join('');
    }

    const items = data.portfolio_items || [];
    if (items.length) {
        document.getElementById('section-projects')?.classList.remove('hidden');
        document.getElementById('portfolio-projects').innerHTML = items.map((item) => `
            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                ${item.thumbnail_url
                    ? `<img src="${escapeHtml(item.thumbnail_url)}" alt="" class="aspect-[16/10] w-full object-cover">`
                    : '<div class="aspect-[16/10] bg-slate-100"></div>'}
                <div class="p-4">
                    <h3 class="text-sm font-semibold text-slate-900">${escapeHtml(item.title)}</h3>
                    <p class="mt-1 line-clamp-2 text-xs text-slate-500">${escapeHtml(item.short_description || item.description || '')}</p>
                    ${item.project_url ? `<a href="${escapeHtml(item.project_url)}" target="_blank" rel="noopener" class="mt-3 inline-block text-xs font-medium text-teal-700 hover:underline">View →</a>` : ''}
                </div>
            </article>
        `).join('');
    }

    const experiences = data.experiences || [];
    if (experiences.length) {
        document.getElementById('section-experience')?.classList.remove('hidden');
        document.getElementById('portfolio-experience').innerHTML = experiences.map((e) => `
            <li class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex flex-wrap items-center gap-2">
                    <h3 class="text-sm font-semibold text-slate-900">${escapeHtml(e.title)}</h3>
                    ${e.is_current ? '<span class="rounded-md bg-emerald-50 px-1.5 py-0.5 text-[10px] font-semibold uppercase text-emerald-700">Current</span>' : ''}
                </div>
                <p class="mt-0.5 text-sm text-slate-700">${escapeHtml(e.organization)}</p>
                <p class="mt-1 text-xs text-slate-500">${escapeHtml(formatDuration(e))}</p>
                ${e.description ? `<p class="mt-2 text-sm text-slate-600">${escapeHtml(e.description)}</p>` : ''}
            </li>
        `).join('');
    }

    const educations = data.educations || [];
    if (educations.length) {
        document.getElementById('section-education')?.classList.remove('hidden');
        document.getElementById('portfolio-education').innerHTML = educations.map((e) => `
            <li class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <h3 class="text-sm font-semibold text-slate-900">${escapeHtml(e.degree)}</h3>
                <p class="mt-0.5 text-sm text-slate-700">${escapeHtml(e.institution)}</p>
                <p class="mt-1 text-xs text-slate-500">${escapeHtml(formatDuration(e))}</p>
            </li>
        `).join('');
    }
}
