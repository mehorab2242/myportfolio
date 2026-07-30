import { login, redirectIfAuthenticated, setToken } from '../auth';

const REMEMBER_LOGIN_KEY = 'remember_login';

function firstValidationError(errors) {
    if (! errors || typeof errors !== 'object') {
        return null;
    }

    const firstKey = Object.keys(errors)[0];

    return firstKey ? errors[firstKey][0] : null;
}

function initPasswordToggle() {
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.getElementById('toggle-password');
    const iconEye = document.getElementById('icon-eye');
    const iconEyeOff = document.getElementById('icon-eye-off');

    if (! passwordInput || ! toggleBtn) {
        return;
    }

    toggleBtn.addEventListener('click', () => {
        const showing = passwordInput.type === 'text';
        passwordInput.type = showing ? 'password' : 'text';
        iconEye?.classList.toggle('hidden', ! showing);
        iconEyeOff?.classList.toggle('hidden', showing);
        toggleBtn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
    });
}

function initRememberMe(loginInput, rememberInput) {
    const saved = localStorage.getItem(REMEMBER_LOGIN_KEY);

    if (saved && loginInput) {
        loginInput.value = saved;
        if (rememberInput) {
            rememberInput.checked = true;
        }
    }
}

export function initLoginPage() {
    redirectIfAuthenticated();

    const form = document.getElementById('login-form');
    const loginInput = document.getElementById('login');
    const passwordInput = document.getElementById('password');
    const rememberInput = document.getElementById('remember');
    const submitBtn = document.getElementById('login-button');
    const errorBox = document.getElementById('login-error');
    const buttonLabel = document.getElementById('login-button-label');
    const buttonSpinner = document.getElementById('login-button-spinner');

    if (! form) {
        return;
    }

    initPasswordToggle();
    initRememberMe(loginInput, rememberInput);

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
            const loginValue = loginInput.value.trim();
            const { response, payload } = await login(loginValue, passwordInput.value);

            if (! response.ok || ! payload.status) {
                const validationMessage = firstValidationError(payload?.data?.errors);
                showError(validationMessage || payload?.message || 'Login failed.');
                return;
            }

            if (rememberInput?.checked) {
                localStorage.setItem(REMEMBER_LOGIN_KEY, loginValue);
            } else {
                localStorage.removeItem(REMEMBER_LOGIN_KEY);
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
