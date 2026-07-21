import { isAuthenticated } from './auth';
import { initLoginPage } from './pages/login';
import { initAdminShell, initDashboardPage, initLogoutButtons } from './pages/dashboard';
import { initSettingsPage, loadAdminThemeFromApi } from './pages/settings';
import { initProfilePage } from './pages/profile';
import { initSkillsPage } from './pages/skills';
import { initProjectsPage } from './pages/projects';
import { initAdminSidebar } from './sidebar';

document.addEventListener('DOMContentLoaded', () => {
    const page = document.body.dataset.page;
    const layout = document.body.dataset.layout;

    document.querySelectorAll('[data-auth-only]').forEach((el) => {
        el.classList.toggle('hidden', !isAuthenticated());
    });

    document.querySelectorAll('[data-guest-only]').forEach((el) => {
        el.classList.toggle('hidden', isAuthenticated());
    });

    if (page === 'login') {
        initLoginPage();
    }

    if (layout === 'dashboard') {
        initAdminSidebar();
        initLogoutButtons();
        loadAdminThemeFromApi();

        if (page === 'dashboard') {
            initDashboardPage();
        } else if (page === 'settings') {
            initAdminShell();
            initSettingsPage();
        } else if (page === 'profile') {
            initProfilePage();
        } else if (page === 'skills') {
            initSkillsPage();
        } else if (page === 'projects') {
            initProjectsPage();
        } else {
            initAdminShell();
        }
    }
});
