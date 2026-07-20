import { clearToken, logout, me, requireAuth } from '../auth';

export function initDashboardPage() {
    if (!requireAuth()) {
        return;
    }

    const loadingState = document.getElementById('dashboard-loading');
    const contentState = document.getElementById('dashboard-content');
    const nameEl = document.getElementById('user-name');
    const emailEl = document.getElementById('user-email');
    const roleEl = document.getElementById('user-role');

    const showContent = () => {
        loadingState?.classList.add('hidden');
        contentState?.classList.remove('hidden');
    };

    const loadUser = async () => {
        try {
            const { response, payload } = await me();

            if (!response.ok || !payload.status) {
                clearToken();
                window.location.href = '/login';
                return;
            }

            const user = payload.data.user;

            if (nameEl) nameEl.textContent = user.name ?? '—';
            if (emailEl) emailEl.textContent = user.email ?? '—';
            if (roleEl) roleEl.textContent = user.role ?? '—';

            showContent();
        } catch {
            clearToken();
            window.location.href = '/login';
        }
    };

    loadUser();
}

export function initLogoutButtons() {
    document.querySelectorAll('[data-logout]').forEach((button) => {
        button.addEventListener('click', async (event) => {
            event.preventDefault();
            button.disabled = true;

            try {
                await logout();
            } catch {
                // Still clear local session even if API call fails.
            } finally {
                clearToken();
                window.location.href = '/login';
            }
        });
    });
}
