<?php
$matchesPage = function ($keys) use ($currentPage) {
    $keys = (array) $keys;
    return in_array($currentPage, $keys, true);
};
?>
<!-- =============== SIDEBAR MOBILE TOGGLE (HANYA UNTUK MOBILE) =============== -->
<button class="sidebar-toggle" data-mobile-toggle aria-label="Toggle Sidebar">
    <span class="toggle-icon"></span>
    <span class="toggle-icon"></span>
    <span class="toggle-icon"></span>
</button>

<!-- OVERLAY HANYA UNTUK MOBILE -->
<div class="sidebar-overlay" data-mobile-overlay></div>

<!-- =============== SIDEBAR UTAMA =============== -->
<aside class="sidebar" data-sidebar>
    <!-- HEADER SIDEBAR DENGAN TOMBOL CLOSE (HANYA MOBILE) -->
    <div class="sidebar-header">
        <h2>ADMIN NCS LAB</h2>
    </div>

    <!-- JUDUL UNTUK DESKTOP -->
    <h2 class="sidebar-title-desktop">ADMIN NCS LAB</h2>

    <!-- =============== MENU UTAMA =============== -->
    <a href="<?php echo $adminBasePath; ?>index.php"
       class="sidebar-link<?php echo $matchesPage('dashboard') ? ' is-active' : ''; ?>"
       data-page="dashboard">
        Dashboard
    </a>

    <div class="menu-header">Pengaturan Tampilan</div>
    <a href="<?php echo $adminBasePath; ?>setting/edit_header.php"
       class="sidebar-link<?php echo $matchesPage('edit_header') ? ' is-active' : ''; ?>"
       data-page="edit_header">
        Header
    </a>
    <a href="<?php echo $adminBasePath; ?>setting/edit_footer.php"
       class="sidebar-link<?php echo $matchesPage('edit_footer') ? ' is-active' : ''; ?>"
       data-page="edit_footer">
        Footer
    </a>
    <a href="<?php echo $adminBasePath; ?>beranda/edit_beranda.php"
       class="sidebar-link<?php echo $matchesPage('edit_beranda') ? ' is-active' : ''; ?>"
       data-page="edit_beranda">
        Beranda
    </a>
    <a href="<?php echo $adminBasePath; ?>beranda/edit_banner.php"
       class="sidebar-link<?php echo $matchesPage('edit_banner') ? ' is-active' : ''; ?>"
       data-page="edit_banner">
        Banner
    </a>

    <div class="menu-header">Manajemen Konten</div>

    <!-- PROFIL DROPDOWN -->
    <div class="dropdown-item">
        <button type="button"
                class="dropdown-toggle<?php echo $matchesPage(['edit_visi_misi','edit_struktur','edit_logo']) ? ' is-open' : ''; ?>"
                data-dropdown-toggle="profilMenu"
                data-page="profil">
            <span>Profil</span>
            <span class="dropdown-icon" aria-hidden="true">&rsaquo;</span>
        </button>
        <div class="submenu-wrapper<?php echo $matchesPage(['edit_visi_misi','edit_struktur','edit_logo']) ? ' is-open' : ''; ?>"
             id="profilMenu">
            <a href="<?php echo $adminBasePath; ?>profil/edit_visi_misi.php"
               class="sidebar-link<?php echo $matchesPage('edit_visi_misi') ? ' is-active' : ''; ?>"
               data-page="edit_visi_misi">
                Visi &amp; Misi
            </a>
            <a href="<?php echo $adminBasePath; ?>profil/edit_struktur.php"
               class="sidebar-link<?php echo $matchesPage('edit_struktur') ? ' is-active' : ''; ?>"
               data-page="edit_struktur">
                Struktur Organisasi
            </a>
            <a href="<?php echo $adminBasePath; ?>profil/edit_logo.php"
               class="sidebar-link<?php echo $matchesPage('edit_logo') ? ' is-active' : ''; ?>"
               data-page="edit_logo">
                Edit Logo
            </a>
        </div>
    </div>

    <!-- GALERI DROPDOWN -->
    <div class="dropdown-item">
        <button type="button"
                class="dropdown-toggle<?php echo $matchesPage(['edit_galeri','edit_agenda']) ? ' is-open' : ''; ?>"
                data-dropdown-toggle="galeriMenu"
                data-page="galeri">
            <span>Galeri</span>
            <span class="dropdown-icon" aria-hidden="true">&rsaquo;</span>
        </button>
        <div class="submenu-wrapper<?php echo $matchesPage(['edit_galeri','edit_agenda']) ? ' is-open' : ''; ?>"
             id="galeriMenu">
           <a href="<?php echo $adminBasePath; ?>galeri/edit_galeri.php"
               class="sidebar-link<?php echo $matchesPage('edit_galeri') ? ' is-active' : ''; ?>"
               data-page="edit_galeri">
                Kelola Galeri
            </a>
           <a href="<?php echo $adminBasePath; ?>galeri/edit_agenda.php"
               class="sidebar-link<?php echo $matchesPage('edit_agenda') ? ' is-active' : ''; ?>"
               data-page="edit_agenda">
                Kelola Agenda
            </a>
        </div>
    </div>

    <!-- ARSIP DROPDOWN -->
    <div class="dropdown-item">
        <button type="button"
                class="dropdown-toggle<?php echo $matchesPage(['edit_penelitian','edit_pengabdian']) ? ' is-open' : ''; ?>"
                data-dropdown-toggle="arsipMenu"
                data-page="arsip">
            <span>Arsip</span>
            <span class="dropdown-icon" aria-hidden="true">&rsaquo;</span>
        </button>
        <div class="submenu-wrapper<?php echo $matchesPage(['edit_penelitian','edit_pengabdian']) ? ' is-open' : ''; ?>"
             id="arsipMenu">
           <a href="<?php echo $adminBasePath; ?>arsip/edit_penelitian.php"
               class="sidebar-link<?php echo $matchesPage('edit_penelitian') ? ' is-active' : ''; ?>"
               data-page="edit_penelitian">
                Kelola Penelitian
            </a>
           <a href="<?php echo $adminBasePath; ?>arsip/edit_pengabdian.php"
               class="sidebar-link<?php echo $matchesPage('edit_pengabdian') ? ' is-active' : ''; ?>"
               data-page="edit_pengabdian">
                Kelola Pengabdian
            </a>
        </div>
    </div>

    <!-- LAYANAN DROPDOWN -->
    <div class="dropdown-item">
        <button type="button"
                class="dropdown-toggle<?php echo $matchesPage(['edit_sarana','lihat_pesan']) ? ' is-open' : ''; ?>"
                data-dropdown-toggle="layananMenu"
                data-page="layanan">
            <span>Layanan</span>
            <span class="dropdown-icon" aria-hidden="true">&rsaquo;</span>
        </button>
        <div class="submenu-wrapper<?php echo $matchesPage(['edit_sarana','lihat_pesan']) ? ' is-open' : ''; ?>"
             id="layananMenu">
            <a href="<?php echo $adminBasePath; ?>layanan/edit_sarana_prasarana.php"
               class="sidebar-link<?php echo $matchesPage('edit_sarana') ? ' is-active' : ''; ?>"
               data-page="edit_sarana">
                Sarana &amp; Prasarana
            </a>
            <a href="<?php echo $adminBasePath; ?>layanan/lihat_pesan.php"
               class="sidebar-link<?php echo $matchesPage('lihat_pesan') ? ' is-active' : ''; ?>"
               data-page="lihat_pesan">
                Pesan Konsultatif
            </a>
        </div>
    </div>

    <!-- LOGOUT -->
    <a href="#" class="sidebar-link" data-page="logout" onclick="return confirmLogout('<?php echo $projectBasePath; ?>user/index.php')">
        Logout
    </a>

