import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

function getSystemTheme() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

function applyTheme(theme) {
    const resolved = theme === 'system' ? getSystemTheme() : theme;
    document.documentElement.setAttribute('data-theme', resolved);
    if (resolved === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

const saved = localStorage.getItem('theme') || 'dark';
applyTheme(saved);

window.applyTheme = applyTheme;

Alpine.start();
