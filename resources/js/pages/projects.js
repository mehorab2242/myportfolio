import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import { api } from '../api';
import { requireAuth } from '../auth';
import { initAdminShell } from './dashboard';
import { showToast } from '../toast';

/** @type {Array<object>} */
let items = [];
/** @type {Array<object>} */
let categories = [];
/** @type {Array<object>} */
let editingMedia = [];
/** @type {Array<File>} */
let pendingFiles = [];
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

function initDatePickers() {
    const startEl = document.getElementById('item-start-date');
    const endEl = document.getElementById('item-end-date');

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

function setItemDates(start = '', end = '') {
    startDatePicker?.set('maxDate', end || null);
    endDatePicker?.set('minDate', start || null);
    startDatePicker?.setDate(start || null, false);
    endDatePicker?.setDate(end || null, false);
}

function getItemDates() {
    return {
        start_date: startDatePicker?.input.value || null,
        end_date: endDatePicker?.input.value || null,
    };
}

function openModal(id) {
    document.getElementById(id)?.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal(id) {
    document.getElementById(id)?.classList.add('hidden');
    if (! document.querySelector('[role="dialog"]:not(.hidden)')) {
        document.body.classList.remove('overflow-hidden');
    }
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

function fillCategorySelect(selectedId = '') {
    const select = document.getElementById('item-category-id');
    if (! select) return;

    select.innerHTML = '<option value="">None</option>'
        + categories.map((c) => (
            `<option value="${c.id}" ${String(c.id) === String(selectedId) ? 'selected' : ''}>${escapeHtml(c.name)}</option>`
        )).join('');
}

function renderItemCard(item) {
    const inactive = item.is_active ? '' : 'opacity-60';
    const featured = item.is_featured
        ? '<span class="rounded-md bg-amber-50 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-amber-700">Featured</span>'
        : '';
    const badge = item.is_active
        ? ''
        : '<span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-500">Inactive</span>';
    const thumb = item.thumbnail_url
        ? `<img src="${escapeHtml(item.thumbnail_url)}" alt="" class="h-full w-full object-cover">`
        : `<div class="flex h-full w-full items-center justify-center bg-slate-100 text-slate-400">
            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
           </div>`;
    const categoryName = item.category?.name
        ? `<p class="mt-1 text-xs text-slate-400">${escapeHtml(item.category.name)}</p>`
        : '';

    return `
        <article
            class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md ${inactive}"
            draggable="true"
            data-item-id="${item.id}"
        >
            <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                ${thumb}
                <button type="button" class="absolute left-2 top-2 cursor-grab rounded-lg bg-white/90 p-1.5 text-slate-400 shadow-sm hover:text-slate-600" data-drag-handle title="Drag to reorder" aria-label="Drag">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16"/></svg>
                </button>
            </div>
            <div class="flex flex-1 flex-col p-4">
                <div class="flex flex-wrap items-center gap-2">
                    <h3 class="truncate text-sm font-semibold text-slate-900">${escapeHtml(item.title)}</h3>
                    ${featured}${badge}
                </div>
                ${categoryName}
                <p class="mt-2 line-clamp-2 flex-1 text-xs text-slate-500">${escapeHtml(item.short_description || item.description || 'No description')}</p>
                <div class="mt-3 flex flex-wrap gap-1 border-t border-slate-100 pt-3">
                    <button type="button" class="rounded-lg px-2 py-1 text-xs font-medium text-slate-600 hover:bg-slate-50" data-action="edit-item" data-id="${item.id}">Edit</button>
                    <button type="button" class="rounded-lg px-2 py-1 text-xs font-medium text-slate-600 hover:bg-slate-50" data-action="toggle-item" data-id="${item.id}">${item.is_active ? 'Hide' : 'Show'}</button>
                    <button type="button" class="rounded-lg px-2 py-1 text-xs font-medium text-rose-600 hover:bg-rose-50" data-action="delete-item" data-id="${item.id}">Delete</button>
                </div>
            </div>
        </article>
    `;
}

function renderItems() {
    const loading = document.getElementById('projects-loading');
    const empty = document.getElementById('projects-empty');
    const grid = document.getElementById('items-grid');

    loading?.classList.add('hidden');

    if (! items.length) {
        empty?.classList.remove('hidden');
        grid?.classList.add('hidden');
        if (grid) grid.innerHTML = '';
        return;
    }

    empty?.classList.add('hidden');
    grid?.classList.remove('hidden');
    grid.innerHTML = items.map(renderItemCard).join('');
    bindItemDragAndDrop();
}

function renderCategoriesManage() {
    const list = document.getElementById('categories-manage-list');
    const hint = document.getElementById('categories-empty-hint');

    if (! categories.length) {
        list.innerHTML = '';
        hint?.classList.remove('hidden');
        return;
    }

    hint?.classList.add('hidden');
    list.innerHTML = categories.map((c) => {
        const inactive = c.is_active ? '' : 'opacity-55';
        const badge = c.is_active ? '' : '<span class="text-[10px] uppercase text-slate-400">Inactive</span>';

        return `
            <li class="flex items-center gap-2 rounded-xl border border-slate-100 bg-slate-50/80 px-3 py-2.5 ${inactive}" draggable="true" data-category-id="${c.id}">
                <button type="button" class="cursor-grab text-slate-300 hover:text-slate-500" data-drag-handle aria-label="Drag">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16"/></svg>
                </button>
                <span class="min-w-0 flex-1 truncate text-sm font-medium text-slate-800">${escapeHtml(c.name)}</span>
                ${badge}
                <button type="button" class="rounded-lg px-2 py-1 text-xs font-medium text-slate-600 hover:bg-white" data-action="edit-category" data-id="${c.id}">Edit</button>
                <button type="button" class="rounded-lg px-2 py-1 text-xs font-medium text-slate-600 hover:bg-white" data-action="toggle-category" data-id="${c.id}">${c.is_active ? 'Hide' : 'Show'}</button>
                <button type="button" class="rounded-lg px-2 py-1 text-xs font-medium text-rose-600 hover:bg-rose-50" data-action="delete-category" data-id="${c.id}">Delete</button>
            </li>
        `;
    }).join('');

    bindCategoryDragAndDrop();
}

function renderMediaList() {
    const list = document.getElementById('item-media-list');
    if (! list) return;

    const isCreateMode = ! document.getElementById('item-id').value;

    if (isCreateMode) {
        if (! pendingFiles.length) {
            list.innerHTML = '<p class="col-span-full text-xs text-slate-400">No images selected yet.</p>';
            return;
        }

        list.innerHTML = pendingFiles.map((file, index) => `
            <div class="group relative aspect-square overflow-hidden rounded-lg border border-slate-200 bg-slate-100" data-pending-index="${index}">
                <img src="${URL.createObjectURL(file)}" alt="" class="h-full w-full object-cover">
                <button type="button" data-action="remove-pending" data-index="${index}"
                    class="absolute right-1 top-1 rounded bg-rose-600 px-1.5 py-0.5 text-[10px] font-semibold text-white opacity-0 transition group-hover:opacity-100">
                    ×
                </button>
            </div>
        `).join('');
        return;
    }

    if (! editingMedia.length) {
        list.innerHTML = '<p class="col-span-full text-xs text-slate-400">No images yet.</p>';
        return;
    }

    list.innerHTML = editingMedia.map((m) => `
        <div class="group relative aspect-square overflow-hidden rounded-lg border border-slate-200 bg-slate-100" draggable="true" data-media-id="${m.id}">
            <img src="${escapeHtml(m.url)}" alt="" class="h-full w-full object-cover">
            <button type="button" data-action="delete-media" data-id="${m.id}"
                class="absolute right-1 top-1 rounded bg-rose-600 px-1.5 py-0.5 text-[10px] font-semibold text-white opacity-0 transition group-hover:opacity-100">
                ×
            </button>
        </div>
    `).join('');

    bindMediaDragAndDrop();
}

async function loadAll() {
    const [catRes, itemRes] = await Promise.all([
        api('/portfolio-categories'),
        api('/portfolio-items'),
    ]);

    if (! catRes.response.ok || ! catRes.payload.status) {
        showToast(catRes.payload?.message || 'Failed to load categories.', 'error');
    } else {
        categories = Array.isArray(catRes.payload.data) ? catRes.payload.data : [];
    }

    if (! itemRes.response.ok || ! itemRes.payload.status) {
        showToast(itemRes.payload?.message || 'Failed to load items.', 'error');
        document.getElementById('projects-loading')?.classList.add('hidden');
        return;
    }

    items = Array.isArray(itemRes.payload.data) ? itemRes.payload.data : [];
    renderItems();
}

function findItem(id) {
    return items.find((i) => String(i.id) === String(id));
}

function findCategory(id) {
    return categories.find((c) => String(c.id) === String(id));
}

function openItemModal(item = null) {
    document.getElementById('item-modal-title').textContent = item ? 'Edit Item' : 'Add Item';
    document.getElementById('item-id').value = item?.id ?? '';
    document.getElementById('item-title').value = item?.title ?? '';
    document.getElementById('item-short-description').value = item?.short_description ?? '';
    document.getElementById('item-description').value = item?.description ?? '';
    fillCategorySelect(item?.category_id ?? '');
    document.getElementById('item-client-name').value = item?.client_name ?? '';
    document.getElementById('item-project-url').value = item?.project_url ?? '';
    setItemDates(item?.start_date ?? '', item?.end_date ?? '');
    document.getElementById('item-is-featured').checked = Boolean(item?.is_featured);
    document.getElementById('item-is-active').checked = item ? Boolean(item.is_active) : true;
    setFieldError('item-title-error', '');
    setFieldError('item-url-error', '');

    const mediaSection = document.getElementById('item-media-section');
    mediaSection?.classList.remove('hidden');
    document.getElementById('item-media-status').textContent = '';
    pendingFiles = [];

    if (item) {
        editingMedia = Array.isArray(item.media) ? [...item.media] : [];
    } else {
        editingMedia = [];
    }

    renderMediaList();

    openModal('item-modal');
    document.getElementById('item-title')?.focus();
}

function openCategoriesModal() {
    document.getElementById('category-id').value = '';
    document.getElementById('category-name').value = '';
    document.getElementById('category-save-btn').textContent = 'Add';
    setFieldError('category-name-error', '');
    renderCategoriesManage();
    openModal('categories-modal');
}

async function saveItem(event) {
    event.preventDefault();

    const id = document.getElementById('item-id').value;
    const title = document.getElementById('item-title').value.trim();
    const project_url = document.getElementById('item-project-url').value.trim();
    const categoryRaw = document.getElementById('item-category-id').value;

    if (! title) {
        setFieldError('item-title-error', 'Title is required.');
        return;
    }
    setFieldError('item-title-error', '');

    if (project_url) {
        try {
            // eslint-disable-next-line no-new
            new URL(project_url);
            setFieldError('item-url-error', '');
        } catch {
            setFieldError('item-url-error', 'Enter a valid URL including https://');
            return;
        }
    }

    const dates = getItemDates();

    const body = {
        title,
        short_description: document.getElementById('item-short-description').value.trim() || null,
        description: document.getElementById('item-description').value.trim() || null,
        category_id: categoryRaw ? Number(categoryRaw) : null,
        client_name: document.getElementById('item-client-name').value.trim() || null,
        project_url: project_url || null,
        start_date: dates.start_date,
        end_date: dates.end_date,
        is_featured: document.getElementById('item-is-featured').checked,
        is_active: document.getElementById('item-is-active').checked,
    };

    const btn = document.getElementById('item-save-btn');
    btn.disabled = true;

    try {
        const { response, payload } = await api(id ? `/portfolio-items/${id}` : '/portfolio-items', {
            method: id ? 'PUT' : 'POST',
            body: JSON.stringify(body),
        });

        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Could not save item.', 'error');
            return;
        }

        showToast(payload.message || 'Item saved.');

        if (! id && payload.data?.id && pendingFiles.length) {
            // Upload queued images to the newly created item
            const status = document.getElementById('item-media-status');
            status.textContent = 'Uploading images…';

            const mediaBody = new FormData();
            pendingFiles.forEach((file) => mediaBody.append('images[]', file));

            const mediaRes = await api(`/portfolio-items/${payload.data.id}/media`, {
                method: 'POST',
                body: mediaBody,
            });

            if (! mediaRes.response.ok || ! mediaRes.payload.status) {
                showToast(mediaRes.payload?.message || 'Item saved, but image upload failed.', 'error');
            } else {
                showToast('Images uploaded.');
            }

            pendingFiles = [];
        }

        closeModal('item-modal');
        await loadAll();
    } catch {
        showToast('Could not save item.', 'error');
    } finally {
        btn.disabled = false;
    }
}

async function saveCategory(event) {
    event.preventDefault();

    const id = document.getElementById('category-id').value;
    const name = document.getElementById('category-name').value.trim();

    if (! name) {
        setFieldError('category-name-error', 'Name is required.');
        return;
    }
    setFieldError('category-name-error', '');

    const btn = document.getElementById('category-save-btn');
    btn.disabled = true;

    try {
        const { response, payload } = await api(id ? `/portfolio-categories/${id}` : '/portfolio-categories', {
            method: id ? 'PUT' : 'POST',
            body: JSON.stringify({ name, is_active: true }),
        });

        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Could not save category.', 'error');
            return;
        }

        document.getElementById('category-id').value = '';
        document.getElementById('category-name').value = '';
        btn.textContent = 'Add';
        showToast(payload.message || 'Category saved.');
        await loadAll();
        renderCategoriesManage();
        fillCategorySelect(document.getElementById('item-category-id')?.value);
    } catch {
        showToast('Could not save category.', 'error');
    } finally {
        btn.disabled = false;
    }
}

async function uploadMedia(files) {
    if (! files?.length) return;

    const itemId = document.getElementById('item-id').value;
    const status = document.getElementById('item-media-status');

    // Create mode: queue files locally; they upload right after the item is saved.
    if (! itemId) {
        pendingFiles = [...pendingFiles, ...files];
        renderMediaList();
        status.textContent = `${pendingFiles.length} image${pendingFiles.length === 1 ? '' : 's'} will upload after saving.`;
        return;
    }

    status.textContent = 'Uploading…';

    const body = new FormData();
    [...files].forEach((file) => body.append('images[]', file));

    try {
        const { response, payload } = await api(`/portfolio-items/${itemId}/media`, {
            method: 'POST',
            body,
        });

        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Upload failed.', 'error');
            status.textContent = 'Upload failed.';
            return;
        }

        editingMedia = Array.isArray(payload.data) ? payload.data : [];
        renderMediaList();
        status.textContent = 'Images updated.';
        showToast(payload.message || 'Images uploaded.');
        await loadAll();
        // Keep editing media in sync with refreshed item
        const refreshed = findItem(itemId);
        if (refreshed) {
            editingMedia = Array.isArray(refreshed.media) ? [...refreshed.media] : editingMedia;
            renderMediaList();
        }
    } catch {
        showToast('Upload failed.', 'error');
        status.textContent = 'Upload failed.';
    }
}

