import { clearToken, logout, me, requireAuth } from '../auth';

/**
 * Shared auth gate for all pages using layouts.dashboard.
 * Also fills the top-bar user chip when /api/me succeeds.
 */
export async function initAdminShell() {
    if (! requireAuth()) {
        return null;
    }

    try {
        const { response, payload } = await me();

        if (! response.ok || ! payload.status) {
            clearToken();
            window.location.href = '/login';
            return null;
        }

        const user = payload.data.user;
        const chip = document.getElementById('sidebar-user-name');

        if (chip) {
            chip.textContent = user.name ?? user.email ?? 'Account';
        }

        return user;
    } catch {
        clearToken();
        window.location.href = '/login';
        return null;
    }
}

export function initDashboardPage() {
    const loadingState = document.getElementById('dashboard-loading');
    const contentState = document.getElementById('dashboard-content');
    const nameEl = document.getElementById('user-name');
    const usernameEl = document.getElementById('user-username');
    const emailEl = document.getElementById('user-email');
    const urlEl = document.getElementById('user-portfolio-url');
    const welcomeEl = document.getElementById('dashboard-welcome');
    const viewBtn = document.getElementById('view-portfolio-btn');

    if (! loadingState && ! contentState) {
        return;
    }

    const showContent = () => {
        loadingState?.classList.add('hidden');
        contentState?.classList.remove('hidden');
    };

    initAdminShell().then((user) => {
        if (! user) {
            return;
        }

        if (welcomeEl) {
            welcomeEl.textContent = `Welcome, ${user.name ?? 'there'}`;
        }
        if (nameEl) nameEl.textContent = user.name ?? '—';
        if (usernameEl) usernameEl.textContent = user.username ?? '—';
        if (emailEl) emailEl.textContent = user.email ?? '—';

        const portfolioUrl = user.portfolio_url || (user.username ? `/${user.username}` : '#');
        if (urlEl) urlEl.textContent = portfolioUrl;
        if (viewBtn) {
            viewBtn.href = portfolioUrl;
            viewBtn.classList.toggle('pointer-events-none', ! user.username);
            viewBtn.classList.toggle('opacity-50', ! user.username);
        }

        showContent();
    });
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
