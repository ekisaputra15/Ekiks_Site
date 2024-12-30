// assets/js/script.js
document.addEventListener('DOMContentLoaded', function() {
    // Add any custom JavaScript functionality here
    
    // Example: Add event listener to delete buttons
    var deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(function(button) {
      button.addEventListener('click', function(event) {
        event.preventDefault();
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
          window.location.href = this.getAttribute('href');
        }
      });
    });
  });