async function handleGridClick(event) {
    const btn = event.target.closest('[data-action]');
    if (! btn) return;

    const action = btn.dataset.action;
    const id = btn.dataset.id;

    if (action === 'edit-item') {
        openItemModal(findItem(id));
        return;
    }

    if (action === 'toggle-item') {
        const { response, payload } = await api(`/portfolio-items/${id}/toggle`, { method: 'PATCH' });
        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Toggle failed.', 'error');
            return;
        }
        showToast(payload.message || 'Updated.');
        await loadAll();
        return;
    }

    if (action === 'delete-item') {
        if (! window.confirm('Delete this portfolio item and its images?')) return;
        const { response, payload } = await api(`/portfolio-items/${id}`, { method: 'DELETE' });
        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Delete failed.', 'error');
            return;
        }
        showToast(payload.message || 'Item deleted.');
        await loadAll();
    }
}

async function handleCategoriesClick(event) {
    const btn = event.target.closest('[data-action]');
    if (! btn) return;

    const action = btn.dataset.action;
    const id = btn.dataset.id;

    if (action === 'edit-category') {
        const cat = findCategory(id);
        document.getElementById('category-id').value = cat?.id ?? '';
        document.getElementById('category-name').value = cat?.name ?? '';
        document.getElementById('category-save-btn').textContent = 'Update';
        document.getElementById('category-name')?.focus();
        return;
    }

    if (action === 'toggle-category') {
        const { response, payload } = await api(`/portfolio-categories/${id}/toggle`, { method: 'PATCH' });
        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Toggle failed.', 'error');
            return;
        }
        showToast(payload.message || 'Updated.');
        await loadAll();
        renderCategoriesManage();
        return;
    }

    if (action === 'delete-category') {
        if (! window.confirm('Delete this category? Items keep their data (category cleared).')) return;
        const { response, payload } = await api(`/portfolio-categories/${id}`, { method: 'DELETE' });
        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Delete failed.', 'error');
            return;
        }
        showToast(payload.message || 'Category deleted.');
        await loadAll();
        renderCategoriesManage();
    }
}

