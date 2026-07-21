import { api } from '../api';
import { requireAuth } from '../auth';
import { initAdminShell } from './dashboard';
import { showToast } from '../toast';

/** @type {Array<object>} */
let categories = [];

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function formatLevel(skill) {
    const level = skill.level ?? '';

    if (skill.level_type === 'percentage') {
        return level !== '' ? `${level}%` : '—';
    }

    if (skill.level_type === 'stars') {
        const n = Math.min(5, Math.max(0, Number(level) || 0));
        return '★'.repeat(n) + '☆'.repeat(5 - n);
    }

    return level || '—';
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

    if (! el) {
        return;
    }

    if (message) {
        el.textContent = message;
        el.classList.remove('hidden');
    } else {
        el.textContent = '';
        el.classList.add('hidden');
    }
}

function fillCategorySelect(selectedId = '') {
    const select = document.getElementById('skill-category-id');

    if (! select) {
        return;
    }

    select.innerHTML = '<option value="">Select category…</option>'
        + categories.map((c) => (
            `<option value="${c.id}" ${String(c.id) === String(selectedId) ? 'selected' : ''}>${escapeHtml(c.name)}</option>`
        )).join('');
}

function renderSkillRow(skill) {
    const inactive = skill.is_active ? '' : 'opacity-55';
    const featured = skill.is_featured
        ? '<span class="rounded-md bg-amber-50 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-amber-700">Featured</span>'
        : '';
    const badge = skill.is_active
        ? ''
        : '<span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-500">Inactive</span>';

    return `
        <li
            class="group flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50/80 px-3 py-2.5 ${inactive}"
            draggable="true"
            data-skill-id="${skill.id}"
            data-category-id="${skill.category_id}"
        >
            <button type="button" class="cursor-grab text-slate-300 hover:text-slate-500" data-drag-handle title="Drag to reorder" aria-label="Drag">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16"/></svg>
            </button>
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="truncate text-sm font-medium text-slate-800">${escapeHtml(skill.name)}</span>
                    ${featured}${badge}
                </div>
                <p class="mt-0.5 text-xs text-slate-500">${escapeHtml(formatLevel(skill))} · ${escapeHtml(skill.level_type)}</p>
            </div>
            <div class="flex shrink-0 items-center gap-1 opacity-100 sm:opacity-0 sm:group-hover:opacity-100">
                <button type="button" class="rounded-lg px-2 py-1 text-xs font-medium text-slate-600 hover:bg-white" data-action="edit-skill" data-id="${skill.id}">Edit</button>
                <button type="button" class="rounded-lg px-2 py-1 text-xs font-medium text-slate-600 hover:bg-white" data-action="toggle-skill" data-id="${skill.id}">${skill.is_active ? 'Hide' : 'Show'}</button>
                <button type="button" class="rounded-lg px-2 py-1 text-xs font-medium text-rose-600 hover:bg-rose-50" data-action="delete-skill" data-id="${skill.id}">Delete</button>
            </div>
        </li>
    `;
}

function renderCategoryCard(category) {
    const inactive = category.is_active ? '' : 'opacity-60';
    const badge = category.is_active
        ? ''
        : '<span class="rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-500">Inactive</span>';
    const skills = Array.isArray(category.skills) ? category.skills : [];

    return `
        <article
            class="rounded-2xl border border-slate-200 bg-white shadow-sm ${inactive}"
            draggable="true"
            data-category-id="${category.id}"
        >
            <div class="flex items-start gap-3 border-b border-slate-100 px-5 py-4">
                <button type="button" class="mt-0.5 cursor-grab text-slate-300 hover:text-slate-500" data-drag-handle title="Drag to reorder" aria-label="Drag category">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16"/></svg>
                </button>
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="text-base font-semibold text-slate-900">${escapeHtml(category.name)}</h3>
                        ${badge}
                    </div>
                    <p class="mt-0.5 text-xs text-slate-500">${skills.length} skill${skills.length === 1 ? '' : 's'}</p>
                </div>
                <div class="flex shrink-0 flex-wrap items-center gap-1">
                    <button type="button" class="rounded-lg px-2.5 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50" data-action="add-skill-here" data-id="${category.id}">+ Skill</button>
                    <button type="button" class="rounded-lg px-2.5 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50" data-action="edit-category" data-id="${category.id}">Edit</button>
                    <button type="button" class="rounded-lg px-2.5 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50" data-action="toggle-category" data-id="${category.id}">${category.is_active ? 'Hide' : 'Show'}</button>
                    <button type="button" class="rounded-lg px-2.5 py-1.5 text-xs font-medium text-rose-600 hover:bg-rose-50" data-action="delete-category" data-id="${category.id}">Delete</button>
                </div>
            </div>
            <ul class="space-y-2 px-5 py-4" data-skills-list="${category.id}">
                ${skills.length
                    ? skills.map(renderSkillRow).join('')
                    : '<li class="rounded-xl border border-dashed border-slate-200 px-3 py-6 text-center text-sm text-slate-400">No skills in this category yet.</li>'}
            </ul>
        </article>
    `;
}

