import { redirectIfAuthenticated, register, setToken } from '../auth';

function firstValidationError(errors) {
    if (! errors || typeof errors !== 'object') {
        return null;
    }

    const firstKey = Object.keys(errors)[0];

    return firstKey ? errors[firstKey][0] : null;
}

export function initRegisterPage() {
    redirectIfAuthenticated();

    const form = document.getElementById('register-form');
    const errorBox = document.getElementById('register-error');
    const submitBtn = document.getElementById('register-button');
    const buttonLabel = document.getElementById('register-button-label');
    const buttonSpinner = document.getElementById('register-button-spinner');
    const usernameInput = document.getElementById('username');

    if (! form) {
        return;
    }

    usernameInput?.addEventListener('input', () => {
        usernameInput.value = usernameInput.value.toLowerCase().replace(/[^a-z0-9_-]/g, '');
    });

    const setLoading = (loading) => {
        submitBtn.disabled = loading;
        buttonLabel.classList.toggle('hidden', loading);
        buttonSpinner.classList.toggle('hidden', ! loading);
    };

    const showError = (message) => {
        errorBox.textContent = message;
        errorBox.classList.remove('hidden');
    };

    const hideError = () => {
        errorBox.textContent = '';
        errorBox.classList.add('hidden');
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        hideError();
        setLoading(true);

        try {
            const { response, payload } = await register({
                name: document.getElementById('name').value.trim(),
                username: document.getElementById('username').value.trim().toLowerCase(),
                email: document.getElementById('email').value.trim(),
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value,
            });

            if (! response.ok || ! payload.status) {
                const validationMessage = firstValidationError(payload?.data?.errors);
                showError(validationMessage || payload?.message || 'Registration failed.');
                return;
            }

            setToken(payload.data.token);
            window.location.href = '/admin';
        } catch {
            showError('Unable to connect to the server. Please try again.');
        } finally {
            setLoading(false);
        }
    });
}
