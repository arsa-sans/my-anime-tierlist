// Auto submit form when filter changes (optional)
    document.addEventListener('DOMContentLoaded', function() {
      const filterSelects = document.querySelectorAll('.search-select');
      filterSelects.forEach(select => {
        select.addEventListener('change', function() {
          // Optional: Auto-submit when filter changes
          // this.form.submit();
        });
      });
      
      // Focus on search input when page loads
      const searchInput = document.getElementById('search');
      if (searchInput && !searchInput.value) {
        searchInput.focus();
      }
    });