function render() {
    const loading = document.getElementById('skills-loading');
    const empty = document.getElementById('skills-empty');
    const list = document.getElementById('categories-list');

    loading?.classList.add('hidden');

    if (! categories.length) {
        empty?.classList.remove('hidden');
        list?.classList.add('hidden');
        list.innerHTML = '';
        return;
    }

    empty?.classList.add('hidden');
    list?.classList.remove('hidden');
    list.innerHTML = categories.map(renderCategoryCard).join('');
    bindDragAndDrop();
}

async function loadCategories() {
    const { response, payload } = await api('/categories');

    if (! response.ok || ! payload.status) {
        showToast(payload?.message || 'Failed to load skills.', 'error');
        document.getElementById('skills-loading')?.classList.add('hidden');
        return;
    }

    categories = Array.isArray(payload.data) ? payload.data : [];
    render();
}

function openCategoryModal(category = null) {
    document.getElementById('category-modal-title').textContent = category ? 'Edit Category' : 'Add Category';
    document.getElementById('category-id').value = category?.id ?? '';
    document.getElementById('category-name').value = category?.name ?? '';
    document.getElementById('category-is-active').checked = category ? Boolean(category.is_active) : true;
    setFieldError('category-name-error', '');
    openModal('category-modal');
    document.getElementById('category-name')?.focus();
}

function openSkillModal(skill = null, categoryId = '') {
    if (! categories.length) {
        showToast('Create a category first.', 'error');
        return;
    }

    document.getElementById('skill-modal-title').textContent = skill ? 'Edit Skill' : 'Add Skill';
    document.getElementById('skill-id').value = skill?.id ?? '';
    fillCategorySelect(skill?.category_id ?? categoryId);
    document.getElementById('skill-name').value = skill?.name ?? '';
    document.getElementById('skill-level-type').value = skill?.level_type ?? 'percentage';
    document.getElementById('skill-level').value = skill?.level ?? '';
    document.getElementById('skill-is-featured').checked = Boolean(skill?.is_featured);
    document.getElementById('skill-is-active').checked = skill ? Boolean(skill.is_active) : true;
    setFieldError('skill-name-error', '');
    setFieldError('skill-category-error', '');
    setFieldError('skill-level-error', '');
    updateLevelPlaceholder();
    openModal('skill-modal');
    document.getElementById('skill-name')?.focus();
}

function updateLevelPlaceholder() {
    const type = document.getElementById('skill-level-type')?.value;
    const input = document.getElementById('skill-level');

    if (! input) {
        return;
    }

    if (type === 'percentage') {
        input.placeholder = '0–100';
    } else if (type === 'stars') {
        input.placeholder = '1–5';
    } else {
        input.placeholder = 'e.g. Expert';
    }
}

function validateSkillLevel() {
    const type = document.getElementById('skill-level-type').value;
    const level = document.getElementById('skill-level').value.trim();

    if (! level) {
        return true;
    }

    if (type === 'percentage') {
        const n = Number(level);
        if (! Number.isFinite(n) || n < 0 || n > 100) {
            setFieldError('skill-level-error', 'Enter a number from 0 to 100.');
            return false;
        }
    }

    if (type === 'stars') {
        const n = Number(level);
        if (! Number.isInteger(n) || n < 1 || n > 5) {
            setFieldError('skill-level-error', 'Enter a whole number from 1 to 5.');
            return false;
        }
    }

    setFieldError('skill-level-error', '');
    return true;
}

function findCategory(id) {
    return categories.find((c) => String(c.id) === String(id));
}

function findSkill(id) {
    for (const category of categories) {
        const skill = (category.skills || []).find((s) => String(s.id) === String(id));
        if (skill) {
            return skill;
        }
    }

    return null;
}

