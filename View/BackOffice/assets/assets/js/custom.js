// ===== SEARCH FUNCTIONALITY =====
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.navbar .search input');
    const tableBody = document.querySelector('table tbody');
    
    if (searchInput && tableBody) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = tableBody.querySelectorAll('tr');
            
            rows.forEach(row => {
                // Skip empty message row
                if (row.querySelector('.empty-message')) {
                    return;
                }
                
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm) || searchTerm === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Show empty message if no results
            const visibleRows = Array.from(rows).filter(r => r.style.display !== 'none');
            const emptyRow = tableBody.querySelector('tr .empty-message');
            if (visibleRows.length === 0 && emptyRow) {
                emptyRow.closest('tr').style.display = '';
            }
        });
    }
});

// ===== FORM VALIDATION & SUBMIT =====
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const inputs = form.querySelectorAll('[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.style.borderColor = '#dc3545';
                    isValid = false;
                } else {
                    input.style.borderColor = '';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires');
            }
        });
    });
});
