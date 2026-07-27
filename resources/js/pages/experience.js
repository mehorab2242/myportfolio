import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import { api } from '../api';
import { requireAuth } from '../auth';
import { initAdminShell } from './dashboard';
import { showToast } from '../toast';

/** @type {Array<object>} */
let experiences = [];
/** @type {import('flatpickr').Instance|null} */
let startDatePicker = null;
/** @type {import('flatpickr').Instance|null} */
let endDatePicker = null;

const EMPLOYMENT_LABELS = {
    full_time: 'Full-time',
    part_time: 'Part-time',
    freelance: 'Freelance',
    internship: 'Internship',
    contract: 'Contract',
    other: 'Other',
};

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function formatDateLabel(value) {
    if (! value) {
        return '';
    }

    const d = new Date(`${value}T00:00:00`);
    if (Number.isNaN(d.getTime())) {
        return value;
    }

    return d.toLocaleDateString(undefined, { year: 'numeric', month: 'short' });
}

function formatDuration(item) {
    const start = formatDateLabel(item.start_date) || '—';
    const end = item.is_current ? 'Present' : (formatDateLabel(item.end_date) || '—');
    return `${start} → ${end}`;
}

function openModal(id) {
    document.getElementById(id)?.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal(id) {
    document.getElementById(id)?.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function setFieldError(id, message) {
    const el = document.getElementById(id);
    if (! el) return;

    if (message) {
        el.textContent = message;
        el.classList.remove('hidden');
    } else {
        el.textContent = '';
        el.classList.add('hidden');
    }
}

function initDatePickers() {
    const startEl = document.getElementById('experience-start-date');
    const endEl = document.getElementById('experience-end-date');

    if (! startEl || ! endEl || startDatePicker) {
        return;
    }

    startDatePicker = flatpickr(startEl, {
        dateFormat: 'Y-m-d',
        allowInput: false,
        disableMobile: true,
        onChange(selectedDates) {
            if (endDatePicker) {
                endDatePicker.set('minDate', selectedDates[0] || null);
            }
        },
    });

    endDatePicker = flatpickr(endEl, {
        dateFormat: 'Y-m-d',
        allowInput: false,
        disableMobile: true,
        onChange(selectedDates) {
            if (startDatePicker) {
                startDatePicker.set('maxDate', selectedDates[0] || null);
            }
        },
    });
}

function setExperienceDates(start = '', end = '') {
    startDatePicker?.set('maxDate', end || null);
    endDatePicker?.set('minDate', start || null);
    startDatePicker?.setDate(start || null, false);
    endDatePicker?.setDate(end || null, false);
}

function getExperienceDates() {
    return {
        start_date: startDatePicker?.input.value || null,
        end_date: endDatePicker?.input.value || null,
    };
}

function syncCurrentWorkingUI() {
    const isCurrent = document.getElementById('experience-is-current')?.checked;
    const endEl = document.getElementById('experience-end-date');

    if (! endEl || ! endDatePicker) {
        return;
    }

    if (isCurrent) {
        endDatePicker.clear();
        endDatePicker.set('maxDate', null);
        endEl.disabled = true;
        endEl.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        endEl.disabled = false;
        endEl.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

function renderTimelineItem(item, index, total) {
    const inactive = item.is_active ? '' : 'opacity-55';
    const badge = item.is_active
        ? ''
        : '<span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-500">Inactive</span>';
    const currentBadge = item.is_current
        ? '<span class="rounded-md bg-emerald-50 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-emerald-700">Current</span>'
        : '';
    const typeLabel = EMPLOYMENT_LABELS[item.employment_type] || item.employment_type;
    const location = item.location
        ? `<p class="mt-1 text-xs text-slate-400">${escapeHtml(item.location)}</p>`
        : '';
    const description = item.description
        ? `<p class="mt-2 line-clamp-3 text-sm text-slate-600">${escapeHtml(item.description)}</p>`
        : '';
    const isLast = index === total - 1;

    return `
        <li class="relative flex gap-4" draggable="true" data-experience-id="${item.id}">
            <div class="flex w-4 shrink-0 flex-col items-center">
                <span class="mt-5 h-3 w-3 rounded-full border-2 border-primary-token bg-white ring-4 ring-white"></span>
                ${isLast ? '' : '<span class="w-px flex-1 bg-slate-200"></span>'}
            </div>
            <article class="mb-4 min-w-0 flex-1 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md ${inactive}">
                <div class="flex items-start gap-3">
                    <button type="button" class="mt-0.5 cursor-grab text-slate-300 hover:text-slate-500" data-drag-handle title="Drag to reorder" aria-label="Drag">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16"/></svg>
                    </button>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-sm font-semibold text-slate-900">${escapeHtml(item.title)}</h3>
                            ${currentBadge}${badge}
                        </div>
                        <p class="mt-0.5 text-sm text-slate-700">${escapeHtml(item.organization)}</p>
                        <p class="mt-2 text-xs font-medium text-slate-500">${escapeHtml(formatDuration(item))} · ${escapeHtml(typeLabel)}</p>
                        ${location}
                        ${description}
                    </div>
                    <div class="flex shrink-0 flex-col gap-1 sm:flex-row">
                        <button type="button" class="rounded-lg px-2 py-1 text-xs font-medium text-slate-600 hover:bg-slate-50" data-action="edit" data-id="${item.id}">Edit</button>
                        <button type="button" class="rounded-lg px-2 py-1 text-xs font-medium text-slate-600 hover:bg-slate-50" data-action="toggle" data-id="${item.id}">${item.is_active ? 'Hide' : 'Show'}</button>
                        <button type="button" class="rounded-lg px-2 py-1 text-xs font-medium text-rose-600 hover:bg-rose-50" data-action="delete" data-id="${item.id}">Delete</button>
                    </div>
                </div>
            </article>
        </li>
    `;
}

function render() {
    const loading = document.getElementById('experience-loading');
    const empty = document.getElementById('experience-empty');
    const list = document.getElementById('experience-timeline');

    loading?.classList.add('hidden');

    if (! experiences.length) {
        empty?.classList.remove('hidden');
        list?.classList.add('hidden');
        if (list) list.innerHTML = '';
        return;
    }

    empty?.classList.add('hidden');
    list?.classList.remove('hidden');
    list.innerHTML = experiences.map((item, i) => renderTimelineItem(item, i, experiences.length)).join('');
    bindDragAndDrop();
}

async function loadExperiences() {
    const { response, payload } = await api('/experiences');

    if (! response.ok || ! payload.status) {
        showToast(payload?.message || 'Failed to load experience.', 'error');
        document.getElementById('experience-loading')?.classList.add('hidden');
        return;
    }

    experiences = Array.isArray(payload.data) ? payload.data : [];
    render();
}

function findExperience(id) {
    return experiences.find((e) => String(e.id) === String(id));
}

function openExperienceModal(item = null) {
    document.getElementById('experience-modal-title').textContent = item ? 'Edit Experience' : 'Add Experience';
    document.getElementById('experience-id').value = item?.id ?? '';
    document.getElementById('experience-title').value = item?.title ?? '';
    document.getElementById('experience-organization').value = item?.organization ?? '';
    document.getElementById('experience-employment-type').value = item?.employment_type ?? 'full_time';
    document.getElementById('experience-location').value = item?.location ?? '';
    document.getElementById('experience-is-current').checked = Boolean(item?.is_current);
    setExperienceDates(item?.start_date ?? '', item?.is_current ? '' : (item?.end_date ?? ''));
    syncCurrentWorkingUI();
    document.getElementById('experience-description').value = item?.description ?? '';
    document.getElementById('experience-is-active').checked = item ? Boolean(item.is_active) : true;
    setFieldError('experience-title-error', '');
    setFieldError('experience-organization-error', '');
    openModal('experience-modal');
    document.getElementById('experience-title')?.focus();
}

async function saveExperience(event) {
    event.preventDefault();

    const id = document.getElementById('experience-id').value;
    const title = document.getElementById('experience-title').value.trim();
    const organization = document.getElementById('experience-organization').value.trim();
    const is_current = document.getElementById('experience-is-current').checked;
    const dates = getExperienceDates();

    let valid = true;

    if (! title) {
        setFieldError('experience-title-error', 'Title is required.');
        valid = false;
    } else {
        setFieldError('experience-title-error', '');
    }

    if (! organization) {
        setFieldError('experience-organization-error', 'Organization is required.');
        valid = false;
    } else {
        setFieldError('experience-organization-error', '');
    }

    if (! valid) {
        return;
    }

    const body = {
        title,
        organization,
        employment_type: document.getElementById('experience-employment-type').value,
        location: document.getElementById('experience-location').value.trim() || null,
        start_date: dates.start_date,
        end_date: is_current ? null : dates.end_date,
        is_current,
        description: document.getElementById('experience-description').value.trim() || null,
        is_active: document.getElementById('experience-is-active').checked,
    };

    const btn = document.getElementById('experience-save-btn');
    btn.disabled = true;

    try {
        const { response, payload } = await api(id ? `/experiences/${id}` : '/experiences', {
            method: id ? 'PUT' : 'POST',
            body: JSON.stringify(body),
        });

        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Could not save experience.', 'error');
            return;
        }

        closeModal('experience-modal');
        showToast(payload.message || 'Experience saved.');
        await loadExperiences();
    } catch {
        showToast('Could not save experience.', 'error');
    } finally {
        btn.disabled = false;
    }
}

async function handleListClick(event) {
    const btn = event.target.closest('[data-action]');
    if (! btn) return;

    const action = btn.dataset.action;
    const id = btn.dataset.id;

    if (action === 'edit') {
        openExperienceModal(findExperience(id));
        return;
    }

    if (action === 'toggle') {
        const { response, payload } = await api(`/experiences/${id}/toggle`, { method: 'PATCH' });
        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Toggle failed.', 'error');
            return;
        }
        showToast(payload.message || 'Updated.');
        await loadExperiences();
        return;
    }

    if (action === 'delete') {
        if (! window.confirm('Delete this experience entry?')) return;
        const { response, payload } = await api(`/experiences/${id}`, { method: 'DELETE' });
        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Delete failed.', 'error');
            return;
        }
        showToast(payload.message || 'Experience deleted.');
        await loadExperiences();
    }
}

function bindDragAndDrop() {
    const list = document.getElementById('experience-timeline');
    if (! list) return;

    let dragEl = null;

    list.querySelectorAll('[data-experience-id]').forEach((row) => {
        row.addEventListener('dragstart', (e) => {
            if (! e.target.closest('[data-drag-handle]')) {
                e.preventDefault();
                return;
            }
            dragEl = row;
            row.classList.add('opacity-70');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', `exp:${row.dataset.experienceId}`);
        });

        row.addEventListener('dragend', () => {
            row.classList.remove('opacity-70');
            dragEl = null;
        });

        row.addEventListener('dragover', (e) => {
            if (! dragEl) return;
            e.preventDefault();
            if (row !== dragEl) {
                const rect = row.getBoundingClientRect();
                const before = e.clientY < rect.top + rect.height / 2;
                list.insertBefore(dragEl, before ? row : row.nextSibling);
            }
        });
    });

    list.addEventListener('drop', async (e) => {
        const raw = e.dataTransfer.getData('text/plain');
        if (! raw.startsWith('exp:')) return;
        e.preventDefault();

        const ids = [...list.querySelectorAll('[data-experience-id]')].map((el) => Number(el.dataset.experienceId));
        const { response, payload } = await api('/experiences/reorder', {
            method: 'PATCH',
            body: JSON.stringify({ ids }),
        });

        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Reorder failed.', 'error');
            await loadExperiences();
            return;
        }

        experiences = Array.isArray(payload.data) ? payload.data : experiences;
        render();
        showToast('Experience reordered.');
    });
}

export async function initExperiencePage() {
    if (! requireAuth()) return;

    initAdminShell();
    initDatePickers();

    document.querySelectorAll('[data-modal-close]').forEach((el) => {
        el.addEventListener('click', () => closeModal(el.dataset.modalClose));
    });

    document.getElementById('add-experience-btn')?.addEventListener('click', () => openExperienceModal());
    document.getElementById('empty-add-experience-btn')?.addEventListener('click', () => openExperienceModal());
    document.getElementById('experience-form')?.addEventListener('submit', saveExperience);
    document.getElementById('experience-is-current')?.addEventListener('change', syncCurrentWorkingUI);
    document.getElementById('experience-timeline')?.addEventListener('click', handleListClick);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal('experience-modal');
        }
    });

    await loadExperiences();
}