async function saveCategory(event) {
    event.preventDefault();

    const id = document.getElementById('category-id').value;
    const name = document.getElementById('category-name').value.trim();
    const is_active = document.getElementById('category-is-active').checked;

    if (! name) {
        setFieldError('category-name-error', 'Name is required.');
        return;
    }

    setFieldError('category-name-error', '');
    const btn = document.getElementById('category-save-btn');
    btn.disabled = true;

    try {
        const { response, payload } = await api(id ? `/categories/${id}` : '/categories', {
            method: id ? 'PUT' : 'POST',
            body: JSON.stringify({ name, is_active }),
        });

        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Could not save category.', 'error');
            return;
        }

        closeModal('category-modal');
        showToast(payload.message || 'Category saved.');
        await loadCategories();
    } catch {
        showToast('Could not save category.', 'error');
    } finally {
        btn.disabled = false;
    }
}

async function saveSkill(event) {
    event.preventDefault();

    const id = document.getElementById('skill-id').value;
    const category_id = document.getElementById('skill-category-id').value;
    const name = document.getElementById('skill-name').value.trim();
    const level_type = document.getElementById('skill-level-type').value;
    const level = document.getElementById('skill-level').value.trim();
    const is_featured = document.getElementById('skill-is-featured').checked;
    const is_active = document.getElementById('skill-is-active').checked;

    let valid = true;

    if (! category_id) {
        setFieldError('skill-category-error', 'Choose a category.');
        valid = false;
    } else {
        setFieldError('skill-category-error', '');
    }

    if (! name) {
        setFieldError('skill-name-error', 'Name is required.');
        valid = false;
    } else {
        setFieldError('skill-name-error', '');
    }

    if (! validateSkillLevel()) {
        valid = false;
    }

    if (! valid) {
        return;
    }

    const btn = document.getElementById('skill-save-btn');
    btn.disabled = true;

    try {
        const { response, payload } = await api(id ? `/skills/${id}` : '/skills', {
            method: id ? 'PUT' : 'POST',
            body: JSON.stringify({
                category_id: Number(category_id),
                name,
                level: level || null,
                level_type,
                is_featured,
                is_active,
            }),
        });

        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Could not save skill.', 'error');
            return;
        }

        closeModal('skill-modal');
        showToast(payload.message || 'Skill saved.');
        await loadCategories();
    } catch {
        showToast('Could not save skill.', 'error');
    } finally {
        btn.disabled = false;
    }
}

async function handleListClick(event) {
    const btn = event.target.closest('[data-action]');

    if (! btn) {
        return;
    }

    const action = btn.dataset.action;
    const id = btn.dataset.id;

    if (action === 'edit-category') {
        openCategoryModal(findCategory(id));
        return;
    }

    if (action === 'add-skill-here') {
        openSkillModal(null, id);
        return;
    }

    if (action === 'toggle-category') {
        const { response, payload } = await api(`/categories/${id}/toggle`, { method: 'PATCH' });
        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Toggle failed.', 'error');
            return;
        }
        showToast(payload.message || 'Updated.');
        await loadCategories();
        return;
    }

    if (action === 'delete-category') {
        if (! window.confirm('Delete this category and all of its skills?')) {
            return;
        }
        const { response, payload } = await api(`/categories/${id}`, { method: 'DELETE' });
        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Delete failed.', 'error');
            return;
        }
        showToast(payload.message || 'Category deleted.');
        await loadCategories();
        return;
    }

    if (action === 'edit-skill') {
        openSkillModal(findSkill(id));
        return;
    }

    if (action === 'toggle-skill') {
        const { response, payload } = await api(`/skills/${id}/toggle`, { method: 'PATCH' });
        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Toggle failed.', 'error');
            return;
        }
        showToast(payload.message || 'Updated.');
        await loadCategories();
        return;
    }

    if (action === 'delete-skill') {
        if (! window.confirm('Delete this skill?')) {
            return;
        }
        const { response, payload } = await api(`/skills/${id}`, { method: 'DELETE' });
        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Delete failed.', 'error');
            return;
        }
        showToast(payload.message || 'Skill deleted.');
        await loadCategories();
    }
}

