// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInputs = document.querySelectorAll('.inner-search input');
    
    searchInputs.forEach(searchInput => {
        searchInput.addEventListener('keyup', function(e) {
            const searchTerm = this.value.toLowerCase();
            const table = document.querySelector('table');
            
            if (!table) return;
            
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    });
});

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const btnMenu = document.querySelector('.inner-button-menu');
    const sider = document.querySelector('.sider');
    const overlay = document.querySelector('.sider-overlay');

    if (btnMenu && sider && overlay) {
        btnMenu.addEventListener('click', () => {
            sider.classList.add('active');
            overlay.classList.add('active');
        });

        overlay.addEventListener('click', () => {
            sider.classList.remove('active');
            overlay.classList.remove('active');
        });
    }
});
