/**
 * Mirror of config/admin_navigation.php for future SPA / API-driven menus.
 * Filter with filterSidebarMenu({ role, modules }).
 */
export const sidebarMenu = [
    { key: 'dashboard', label: 'Dashboard', href: '/admin', icon: 'layout-dashboard', roles: ['admin', 'user'], modules: [] },
    { key: 'profile', label: 'Profile', href: '/admin/profile', icon: 'user', roles: ['admin', 'user'], modules: [] },
    { key: 'sections', label: 'Sections', href: '/admin/sections', icon: 'layout-template', roles: ['admin', 'user'], modules: ['portfolio'] },
    { key: 'skills', label: 'Skills', href: '/admin/skills', icon: 'sparkles', roles: ['admin', 'user'], modules: ['portfolio'] },
    { key: 'projects', label: 'Projects', href: '/admin/projects', icon: 'folder-kanban', roles: ['admin', 'user'], modules: ['portfolio'] },
    { key: 'experience', label: 'Experience', href: '/admin/experience', icon: 'briefcase', roles: ['admin', 'user'], modules: ['portfolio'] },
    { key: 'education', label: 'Education', href: '/admin/education', icon: 'graduation-cap', roles: ['admin', 'user'], modules: ['portfolio'] },
    { key: 'theme', label: 'Theme', href: '/admin/theme', icon: 'palette', roles: ['admin', 'user'], modules: ['portfolio'] },
    { key: 'settings', label: 'Settings', href: '/admin/settings', icon: 'settings', roles: ['admin', 'user'], modules: [] },
];

export function filterSidebarMenu({ role = null, modules = null } = {}) {
    return sidebarMenu.filter((item) => {
        if (role && item.roles?.length && !item.roles.includes(role)) {
            return false;
        }

        if (modules && item.modules?.length) {
            const overlap = item.modules.some((module) => modules.includes(module));
            if (!overlap) {
                return false;
            }
        }

        return true;
    });
}
