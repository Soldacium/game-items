const themeToggle = document.getElementById('themeToggle');
const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');

let currentTheme = localStorage.getItem('theme');
if (!currentTheme) {
    currentTheme = prefersDarkScheme.matches ? 'dark' : 'light';
    localStorage.setItem('theme', currentTheme);
}

document.body.setAttribute('data-theme', currentTheme);
updateThemeIcon();

themeToggle.addEventListener('click', () => {
    currentTheme = currentTheme === 'light' ? 'dark' : 'light';
    document.body.setAttribute('data-theme', currentTheme);
    localStorage.setItem('theme', currentTheme);
    updateThemeIcon();
});

function updateThemeIcon() {
    const icon = themeToggle.querySelector('svg');
    if (currentTheme === 'dark') {
        icon.innerHTML = `
            <circle cx="12" cy="12" r="5"/>
            <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
        `;
    } else {
        icon.innerHTML = `
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        `;
    }
}

const userMenuBtn = document.getElementById('userMenuBtn');
const userMenuContent = document.getElementById('userMenuContent');

if (userMenuBtn && userMenuContent) {
    userMenuBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        userMenuContent.classList.toggle('show');
    });

    document.addEventListener('click', (e) => {
        if (!userMenuContent.contains(e.target) && !userMenuBtn.contains(e.target)) {
            userMenuContent.classList.remove('show');
        }
    });
} 