import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import { api } from '../api';
import { requireAuth } from '../auth';
import { initAdminShell } from './dashboard';
import { showToast } from '../toast';

/** @type {Array<object>} */
let educations = [];
/** @type {import('flatpickr').Instance|null} */
let startDatePicker = null;
/** @type {import('flatpickr').Instance|null} */
let endDatePicker = null;

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
    const startEl = document.getElementById('education-start-date');
    const endEl = document.getElementById('education-end-date');

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

function setEducationDates(start = '', end = '') {
    startDatePicker?.set('maxDate', end || null);
    endDatePicker?.set('minDate', start || null);
    startDatePicker?.setDate(start || null, false);
    endDatePicker?.setDate(end || null, false);
}

function getEducationDates() {
    return {
        start_date: startDatePicker?.input.value || null,
        end_date: endDatePicker?.input.value || null,
    };
}

function syncCurrentStudyingUI() {
    const isCurrent = document.getElementById('education-is-current')?.checked;
    const endEl = document.getElementById('education-end-date');

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
    const location = item.location
        ? `<p class="mt-1 text-xs text-slate-400">${escapeHtml(item.location)}</p>`
        : '';
    const field = item.field_of_study
        ? `<p class="mt-1 text-xs text-slate-500">${escapeHtml(item.field_of_study)}</p>`
        : '';
    const grade = item.grade
        ? `<p class="mt-2 text-xs text-slate-500">Grade: ${escapeHtml(item.grade)}</p>`
        : '';
    const description = item.description
        ? `<p class="mt-2 line-clamp-3 text-sm text-slate-600">${escapeHtml(item.description)}</p>`
        : '';
    const isLast = index === total - 1;

    return `
        <li class="relative flex gap-4" draggable="true" data-education-id="${item.id}">
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
                            <h3 class="text-sm font-semibold text-slate-900">${escapeHtml(item.degree)}</h3>
                            ${currentBadge}${badge}
                        </div>
                        <p class="mt-0.5 text-sm text-slate-700">${escapeHtml(item.institution)}</p>
                        ${field}
                        <p class="mt-2 text-xs font-medium text-slate-500">${escapeHtml(formatDuration(item))}</p>
                        ${location}
                        ${grade}
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
    const loading = document.getElementById('education-loading');
    const empty = document.getElementById('education-empty');
    const list = document.getElementById('education-timeline');

    loading?.classList.add('hidden');

    if (! educations.length) {
        empty?.classList.remove('hidden');
        list?.classList.add('hidden');
        if (list) list.innerHTML = '';
        return;
    }

    empty?.classList.add('hidden');
    list?.classList.remove('hidden');
    list.innerHTML = educations.map((item, i) => renderTimelineItem(item, i, educations.length)).join('');
    bindDragAndDrop();
}

async function loadEducations() {
    const { response, payload } = await api('/educations');

    if (! response.ok || ! payload.status) {
        showToast(payload?.message || 'Failed to load education.', 'error');
        document.getElementById('education-loading')?.classList.add('hidden');
        return;
    }

    educations = Array.isArray(payload.data) ? payload.data : [];
    render();
}

function findEducation(id) {
    return educations.find((e) => String(e.id) === String(id));
}

function openEducationModal(item = null) {
    document.getElementById('education-modal-title').textContent = item ? 'Edit Education' : 'Add Education';
    document.getElementById('education-id').value = item?.id ?? '';
    document.getElementById('education-degree').value = item?.degree ?? '';
    document.getElementById('education-institution').value = item?.institution ?? '';
    document.getElementById('education-field').value = item?.field_of_study ?? '';
    document.getElementById('education-location').value = item?.location ?? '';
    document.getElementById('education-is-current').checked = Boolean(item?.is_current);
    setEducationDates(item?.start_date ?? '', item?.is_current ? '' : (item?.end_date ?? ''));
    syncCurrentStudyingUI();
    document.getElementById('education-grade').value = item?.grade ?? '';
    document.getElementById('education-description').value = item?.description ?? '';
    document.getElementById('education-is-active').checked = item ? Boolean(item.is_active) : true;
    setFieldError('education-degree-error', '');
    setFieldError('education-institution-error', '');
    openModal('education-modal');
    document.getElementById('education-degree')?.focus();
}

async function saveEducation(event) {
    event.preventDefault();

    const id = document.getElementById('education-id').value;
    const degree = document.getElementById('education-degree').value.trim();
    const institution = document.getElementById('education-institution').value.trim();
    const is_current = document.getElementById('education-is-current').checked;
    const dates = getEducationDates();

    let valid = true;

    if (! degree) {
        setFieldError('education-degree-error', 'Degree is required.');
        valid = false;
    } else {
        setFieldError('education-degree-error', '');
    }

    if (! institution) {
        setFieldError('education-institution-error', 'Institution is required.');
        valid = false;
    } else {
        setFieldError('education-institution-error', '');
    }

    if (! valid) {
        return;
    }

    const body = {
        degree,
        institution,
        field_of_study: document.getElementById('education-field').value.trim() || null,
        location: document.getElementById('education-location').value.trim() || null,
        start_date: dates.start_date,
        end_date: is_current ? null : dates.end_date,
        is_current,
        grade: document.getElementById('education-grade').value.trim() || null,
        description: document.getElementById('education-description').value.trim() || null,
        is_active: document.getElementById('education-is-active').checked,
    };

    const btn = document.getElementById('education-save-btn');
    btn.disabled = true;

    try {
        const { response, payload } = await api(id ? `/educations/${id}` : '/educations', {
            method: id ? 'PUT' : 'POST',
            body: JSON.stringify(body),
        });

        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Could not save education.', 'error');
            return;
        }

        closeModal('education-modal');
        showToast(payload.message || 'Education saved.');
        await loadEducations();
    } catch {
        showToast('Could not save education.', 'error');
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
        openEducationModal(findEducation(id));
        return;
    }

    if (action === 'toggle') {
        const { response, payload } = await api(`/educations/${id}/toggle`, { method: 'PATCH' });
        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Toggle failed.', 'error');
            return;
        }
        showToast(payload.message || 'Updated.');
        await loadEducations();
        return;
    }

    if (action === 'delete') {
        if (! window.confirm('Delete this education entry?')) return;
        const { response, payload } = await api(`/educations/${id}`, { method: 'DELETE' });
        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Delete failed.', 'error');
            return;
        }
        showToast(payload.message || 'Education deleted.');
        await loadEducations();
    }
}

function bindDragAndDrop() {
    const list = document.getElementById('education-timeline');
    if (! list) return;

    let dragEl = null;

    list.querySelectorAll('[data-education-id]').forEach((row) => {
        row.addEventListener('dragstart', (e) => {
            if (! e.target.closest('[data-drag-handle]')) {
                e.preventDefault();
                return;
            }
            dragEl = row;
            row.classList.add('opacity-70');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', `edu:${row.dataset.educationId}`);
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
        if (! raw.startsWith('edu:')) return;
        e.preventDefault();

        const ids = [...list.querySelectorAll('[data-education-id]')].map((el) => Number(el.dataset.educationId));
        const { response, payload } = await api('/educations/reorder', {
            method: 'PATCH',
            body: JSON.stringify({ ids }),
        });

        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Reorder failed.', 'error');
            await loadEducations();
            return;
        }

        educations = Array.isArray(payload.data) ? payload.data : educations;
        render();
        showToast('Education reordered.');
    });
}

export async function initEducationPage() {
    if (! requireAuth()) return;

    initAdminShell();
    initDatePickers();

    document.querySelectorAll('[data-modal-close]').forEach((el) => {
        el.addEventListener('click', () => closeModal(el.dataset.modalClose));
    });

    document.getElementById('add-education-btn')?.addEventListener('click', () => openEducationModal());
    document.getElementById('empty-add-education-btn')?.addEventListener('click', () => openEducationModal());
    document.getElementById('education-form')?.addEventListener('submit', saveEducation);
    document.getElementById('education-is-current')?.addEventListener('change', syncCurrentStudyingUI);
    document.getElementById('education-timeline')?.addEventListener('click', handleListClick);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal('education-modal');
        }
    });

    await loadEducations();
}
