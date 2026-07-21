export const DEFAULT_ADMIN_THEME = {
    primary: '#0d9488',
    secondary: '#14b8a6',
};

export const ADMIN_THEME_STORAGE_KEY = 'admin_brand_theme';

/**
 * Normalize user/API hex to #rrggbb or null if invalid.
 */
export function normalizeHex(value) {
    if (value === null || value === undefined) {
        return null;
    }

    let hex = String(value).trim().toLowerCase();

    if (hex === '') {
        return null;
    }

    if (!hex.startsWith('#')) {
        hex = `#${hex}`;
    }

    if (!/^#[0-9a-f]{6}$/.test(hex)) {
        return null;
    }

    return hex;
}

/**
 * Apply admin-only brand colours to CSS variables on :root.
 * Does not affect Theme (frontend) module.
 */
export function applyAdminTheme(primary, secondary) {
    const p = normalizeHex(primary) || DEFAULT_ADMIN_THEME.primary;
    const s = normalizeHex(secondary) || DEFAULT_ADMIN_THEME.secondary;
    const root = document.documentElement;

    root.style.setProperty('--color-primary', p);
    root.style.setProperty('--color-primary-hover', `color-mix(in srgb, ${p} 85%, #000)`);
    root.style.setProperty('--color-secondary', s);
    root.style.setProperty('--color-ring', p);

    try {
        localStorage.setItem(
            ADMIN_THEME_STORAGE_KEY,
            JSON.stringify({ admin_primary: p, admin_secondary: s }),
        );
    } catch {
        // Ignore storage failures (private mode, etc.).
    }

    return { admin_primary: p, admin_secondary: s };
}

/**
 * Apply cached admin theme early to reduce flash.
 */
export function applyCachedAdminTheme() {
    try {
        const raw = localStorage.getItem(ADMIN_THEME_STORAGE_KEY);

        if (!raw) {
            return null;
        }

        const parsed = JSON.parse(raw);

        return applyAdminTheme(parsed.admin_primary, parsed.admin_secondary);
    } catch {
        return null;
    }
}
