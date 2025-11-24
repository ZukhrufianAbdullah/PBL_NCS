document.addEventListener('DOMContentLoaded', function() {
    // Fungsi konfirmasi form sebelum submit (Opsional)
    const saveForms = document.querySelectorAll('form');
    saveForms.forEach(form => {
        const submitButton = form.querySelector('input[type="submit"], button[type="submit"]');
        if (submitButton) {
            form.addEventListener('submit', function() {
                // Tambahkan konfirmasi jika diperlukan
            });
        }
    });
});

// FUNGSI UTAMA DROPDOWN SIDEBAR
function toggleMenu(menuId) {
    const submenu = document.getElementById(menuId);
    const icon = document.getElementById('icon-' + menuId);
    
    if (!submenu || !icon) return;

    // Toggle kelas 'active' untuk menampilkan/menyembunyikan submenu
    submenu.classList.toggle('active');
    icon.classList.toggle('active');
    
    // Opsional: Tutup submenu lain (agar hanya satu yang terbuka)
    const allSubmenus = document.querySelectorAll('.submenu-wrapper');
    allSubmenus.forEach(item => {
        if (item.id !== menuId && item.classList.contains('active')) {
            item.classList.remove('active');
            
            const otherIcon = document.getElementById('icon-' + item.id);
            if (otherIcon) {
                otherIcon.classList.remove('active');
            }
        }
    });
}

