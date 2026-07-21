import { api } from '../api';
import { me } from '../auth';
import { initAdminShell } from './dashboard';
import { showToast } from '../toast';

function setError(field, message) {
    const el = document.querySelector(`[data-error-for="${field}"]`);

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

function clearErrors() {
    document.querySelectorAll('[data-error-for]').forEach((el) => {
        el.textContent = '';
        el.classList.add('hidden');
    });
}

function isValidUrl(value) {
    if (! value) {
        return true;
    }

    try {
        const url = new URL(value);
        return url.protocol === 'http:' || url.protocol === 'https:';
    } catch {
        return false;
    }
}

function toggleEmptyState(listEl, emptyEl) {
    const hasRows = listEl.children.length > 0;
    emptyEl.classList.toggle('hidden', hasRows);
}

function updateCompletion() {
    const checks = [
        Boolean(document.getElementById('profile-name')?.value.trim()),
        Boolean(document.getElementById('profile-title')?.value.trim()),
        Boolean(document.getElementById('profile-bio')?.value.trim()),
        Boolean(document.getElementById('profile-about')?.value.trim()),
        Boolean(document.getElementById('avatar-preview')?.dataset.hasAvatar === '1'),
        Boolean(document.getElementById('profile-location')?.value.trim()),
        Boolean(document.getElementById('profile-phone')?.value.trim()),
        Boolean(document.getElementById('profile-website')?.value.trim()),
        document.getElementById('social-links-list')?.children.length > 0,
        document.getElementById('meta-fields-list')?.children.length > 0,
    ];

    const score = checks.filter(Boolean).length;
    const percent = Math.round((score / checks.length) * 100);
    const bar = document.getElementById('profile-completion-bar');
    const label = document.getElementById('profile-completion-label');

    if (bar) {
        bar.style.width = `${percent}%`;
    }

    if (label) {
        label.textContent = `${percent}%`;
    }
}

function addSocialRow({ id = '', platform = 'linkedin', url = '' } = {}) {
    const template = document.getElementById('social-link-template');
    const list = document.getElementById('social-links-list');
    const empty = document.getElementById('social-links-empty');
    const node = template.content.firstElementChild.cloneNode(true);

    node.dataset.id = id || '';
    node.querySelector('.social-platform').value = platform;
    node.querySelector('.social-url').value = url;

    node.querySelector('.remove-social-link').addEventListener('click', async () => {
        const linkId = node.dataset.id;

        if (linkId) {
            try {
                const { response, payload } = await api(`/social-links/${linkId}`, { method: 'DELETE' });

                if (! response.ok || ! payload.status) {
                    showToast(payload?.message || 'Could not delete link.', 'error');
                    return;
                }
            } catch {
                showToast('Could not delete link.', 'error');
                return;
            }
        }

        node.remove();
        toggleEmptyState(list, empty);
        updateCompletion();
    });

    node.querySelector('.social-url').addEventListener('input', updateCompletion);
    list.appendChild(node);
    toggleEmptyState(list, empty);
    updateCompletion();
}

function addMetaRow({ key = '', value = '' } = {}) {
    const template = document.getElementById('meta-field-template');
    const list = document.getElementById('meta-fields-list');
    const empty = document.getElementById('meta-fields-empty');
    const node = template.content.firstElementChild.cloneNode(true);

    node.querySelector('.meta-key').value = key;
    node.querySelector('.meta-value').value = value;

    node.querySelector('.remove-meta-field').addEventListener('click', () => {
        node.remove();
        toggleEmptyState(list, empty);
        updateCompletion();
    });

    node.querySelector('.meta-key').addEventListener('input', updateCompletion);
    node.querySelector('.meta-value').addEventListener('input', updateCompletion);

    list.appendChild(node);
    toggleEmptyState(list, empty);
    updateCompletion();
}

function collectSocialLinks() {
    return Array.from(document.querySelectorAll('.social-link-row')).map((row) => {
        const item = {
            platform: row.querySelector('.social-platform').value,
            url: row.querySelector('.social-url').value.trim(),
        };

        if (row.dataset.id) {
            item.id = Number(row.dataset.id);
        }

        return item;
    }).filter((item) => item.url !== '');
}

function collectMeta() {
    return Array.from(document.querySelectorAll('.meta-field-row')).map((row) => ({
        key: row.querySelector('.meta-key').value.trim().toLowerCase(),
        value: row.querySelector('.meta-value').value.trim(),
    })).filter((item) => item.key !== '');
}

function fillProfile(data, accountEmail) {
    const profile = data.profile || {};

    document.getElementById('profile-name').value = profile.name || '';
    document.getElementById('profile-title').value = profile.title || '';
    document.getElementById('profile-bio').value = profile.bio || '';
    document.getElementById('profile-about').value = profile.about || '';
    document.getElementById('profile-phone').value = profile.phone || '';
    document.getElementById('profile-location').value = profile.location || '';
    document.getElementById('profile-website').value = profile.website || '';
    document.getElementById('email-public').checked = Boolean(profile.email_public);
    document.getElementById('phone-public').checked = Boolean(profile.phone_public);
    document.getElementById('profile-email').value = accountEmail || '';

    const preview = document.getElementById('avatar-preview');

    if (profile.avatar_url) {
        preview.src = profile.avatar_url;
        preview.dataset.hasAvatar = '1';
    } else {
        const name = encodeURIComponent(profile.name || accountEmail || 'User');
        preview.src = `https://ui-avatars.com/api/?name=${name}&background=0d9488&color=fff`;
        preview.dataset.hasAvatar = '0';
    }

    document.getElementById('social-links-list').innerHTML = '';
    document.getElementById('meta-fields-list').innerHTML = '';

    (data.social_links || []).forEach((link) => {
        addSocialRow({
            id: String(link.id),
            platform: link.platform,
            url: link.url,
        });
    });

    (data.meta || []).forEach((item) => {
        addMetaRow({ key: item.key, value: item.value || '' });
    });

    toggleEmptyState(
        document.getElementById('social-links-list'),
        document.getElementById('social-links-empty'),
    );
    toggleEmptyState(
        document.getElementById('meta-fields-list'),
        document.getElementById('meta-fields-empty'),
    );

    updateCompletion();
}

function validateForm() {
    clearErrors();
    let ok = true;

    const name = document.getElementById('profile-name').value.trim();
    const title = document.getElementById('profile-title').value.trim();
    const website = document.getElementById('profile-website').value.trim();

    if (! name) {
        setError('name', 'Name is required.');
        ok = false;
    }

    if (! title) {
        setError('title', 'Title is required.');
        ok = false;
    }

    if (website && ! isValidUrl(website)) {
        setError('website', 'Enter a valid website URL.');
        ok = false;
    }

    const links = collectSocialLinks();

    for (const link of links) {
        if (! isValidUrl(link.url)) {
            setError('social', 'Each social link must be a valid URL.');
            ok = false;
            break;
        }
    }

    const meta = collectMeta();

    for (const item of meta) {
        if (! /^[a-z0-9_]+$/.test(item.key)) {
            setError('meta', 'Meta keys may only use lowercase letters, numbers, and underscores.');
            ok = false;
            break;
        }
    }

    return ok;
}

function setSaving(loading) {
    const btn = document.getElementById('profile-save-button');
    const label = document.getElementById('profile-save-label');
    const spinner = document.getElementById('profile-save-spinner');

    btn.disabled = loading;
    label.classList.toggle('hidden', loading);
    spinner.classList.toggle('hidden', ! loading);
}

export async function initProfilePage() {
    const form = document.getElementById('profile-form');
    const loading = document.getElementById('profile-page-loading');

    if (! form) {
        return;
    }

    await initAdminShell();

    document.getElementById('add-social-link')?.addEventListener('click', () => addSocialRow());
    document.getElementById('add-meta-field')?.addEventListener('click', () => addMetaRow());

    ['profile-name', 'profile-title', 'profile-bio', 'profile-about', 'profile-phone', 'profile-location', 'profile-website']
        .forEach((id) => {
            document.getElementById(id)?.addEventListener('input', updateCompletion);
        });

    document.getElementById('preview-portfolio-button')?.addEventListener('click', (event) => {
        // Public portfolio page is not built yet — keep a gentle notice.
        showToast('Public portfolio preview will open here once Theme/frontend is ready.');
    });

    document.getElementById('avatar-input')?.addEventListener('change', async (event) => {
        const file = event.target.files?.[0];

        if (! file) {
            return;
        }

        if (! file.type.startsWith('image/')) {
            showToast('Please choose an image file.', 'error');
            return;
        }

        const preview = document.getElementById('avatar-preview');
        const status = document.getElementById('avatar-status');
        preview.src = URL.createObjectURL(file);
        preview.dataset.hasAvatar = '1';
        status.textContent = 'Uploading…';
        updateCompletion();

        const body = new FormData();
        body.append('image', file);

        try {
            const { response, payload } = await api('/profile/avatar', {
                method: 'POST',
                body,
            });

            if (! response.ok || ! payload.status) {
                showToast(payload?.message || 'Avatar upload failed.', 'error');
                status.textContent = 'Upload failed. Try again.';
                return;
            }

            if (payload.data?.profile?.avatar_url) {
                preview.src = payload.data.profile.avatar_url;
            }

            status.textContent = 'Avatar updated.';
            showToast(payload.message || 'Avatar uploaded.');
            updateCompletion();
        } catch {
            showToast('Avatar upload failed.', 'error');
            status.textContent = 'Upload failed. Try again.';
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (! validateForm()) {
            showToast('Please fix the highlighted fields.', 'error');
            return;
        }

        setSaving(true);

        const profilePayload = {
            name: document.getElementById('profile-name').value.trim(),
            title: document.getElementById('profile-title').value.trim(),
            bio: document.getElementById('profile-bio').value.trim() || null,
            about: document.getElementById('profile-about').value.trim() || null,
            location: document.getElementById('profile-location').value.trim() || null,
            phone: document.getElementById('profile-phone').value.trim() || null,
            website: document.getElementById('profile-website').value.trim() || null,
            email_public: document.getElementById('email-public').checked,
            phone_public: document.getElementById('phone-public').checked,
        };

        try {
            const profileResult = await api('/profile', {
                method: 'POST',
                body: JSON.stringify(profilePayload),
            });

            if (! profileResult.response.ok || ! profileResult.payload.status) {
                const errors = profileResult.payload?.data?.errors;

                if (errors) {
                    Object.keys(errors).forEach((key) => setError(key, errors[key][0]));
                }

                showToast(profileResult.payload?.message || 'Could not save profile.', 'error');
                return;
            }

            const links = collectSocialLinks();

            if (links.length > 0) {
                const linksResult = await api('/social-links', {
                    method: 'POST',
                    body: JSON.stringify({ links }),
                });

                if (! linksResult.response.ok || ! linksResult.payload.status) {
                    showToast(linksResult.payload?.message || 'Profile saved, but social links failed.', 'error');
                    return;
                }

                document.getElementById('social-links-list').innerHTML = '';
                (linksResult.payload.data.social_links || []).forEach((link) => {
                    addSocialRow({
                        id: String(link.id),
                        platform: link.platform,
                        url: link.url,
                    });
                });
            }

            const meta = collectMeta();

            if (meta.length > 0) {
                const metaResult = await api('/professional-meta', {
                    method: 'POST',
                    body: JSON.stringify({ meta }),
                });

                if (! metaResult.response.ok || ! metaResult.payload.status) {
                    showToast(metaResult.payload?.message || 'Profile saved, but meta fields failed.', 'error');
                    return;
                }

                document.getElementById('meta-fields-list').innerHTML = '';
                (metaResult.payload.data.meta || []).forEach((item) => {
                    addMetaRow({ key: item.key, value: item.value || '' });
                });
            }

            showToast(profileResult.payload.message || 'Profile saved successfully.');
            updateCompletion();
        } catch {
            showToast('Unable to save profile.', 'error');
        } finally {
            setSaving(false);
        }
    });

    try {
        const [{ response, payload }, meResult] = await Promise.all([
            api('/profile', { method: 'GET' }),
            me(),
        ]);

        if (! response.ok || ! payload.status) {
            showToast(payload?.message || 'Could not load profile.', 'error');
            return;
        }

        const accountEmail = meResult.payload?.data?.user?.email || '';
        fillProfile(payload.data, accountEmail);

        loading.classList.add('hidden');
        form.classList.remove('hidden');
    } catch {
        showToast('Could not load profile.', 'error');
    }
}
