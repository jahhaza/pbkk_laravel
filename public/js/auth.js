(() => {
    const card = document.getElementById('auth-card');
    if (!card) return;

    const panes = {
        login: document.getElementById('login-pane'),
        register: document.getElementById('register-pane'),
    };
    const overlay = {
        login: document.querySelector('.overlay-right'),
        register: document.querySelector('.overlay-left'),
    };
    const urls = { login: '/login', register: '/register' };

    function setMode(mode, updateUrl = true) {
        if (!panes[mode]) return;
        card.dataset.mode = mode;
        for (const name of Object.keys(panes)) {
            const active = name === mode;
            panes[name].inert = !active;
            panes[name].setAttribute('aria-hidden', String(!active));
            overlay[name].inert = !active;
            overlay[name].setAttribute('aria-hidden', String(!active));
        }
        document.querySelectorAll('.mobile-nav [data-switch]').forEach(button => {
            button.setAttribute('aria-pressed', String(button.dataset.switch === mode));
        });
        document.title = (mode === 'login' ? 'Masuk' : 'Daftar') + ' — Laravel Registrasi';
        if (updateUrl && location.pathname !== urls[mode]) {
            history.pushState({ mode }, '', urls[mode]);
        }
    }

    document.querySelectorAll('[data-switch]').forEach(button => {
        button.addEventListener('click', () => setMode(button.dataset.switch));
    });
    addEventListener('popstate', () => {
        setMode(location.pathname === urls.login ? 'login' : 'register', false);
    });
    setMode(card.dataset.mode, false);

    document.querySelectorAll('[data-reveal]').forEach(button => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.reveal);
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            button.textContent = show ? 'Tutup' : 'Lihat';
            button.setAttribute('aria-pressed', String(show));
            button.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
            input.focus();
        });
    });

    const registerForm = document.getElementById('register-form');
    const name = document.getElementById('register-name');
    const email = document.getElementById('register-email');
    const password = document.getElementById('register-password');
    const confirmation = document.getElementById('register-confirmation');
    const strength = document.getElementById('strength');
    const strengthLabel = document.getElementById('strength-label');
    const touched = new Set();
    const fields = {
        name: { input: name, live: document.getElementById('register-name-live') },
        email: { input: email, live: document.getElementById('register-email-live') },
        password: { input: password, live: document.getElementById('register-password-live') },
        confirmation: { input: confirmation, live: document.getElementById('register-confirmation-live') },
    };

    function validate() {
        return {
            name: {
                valid: name.value.trim().length > 0 && name.value.trim().length <= 255,
                error: 'Nama wajib diisi.',
                success: 'Nama sudah diisi.',
            },
            email: {
                valid: /^[^\s@]+@gmail\.com$/i.test(email.value.trim()),
                error: 'Gunakan alamat @gmail.com yang valid.',
                success: 'Format Gmail sudah benar.',
            },
            password: {
                valid: password.value.length >= 8,
                error: 'Minimal 8 karakter.',
                success: 'Panjang kata sandi sesuai.',
            },
            confirmation: {
                valid: confirmation.value.length > 0 && confirmation.value === password.value,
                error: 'Kata sandi belum sama.',
                success: 'Kata sandi cocok.',
            },
        };
    }

    function update() {
        const results = validate();
        for (const [key, field] of Object.entries(fields)) {
            const result = results[key];
            const wrapper = field.input.closest('.field');
            const show = touched.has(key) || field.input.value.length > 0;
            wrapper.classList.toggle('valid', show && result.valid);
            wrapper.classList.toggle('invalid', show && !result.valid);
            field.input.setAttribute('aria-invalid', String(show && !result.valid));
            field.live.textContent = show ? (result.valid ? result.success : result.error) : '';
        }
        const value = password.value;
        let score = 0;
        if (value) {
            score = 1;
            if (value.length >= 8) score++;
            if (/[a-z]/.test(value) && /[A-Z]/.test(value)) score++;
            if (/\d|[^A-Za-z]/.test(value)) score++;
        }
        strength.dataset.score = String(score);
        strengthLabel.textContent = ['Belum diisi', 'Dasar', 'Cukup', 'Bagus', 'Kuat'][score];
        return results;
    }

    for (const [key, field] of Object.entries(fields)) {
        field.input.addEventListener('input', () => {
            touched.add(key);
            const wrapper = field.input.closest('.field');
            wrapper.classList.remove('server-error');
            wrapper.querySelector('.field-error')?.remove();
            update();
        });
        field.input.addEventListener('blur', () => { touched.add(key); update(); });
    }

    registerForm.addEventListener('submit', event => {
        Object.keys(fields).forEach(key => touched.add(key));
        const results = update();
        const firstInvalid = Object.keys(fields).find(key => !results[key].valid);
        if (firstInvalid) {
            event.preventDefault();
            fields[firstInvalid].input.focus();
            return;
        }
        const submit = registerForm.querySelector('.primary-button');
        submit.disabled = true;
        submit.firstChild.textContent = 'Menyimpan akun ';
    });

    const loginForm = document.getElementById('login-form');
    loginForm.addEventListener('submit', event => {
        const loginEmail = document.getElementById('login-email');
        const loginPassword = document.getElementById('login-password');
        const invalid = !loginEmail.value.trim() || !loginEmail.validity.valid
            ? loginEmail
            : !loginPassword.value ? loginPassword : null;
        if (invalid) {
            event.preventDefault();
            invalid.closest('.field').classList.add('invalid');
            invalid.focus();
            return;
        }
        const submit = loginForm.querySelector('.primary-button');
        submit.disabled = true;
        submit.firstChild.textContent = 'Memeriksa akun ';
    });
    update();
})();