async function handleMediaClick(event) {
    const pendingBtn = event.target.closest('[data-action="remove-pending"]');
    if (pendingBtn) {
        pendingFiles.splice(Number(pendingBtn.dataset.index), 1);
        renderMediaList();
        const status = document.getElementById('item-media-status');
        status.textContent = pendingFiles.length
            ? `${pendingFiles.length} image${pendingFiles.length === 1 ? '' : 's'} will upload after saving.`
            : '';
        return;
    }

    const btn = event.target.closest('[data-action="delete-media"]');
    if (! btn) return;

    const id = btn.dataset.id;
    if (! window.confirm('Remove this image?')) return;

    const { response, payload } = await api(`/portfolio-media/${id}`, { method: 'DELETE' });
    if (! response.ok || ! payload.status) {
        showToast(payload?.message || 'Delete failed.', 'error');
        return;
    }

    editingMedia = editingMedia.filter((m) => String(m.id) !== String(id));
    renderMediaList();
    showToast(payload.message || 'Image removed.');
    await loadAll();
}

function bindItemDragAndDrop() {
    const grid = document.getElementById('items-grid');
    if (! grid) return;

    let dragEl = null;

    grid.querySelectorAll('[data-item-id]').forEach((card) => {
        card.addEventListener('dragstart', (e) => {
            if (! e.target.closest('[data-drag-handle]')) {
                e.preventDefault();
                return;
            }
            dragEl = card;
            card.classList.add('ring-2', 'ring-primary-token');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', `item:${card.dataset.itemId}`);
        });

        card.addEventListener('dragend', () => {
            card.classList.remove('ring-2', 'ring-primary-token');
            dragEl = null;
        });

        card.addEventListener('dragover', (e) => {
            if (! dragEl) return;
            e.preventDefault();
            if (card !== dragEl) {
                const rect = card.getBoundingClientRect();
                const before = e.clientY < rect.top + rect.height / 2;
                grid.insertBefore(dragEl, before ? card : card.nextSibling);
            }
        });
    });

    grid.addEventListener('drop', async (e) => {
        const raw = e.dataTransfer.getData('text/plain');
        if (! raw.startsWith('item:')) return;
        e.preventDefault();
        const ids = [...grid.querySelectorAll('[data-item-id]')].map((el) => Number(el.dataset.itemId));
        const { response, payload } = await api('/portfolio-items/reorder', {
            method: 'PATCH',
            body: JSON.stringify({ ids }),
        });
        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Reorder failed.', 'error');
            await loadAll();
            return;
        }
        items = Array.isArray(payload.data) ? payload.data : items;
        renderItems();
        showToast('Items reordered.');
    });
}

