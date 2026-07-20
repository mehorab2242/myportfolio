const TOKEN_KEY = 'auth_token';

export function getToken() {
    return localStorage.getItem(TOKEN_KEY);
}

export function setToken(token) {
    localStorage.setItem(TOKEN_KEY, token);
}

export function clearToken() {
    localStorage.removeItem(TOKEN_KEY);
}

export function isAuthenticated() {
    return Boolean(getToken());
}

/**
 * Fetch wrapper for /api endpoints.
 * Automatically attaches Bearer token when present.
 */
export async function api(path, options = {}) {
    const headers = {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...(options.headers || {}),
    };

    const token = getToken();

    if (token) {
        headers.Authorization = `Bearer ${token}`;
    }

    const response = await fetch(`/api${path}`, {
        ...options,
        headers,
    });

    let payload = null;

    try {
        payload = await response.json();
    } catch {
        payload = {
            status: false,
            message: 'Invalid server response.',
            data: null,
        };
    }

    return { response, payload };
}