function bindDragAndDrop() {
    const list = document.getElementById('categories-list');

    if (! list) {
        return;
    }

    /** @type {HTMLElement|null} */
    let dragEl = null;

    list.querySelectorAll('[data-category-id].rounded-2xl').forEach((card) => {
        card.addEventListener('dragstart', (e) => {
            if (e.target.closest('[data-skill-id]')) {
                return;
            }

            if (! e.target.closest('[data-drag-handle]')) {
                e.preventDefault();
                return;
            }

            dragEl = card;
            card.classList.add('ring-2', 'ring-primary-token');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', `category:${card.dataset.categoryId}`);
        });

        card.addEventListener('dragend', () => {
            card.classList.remove('ring-2', 'ring-primary-token');
            dragEl = null;
        });

        card.addEventListener('dragover', (e) => {
            if (! dragEl || ! dragEl.hasAttribute('data-category-id') || dragEl.hasAttribute('data-skill-id')) {
                return;
            }
            e.preventDefault();
            if (card !== dragEl) {
                const rect = card.getBoundingClientRect();
                const before = e.clientY < rect.top + rect.height / 2;
                list.insertBefore(dragEl, before ? card : card.nextSibling);
            }
        });
    });

    list.addEventListener('drop', async (e) => {
        const raw = e.dataTransfer.getData('text/plain');
        if (! raw.startsWith('category:')) {
            return;
        }
        e.preventDefault();
        const ids = [...list.querySelectorAll(':scope > [data-category-id]')].map((el) => Number(el.dataset.categoryId));
        const { response, payload } = await api('/categories/reorder', {
            method: 'PATCH',
            body: JSON.stringify({ ids }),
        });
        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Reorder failed.', 'error');
            await loadCategories();
            return;
        }
        categories = Array.isArray(payload.data) ? payload.data : categories;
        render();
        showToast('Categories reordered.');
    });

    list.querySelectorAll('[data-skills-list]').forEach((skillsList) => {
        let skillDrag = null;

        skillsList.querySelectorAll('[data-skill-id]').forEach((row) => {
            row.addEventListener('dragstart', (e) => {
                skillDrag = row;
                e.stopPropagation();
                row.classList.add('ring-2', 'ring-primary-token');
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', `skill:${row.dataset.skillId}`);
            });

            row.addEventListener('dragend', () => {
                row.classList.remove('ring-2', 'ring-primary-token');
                skillDrag = null;
            });

            row.addEventListener('dragover', (e) => {
                if (! skillDrag || skillDrag.dataset.categoryId !== row.dataset.categoryId) {
                    return;
                }
                e.preventDefault();
                e.stopPropagation();
                if (row !== skillDrag) {
                    const rect = row.getBoundingClientRect();
                    const before = e.clientY < rect.top + rect.height / 2;
                    skillsList.insertBefore(skillDrag, before ? row : row.nextSibling);
                }
            });
        });

        skillsList.addEventListener('drop', async (e) => {
            const raw = e.dataTransfer.getData('text/plain');
            if (! raw.startsWith('skill:')) {
                return;
            }
            e.preventDefault();
            e.stopPropagation();
            const categoryId = Number(skillsList.dataset.skillsList);
            const ids = [...skillsList.querySelectorAll('[data-skill-id]')].map((el) => Number(el.dataset.skillId));
            const { response, payload } = await api('/skills/reorder', {
                method: 'PATCH',
                body: JSON.stringify({ ids, category_id: categoryId }),
            });
            if (! response.ok || ! payload.status) {
                showToast(payload?.message || 'Reorder failed.', 'error');
                await loadCategories();
                return;
            }
            await loadCategories();
            showToast('Skills reordered.');
        });
    });
}

export async function initSkillsPage() {
    if (! requireAuth()) {
        return;
    }

    initAdminShell();

    document.querySelectorAll('[data-modal-close]').forEach((el) => {
        el.addEventListener('click', () => closeModal(el.dataset.modalClose));
    });

    document.getElementById('add-category-btn')?.addEventListener('click', () => openCategoryModal());
    document.getElementById('empty-add-category-btn')?.addEventListener('click', () => openCategoryModal());
    document.getElementById('add-skill-btn')?.addEventListener('click', () => openSkillModal());
    document.getElementById('category-form')?.addEventListener('submit', saveCategory);
    document.getElementById('skill-form')?.addEventListener('submit', saveSkill);
    document.getElementById('skill-level-type')?.addEventListener('change', updateLevelPlaceholder);
    document.getElementById('categories-list')?.addEventListener('click', handleListClick);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal('category-modal');
            closeModal('skill-modal');
        }
    });

    await loadCategories();
}