</aside>

<!-- =============== JAVASCRIPT UNTUK SIDEBAR =============== -->
<script>
// Logout confirmation
function confirmLogout(logoutUrl) {
    var confirmation = confirm("Apakah Anda yakin ingin logout?");
    if (confirmation) {
        window.location.href = logoutUrl;
        return true;
    }
    return false;
}

// Sidebar functionality
document.addEventListener('DOMContentLoaded', function() {
    // =============== 1. MOBILE SIDEBAR TOGGLE ===============
    const sidebar = document.querySelector('[data-sidebar]');
    const mobileToggleBtn = document.querySelector('[data-mobile-toggle]');
    const mobileCloseBtn = document.querySelector('[data-mobile-close]');
    const mobileOverlay = document.querySelector('[data-mobile-overlay]');
    
    // Function untuk mobile sidebar
    function toggleMobileSidebar() {
        sidebar.classList.toggle('is-open');
        mobileOverlay.classList.toggle('is-active');
        document.body.classList.toggle('sidebar-open');
        
        // Sembunyikan/tampilkan toggle button
        if (window.innerWidth <= 768 && mobileToggleBtn) {
            if (sidebar.classList.contains('is-open')) {
                mobileToggleBtn.style.display = 'none';
            } else {
                mobileToggleBtn.style.display = 'flex';
            }
        }
    }
    
    function closeMobileSidebar() {
        sidebar.classList.remove('is-open');
        mobileOverlay.classList.remove('is-active');
        document.body.classList.remove('sidebar-open');
        
        // Tampilkan kembali toggle button
        if (window.innerWidth <= 768 && mobileToggleBtn) {
            mobileToggleBtn.style.display = 'flex';
        }
    }
    
    // Event listeners
    if (mobileToggleBtn) {
        mobileToggleBtn.addEventListener('click', toggleMobileSidebar);
    }
    
    if (mobileCloseBtn) {
        mobileCloseBtn.addEventListener('click', closeMobileSidebar);
    }
    
    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', closeMobileSidebar);
    }
    
    // Close sidebar when clicking on link (mobile only)
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                closeMobileSidebar();
            }
        });
    });
    
    // Close sidebar on ESC key (mobile only)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && window.innerWidth <= 768) {
            closeMobileSidebar();
        }
    });
    
    // =============== 2. DROPDOWN MENU FUNCTIONALITY ===============
    // Ini bekerja di SEMUA DEVICE (desktop & mobile)
    const dropdownToggles = document.querySelectorAll('[data-dropdown-toggle]');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation(); // Mencegah event bubbling
            
            const targetId = this.getAttribute('data-dropdown-toggle');
            const target = document.getElementById(targetId);
            
            if (target) {
                // Toggle class is-open
                this.classList.toggle('is-open');
                target.classList.toggle('is-open');
            }
        });
    });
    
    // =============== 3. AUTO-OPEN DROPDOWN JIKA PAGE ACTIVE ===============
    // Cari semua link yang active
    const activeLinks = document.querySelectorAll('.sidebar-link.is-active');
    
    if (activeLinks.length > 0) {
        activeLinks.forEach(activeLink => {
            // Cari parent dropdown yang sesuai
            const submenuWrapper = activeLink.closest('.submenu-wrapper');
            
            if (submenuWrapper) {
                // Buka submenu wrapper
                submenuWrapper.classList.add('is-open');
                
                // Buka juga toggle button-nya
                const dropdownToggle = document.querySelector(
                    `[data-dropdown-toggle="${submenuWrapper.id}"]`
                );
                
                if (dropdownToggle) {
                    dropdownToggle.classList.add('is-open');
                }
            }
        });
    }
    
    // =============== 4. CLOSE DROPDOWN WHEN CLICKING OUTSIDE (DESKTOP ONLY) ===============
    if (window.innerWidth > 768) {
        document.addEventListener('click', function(e) {
            // Jika klik di luar dropdown
            if (!e.target.closest('.dropdown-item')) {
                dropdownToggles.forEach(toggle => {
                    const targetId = toggle.getAttribute('data-dropdown-toggle');
                    const target = document.getElementById(targetId);
                    
                    if (target && target.classList.contains('is-open')) {
                        toggle.classList.remove('is-open');
                        target.classList.remove('is-open');
                    }
                });
            }
        });
    }
});

