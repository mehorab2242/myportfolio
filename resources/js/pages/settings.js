import { api } from '../api';
import { applyAdminTheme, applyCachedAdminTheme, normalizeHex } from '../theme';

function firstError(errors, field) {
    return errors?.[field]?.[0] ?? null;
}

export async function loadAdminThemeFromApi() {
    applyCachedAdminTheme();

    try {
        const { response, payload } = await api('/settings', { method: 'GET' });

        if (!response.ok || !payload.status) {
            return null;
        }

        return applyAdminTheme(payload.data.admin_primary, payload.data.admin_secondary);
    } catch {
        return null;
    }
}

export function initSettingsPage() {
    const form = document.getElementById('admin-brand-form');

    if (!form) {
        return;
    }

    const primaryColor = document.getElementById('admin_primary_color');
    const primaryText = document.getElementById('admin_primary');
    const secondaryColor = document.getElementById('admin_secondary_color');
    const secondaryText = document.getElementById('admin_secondary');
    const primaryError = document.getElementById('admin_primary_error');
    const secondaryError = document.getElementById('admin_secondary_error');
    const formError = document.getElementById('settings-form-error');
    const formSuccess = document.getElementById('settings-form-success');
    const saveBtn = document.getElementById('settings-save-button');
    const saveLabel = document.getElementById('settings-save-label');
    const saveSpinner = document.getElementById('settings-save-spinner');
    const previewPrimary = document.getElementById('preview-primary');
    const previewSecondary = document.getElementById('preview-secondary');
    const previewGradient = document.getElementById('preview-gradient');

    const setFieldError = (el, message) => {
        if (!el) {
            return;
        }

        if (message) {
            el.textContent = message;
            el.classList.remove('hidden');
        } else {
            el.textContent = '';
            el.classList.add('hidden');
        }
    };

    const setLoading = (loading) => {
        saveBtn.disabled = loading;
        saveLabel.classList.toggle('hidden', loading);
        saveSpinner.classList.toggle('hidden', !loading);
    };

    const syncPair = (colorInput, textInput, value) => {
        const hex = normalizeHex(value) || value;

        if (normalizeHex(value)) {
            colorInput.value = hex;
            textInput.value = hex;
        } else if (typeof value === 'string') {
            textInput.value = value;
        }
    };

    const updatePreview = () => {
        const p = normalizeHex(primaryText.value) || primaryColor.value;
        const s = normalizeHex(secondaryText.value) || secondaryColor.value;

        if (previewPrimary) {
            previewPrimary.style.backgroundColor = p;
        }

        if (previewSecondary) {
            previewSecondary.style.backgroundColor = s;
        }

        if (previewGradient) {
            previewGradient.style.backgroundImage = `linear-gradient(to right, ${p}, ${s})`;
        }

        // Live preview across admin shell while editing (not saved until submit).
        applyAdminTheme(p, s);
    };

    const bindPair = (colorInput, textInput) => {
        colorInput.addEventListener('input', () => {
            textInput.value = colorInput.value.toLowerCase();
            updatePreview();
        });

        textInput.addEventListener('input', () => {
            const hex = normalizeHex(textInput.value);

            if (hex) {
                colorInput.value = hex;
            }

            updatePreview();
        });

        textInput.addEventListener('blur', () => {
            const hex = normalizeHex(textInput.value);

            if (hex) {
                syncPair(colorInput, textInput, hex);
                updatePreview();
            }
        });
    };

    bindPair(primaryColor, primaryText);
    bindPair(secondaryColor, secondaryText);

    const hydrate = async () => {
        try {
            const { response, payload } = await api('/settings', { method: 'GET' });

            if (!response.ok || !payload.status) {
                setFieldError(formError, payload?.message || 'Unable to load settings.');
                return;
            }

            const primary = payload.data.admin_primary;
            const secondary = payload.data.admin_secondary;

            syncPair(primaryColor, primaryText, primary);
            syncPair(secondaryColor, secondaryText, secondary);
            applyAdminTheme(primary, secondary);
            updatePreview();
        } catch {
            setFieldError(formError, 'Unable to connect to the server.');
        }
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        setFieldError(formError, null);
        setFieldError(formSuccess, null);
        setFieldError(primaryError, null);
        setFieldError(secondaryError, null);

        const primary = normalizeHex(primaryText.value);
        const secondary = normalizeHex(secondaryText.value);

        if (!primary) {
            setFieldError(primaryError, 'Enter a valid hex colour, e.g. #0d9488.');
            return;
        }

        if (!secondary) {
            setFieldError(secondaryError, 'Enter a valid hex colour, e.g. #0d9488.');
            return;
        }

        setLoading(true);

        try {
            const { response, payload } = await api('/admin/settings', {
                method: 'PUT',
                body: JSON.stringify({
                    admin_primary: primary,
                    admin_secondary: secondary,
                }),
            });

            if (!response.ok || !payload.status) {
                const errors = payload?.data?.errors;
                setFieldError(primaryError, firstError(errors, 'admin_primary'));
                setFieldError(secondaryError, firstError(errors, 'admin_secondary'));
                setFieldError(formError, payload?.message || 'Unable to save settings.');
                return;
            }

            applyAdminTheme(payload.data.admin_primary, payload.data.admin_secondary);
            syncPair(primaryColor, primaryText, payload.data.admin_primary);
            syncPair(secondaryColor, secondaryText, payload.data.admin_secondary);
            updatePreview();

            if (formSuccess) {
                formSuccess.textContent = payload.message || 'Saved.';
                formSuccess.classList.remove('hidden');
            }
        } catch {
            setFieldError(formError, 'Unable to connect to the server.');
        } finally {
            setLoading(false);
        }
    });

    hydrate();
}