function bindCategoryDragAndDrop() {
    const list = document.getElementById('categories-manage-list');
    if (! list) return;

    let dragEl = null;

    list.querySelectorAll('[data-category-id]').forEach((row) => {
        row.addEventListener('dragstart', (e) => {
            if (! e.target.closest('[data-drag-handle]')) {
                e.preventDefault();
                return;
            }
            dragEl = row;
            row.classList.add('ring-2', 'ring-primary-token');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', `pcat:${row.dataset.categoryId}`);
        });

        row.addEventListener('dragend', () => {
            row.classList.remove('ring-2', 'ring-primary-token');
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
        if (! raw.startsWith('pcat:')) return;
        e.preventDefault();
        const ids = [...list.querySelectorAll('[data-category-id]')].map((el) => Number(el.dataset.categoryId));
        const { response, payload } = await api('/portfolio-categories/reorder', {
            method: 'PATCH',
            body: JSON.stringify({ ids }),
        });
        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Reorder failed.', 'error');
            await loadAll();
            renderCategoriesManage();
            return;
        }
        categories = Array.isArray(payload.data) ? payload.data : categories;
        renderCategoriesManage();
        showToast('Categories reordered.');
    });
}

function bindMediaDragAndDrop() {
    const list = document.getElementById('item-media-list');
    if (! list) return;

    let dragEl = null;
    const itemId = document.getElementById('item-id').value;

    list.querySelectorAll('[data-media-id]').forEach((tile) => {
        tile.addEventListener('dragstart', (e) => {
            dragEl = tile;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', `media:${tile.dataset.mediaId}`);
        });

        tile.addEventListener('dragend', () => {
            dragEl = null;
        });

        tile.addEventListener('dragover', (e) => {
            if (! dragEl) return;
            e.preventDefault();
            if (tile !== dragEl) {
                const rect = tile.getBoundingClientRect();
                const before = e.clientX < rect.left + rect.width / 2;
                list.insertBefore(dragEl, before ? tile : tile.nextSibling);
            }
        });
    });

    list.addEventListener('drop', async (e) => {
        const raw = e.dataTransfer.getData('text/plain');
        if (! raw.startsWith('media:') || ! itemId) return;
        e.preventDefault();
        const ids = [...list.querySelectorAll('[data-media-id]')].map((el) => Number(el.dataset.mediaId));
        const { response, payload } = await api(`/portfolio-items/${itemId}/media/reorder`, {
            method: 'PATCH',
            body: JSON.stringify({ ids }),
        });
        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Reorder failed.', 'error');
            return;
        }
        editingMedia = Array.isArray(payload.data) ? payload.data : editingMedia;
        renderMediaList();
        await loadAll();
        showToast('Images reordered.');
    });
}

export async function initProjectsPage() {
    if (! requireAuth()) return;

    initAdminShell();
    initDatePickers();

    document.querySelectorAll('[data-modal-close]').forEach((el) => {
        el.addEventListener('click', () => closeModal(el.dataset.modalClose));
    });

    document.getElementById('add-item-btn')?.addEventListener('click', () => openItemModal());
    document.getElementById('empty-add-item-btn')?.addEventListener('click', () => openItemModal());
    document.getElementById('manage-categories-btn')?.addEventListener('click', openCategoriesModal);
    document.getElementById('item-form')?.addEventListener('submit', saveItem);
    document.getElementById('category-form')?.addEventListener('submit', saveCategory);
    document.getElementById('items-grid')?.addEventListener('click', handleGridClick);
    document.getElementById('categories-manage-list')?.addEventListener('click', handleCategoriesClick);
    document.getElementById('item-media-list')?.addEventListener('click', handleMediaClick);
    document.getElementById('item-media-input')?.addEventListener('change', (e) => {
        uploadMedia(e.target.files);
        e.target.value = '';
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal('item-modal');
            closeModal('categories-modal');
        }
    });

    await loadAll();
}
