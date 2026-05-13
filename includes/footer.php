    <!-- Main JS logic cho Mobile Menu, Search & Alerts -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Mobile Menu
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

            // 2. Auto hide alerts after 3 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = "opacity 0.5s ease";
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 500);
                }, 3000);
            });

            // 3. Client-side Table Search Filter
            const searchInput = document.querySelector('.inner-search input');
            const tableRows = document.querySelectorAll('table tbody tr');
            if(searchInput && tableRows.length > 0) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    tableRows.forEach(row => {
                        if (row.querySelector('td[colspan]')) return;
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }
        });
    </script>
</body>
</html>