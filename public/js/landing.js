const themeToggle = document.getElementById('theme-toggle');
const html = document.documentElement;

const state = {
    search: '',
    filters: {
        type: new Set(),
        act: new Set(),
        rarity: new Set()
    }
};

const searchInput = document.getElementById('searchInput');
const searchButton = document.getElementById('searchButton');
const filtersButton = document.getElementById('filtersButton');
const filtersPanel = document.getElementById('filtersPanel');
const resultsGrid = document.getElementById('resultsGrid');

const savedTheme = localStorage.getItem('theme') || 'light';
html.setAttribute('data-theme', savedTheme);

const themeIcon = themeToggle?.querySelector('i');
if (themeIcon) {
    themeIcon.classList.toggle('fa-moon', savedTheme === 'light');
    themeIcon.classList.toggle('fa-sun', savedTheme === 'dark');
}


function setupButtons() {
    themeToggle?.addEventListener('click', () => {
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        
        const icon = themeToggle.querySelector('i');
        icon.classList.toggle('fa-sun');
        icon.classList.toggle('fa-moon');
    });
    
    searchInput?.addEventListener('input', (e) => {
        state.search = e.target.value.trim();
        console.log(state);
        debounce(updateResults, 300)();
    });
    
    searchButton?.addEventListener('click', () => {
        updateResults();
    });
    
    filtersButton?.addEventListener('click', () => {
        filtersPanel?.classList.toggle('show');
        filtersButton.classList.toggle('active');
    });
    document.querySelectorAll('.filter-btn').forEach(btn => {
        const type = btn.hasAttribute('data-type') ? 'type' : 
                     btn.hasAttribute('data-act') ? 'act' : 
                     btn.hasAttribute('data-rarity') ? 'rarity' : null;
        
        const value = btn.dataset.type || btn.dataset.act || btn.dataset.rarity;
        
        if (!type || !value) return;
        
        if (btn.classList.contains('active')) {
            state.filters[type].add(value);
        }
        
        btn.addEventListener('click', () => {
            btn.classList.toggle('active');
            
            if (btn.classList.contains('active')) {
                state.filters[type].add(value);
            } else {
                state.filters[type].delete(value);
            }
            
            updateResults();
        });
    });
}


function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            console.log('debouncesdf');
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

async function updateResults() {
    const params = new URLSearchParams();
    if (state.search) {
        params.append('search', state.search);
    }
    
    for (const [type, values] of Object.entries(state.filters)) {
        if (values.size > 0) {
            params.append(type, Array.from(values).join(','));
        }
    }
    
    try {
        console.log('Fetching items with params:', params.toString());
        const response = await fetch(`/api/items?${params.toString()}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('API response:', data);
        
        if (!data.success) {
            throw new Error(data.message || 'Failed to fetch items');
        }
        
        displayResults(data.items);
    } catch (error) {
        console.error('Error fetching items:', error);
        resultsGrid.innerHTML = '<p class="error-message">Failed to load items. Please try again.</p>';
    }
}

function displayResults(items) {
    if (!resultsGrid) return;
    
    if (!items || !items.length) {
        resultsGrid.innerHTML = '<p class="no-results">No items found matching your criteria.</p>';
        return;
    }
    
    console.log('Displaying items:', items);
    
    resultsGrid.innerHTML = items.map(item => {
        console.log('Processing item:', item);
        
        const imageHtml = item.image_url 
            ? `<img src="${escapeHtml(item.image_url)}" 
                   alt="${escapeHtml(item.name)}"
                   onerror="this.src='/public/img/default-item.svg'; this.onerror=null;
                          console.log('Image load error for:', this.src);">`
            : `<img src="/public/img/default-item.svg" 
                   alt="Default item image">`;
                   
        return `
        
            <div class="item-card ${item.rarity.toLowerCase()}">
                <div class="item-image">
                    ${imageHtml}
                </div>
                <div class="item-info">
                    <h3>${escapeHtml(item.name)}</h3>
                    <div class="item-meta">
                        <span class="badge badge-${escapeHtml(item.rarity)}">
                        ${escapeHtml(item.rarity)}
                        </span>
                        <span class="badge badge-type">
                        ${escapeHtml(item.type)}
                        </span>
                        <span class="badge badge-act">
                            Act ${escapeHtml(item.act)}
                        </span>
                    </div>
                    <p class="item-description">
                    ${escapeHtml(item.description || '')}
                    </p>
                </div>
            </div>
        `;
    }).join('');
}

function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

const mobileMenuButton = document.querySelector('.mobile-menu-button');
const navSections = document.querySelector('.nav-sections');

mobileMenuButton?.addEventListener('click', () => {
    navSections?.classList.toggle('show');
});

setupButtons();
updateResults(); 