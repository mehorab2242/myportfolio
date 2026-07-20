import { api, clearToken, getToken, isAuthenticated, setToken } from './api';

export { clearToken, getToken, isAuthenticated, setToken };

export async function login(email, password) {
    return api('/login', {
        method: 'POST',
        body: JSON.stringify({ email, password }),
    });
}

export async function logout() {
    return api('/logout', {
        method: 'POST',
    });
}

export async function me() {
    return api('/me', {
        method: 'GET',
    });
}

/**
 * Redirect to login if no token exists.
 * Returns false when redirected.
 */
export function requireAuth() {
    if (!isAuthenticated()) {
        window.location.href = '/login';
        return false;
    }

    return true;
}

/**
 * Redirect to dashboard if already logged in.
 */
export function redirectIfAuthenticated() {
    if (isAuthenticated()) {
        window.location.href = '/dashboard';
        return true;
    }

    return false;
}
