// File: admin/asset/js/script_admin.js
// FIXED VERSION - Dropdown & UI Functions

console.log('🚀 Admin Script Loaded Successfully!');

/**
 * Fungsi untuk toggle dropdown menu di sidebar
 * @param {string} menuId - ID dari submenu wrapper
 */
function toggleMenu(menuId) {
    console.log('toggleMenu called:', menuId);
    
    const submenu = document.getElementById(menuId);
    const icon = document.getElementById('icon-' + menuId);
    
    if (!submenu) {
        console.error('❌ Submenu not found:', menuId);
        return;
    }
    
    if (!icon) {
        console.error('❌ Icon not found:', 'icon-' + menuId);
        return;
    }
    
    // Toggle max-height untuk animasi smooth
    if (submenu.style.maxHeight && submenu.style.maxHeight !== '0px') {
        // Tutup menu
        console.log('🔽 Closing menu:', menuId);
        submenu.style.maxHeight = '0px';
        icon.classList.remove('active');
    } else {
        // Buka menu
        console.log('🔼 Opening menu:', menuId, 'scrollHeight:', submenu.scrollHeight);
        submenu.style.maxHeight = submenu.scrollHeight + 'px';
        icon.classList.add('active');
    }
}

/**
 * Inisialisasi dropdown saat DOM ready
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('📋 DOM Content Loaded');
    
    // Inisialisasi semua submenu dengan max-height 0
    const submenus = document.querySelectorAll('.submenu-wrapper');
    console.log('📂 Found', submenus.length, 'submenus');
    
    submenus.forEach(function(submenu) {
        submenu.style.maxHeight = '0px';
        console.log('✓ Initialized:', submenu.id);
    });
    
    // Auto-open submenu berdasarkan halaman aktif
    const currentPath = window.location.pathname;
    console.log('📍 Current path:', currentPath);
    
    if (currentPath.includes('/profil/')) {
        console.log('🔓 Auto-opening: manajemenKonten');
        toggleMenu('manajemenKonten');
    } else if (currentPath.includes('/galeri/')) {
        console.log('🔓 Auto-opening: galeriMenu');
        toggleMenu('galeriMenu');
    } else if (currentPath.includes('/arsip/')) {
        console.log('🔓 Auto-opening: arsipMenu');
        toggleMenu('arsipMenu');
    } else if (currentPath.includes('/layanan/')) {
        console.log('🔓 Auto-opening: layananMenu');
        toggleMenu('layananMenu');
    }
    
    // Tandai link aktif
    const links = document.querySelectorAll('.sidebar a[href]');
    const currentFile = window.location.pathname.split('/').pop();
    
    links.forEach(function(link) {
        const linkPath = link.getAttribute('href');
        if (linkPath && linkPath.includes(currentFile) && currentFile !== '') {
            link.classList.add('active');
            console.log('✓ Active link:', linkPath);
        }
    });
    
    console.log('✅ Initialization complete');
});

/**
 * Konfirmasi logout
 */
function confirmLogout() {
    return confirm('Apakah Anda yakin ingin keluar dari dashboard admin?');
}

/**
 * Konfirmasi hapus data
 */
function confirmDelete(itemName) {
    return confirm('Apakah Anda yakin ingin menghapus ' + itemName + '?');
}

/**
 * Preview gambar sebelum upload
 */
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    
    if (!preview) {
        console.error('Preview element not found:', previewId);
        return;
    }
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validasi tipe file
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Tipe file tidak diizinkan. Gunakan JPG, PNG, atau GIF.');
            input.value = '';
            return;
        }
        
        // Validasi ukuran file (max 2MB)
        const maxSize = 2 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}

/**
 * Validasi form
 */
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalidField = null;
    
    requiredFields.forEach(function(field) {
        if (!field.value.trim()) {
            field.style.borderColor = 'red';
            isValid = false;
            if (!firstInvalidField) {
                firstInvalidField = field;
            }
        } else {
            field.style.borderColor = '#ccc';
        }
    });
    
    if (!isValid) {
        alert('Mohon lengkapi semua field yang wajib diisi!');
        if (firstInvalidField) {
            firstInvalidField.focus();
        }
    }
    
    return isValid;
}

/**
 * Auto-hide alerts
 */
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-success, .alert-danger, .alert-info');
    
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.style.display = 'none';
            }, 500);
        }, 3000);
    });
});

/**
 * Search table
 */
function searchTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    
    if (!input || !table) return;
    
    const filter = input.value.toUpperCase();
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < cells.length; j++) {
            const cellText = cells[j].textContent || cells[j].innerText;
            if (cellText.toUpperCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }
        
        rows[i].style.display = found ? '' : 'none';
    }
}

console.log('✅ All functions loaded successfully');