// =============== 5. HANDLE WINDOW RESIZE ===============
window.addEventListener('resize', function() {
    const sidebar = document.querySelector('[data-sidebar]');
    const mobileOverlay = document.querySelector('[data-mobile-overlay]');
    const mobileToggleBtn = document.querySelector('[data-mobile-toggle]');
    
    // Jika resize ke desktop (>768px), close mobile sidebar
    if (window.innerWidth > 768) {
        if (sidebar) sidebar.classList.remove('is-open');
        if (mobileOverlay) mobileOverlay.classList.remove('is-active');
        document.body.classList.remove('sidebar-open');
        
        // Sembunyikan toggle button di desktop
        if (mobileToggleBtn) {
            mobileToggleBtn.style.display = 'none';
        }
    } else {
        // Jika resize ke mobile
        if (mobileToggleBtn && sidebar) {
            if (sidebar.classList.contains('is-open')) {
                // Jika sidebar terbuka, sembunyikan toggle button
                mobileToggleBtn.style.display = 'none';
            } else {
                // Jika sidebar tertutup, tampilkan toggle button
                mobileToggleBtn.style.display = 'flex';
            }
        }
    }
});

// Inisialisasi awal: pastikan toggle button tidak tampil di desktop
window.dispatchEvent(new Event('resize'));
</script>