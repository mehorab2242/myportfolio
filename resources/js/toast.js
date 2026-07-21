/**
 * Lightweight toast notifications for the admin panel.
 */
export function showToast(message, type = 'success') {
    const host = document.getElementById('toast-host');

    if (! host) {
        return;
    }

    const el = document.createElement('div');
    const styles = type === 'error'
        ? 'border-rose-200 bg-rose-50 text-rose-800'
        : 'border-emerald-200 bg-emerald-50 text-emerald-800';

    el.className = `pointer-events-auto mb-2 w-full max-w-sm rounded-xl border px-4 py-3 text-sm shadow-lg transition ${styles}`;
    el.setAttribute('role', 'status');
    el.textContent = message;

    host.appendChild(el);

    window.setTimeout(() => {
        el.classList.add('opacity-0');
        window.setTimeout(() => el.remove(), 250);
    }, 3200);
}
