import { isAuthenticated } from './auth';
import { initLoginPage } from './pages/login';
import { initDashboardPage, initLogoutButtons } from './pages/dashboard';

document.addEventListener('DOMContentLoaded', () => {
    const page = document.body.dataset.page;

    // Toggle navbar logout visibility based on token.
    document.querySelectorAll('[data-auth-only]').forEach((el) => {
        el.classList.toggle('hidden', !isAuthenticated());
    });

    document.querySelectorAll('[data-guest-only]').forEach((el) => {
        el.classList.toggle('hidden', isAuthenticated());
    });

    if (page === 'login') {
        initLoginPage();
    }

    if (page === 'dashboard') {
        initDashboardPage();
    }

    initLogoutButtons();
});
