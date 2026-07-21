/**
 * Mobile sidebar open/close for the admin dashboard layout.
 */
export function initAdminSidebar() {
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('admin-sidebar-overlay');
    const toggle = document.getElementById('admin-sidebar-toggle');

    if (!sidebar || !toggle) {
        return;
    }

    const setOpen = (open) => {
        sidebar.classList.toggle('-translate-x-full', !open);
        overlay?.classList.toggle('hidden', !open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
        document.body.classList.toggle('overflow-hidden', open && window.innerWidth < 1024);
    };

    toggle.addEventListener('click', () => {
        const isClosed = sidebar.classList.contains('-translate-x-full');
        setOpen(isClosed);
    });

    overlay?.addEventListener('click', () => setOpen(false));

    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setOpen(false);
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            setOpen(false);
            sidebar.classList.remove('-translate-x-full');
        } else if (toggle.getAttribute('aria-expanded') !== 'true') {
            sidebar.classList.add('-translate-x-full');
        }
    });
}
