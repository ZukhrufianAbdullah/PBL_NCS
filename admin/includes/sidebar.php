<?php
$matchesPage = function ($keys) use ($currentPage) {
    $keys = (array) $keys;
    return in_array($currentPage, $keys, true);
};
?>
<aside class="sidebar" data-sidebar>
    <h2>ADMIN NCS LAB</h2>

    <a href="<?php echo $adminBasePath; ?>index.php"
       class="sidebar-link<?php echo $matchesPage('dashboard') ? ' is-active' : ''; ?>">
        Dashboard
    </a>

    <div class="menu-header">Pengaturan Tampilan</div>
    <a href="<?php echo $adminBasePath; ?>setting/edit_header.php"
       class="sidebar-link<?php echo $matchesPage('edit_header') ? ' is-active' : ''; ?>">
        Edit Header
    </a>
    <a href="<?php echo $adminBasePath; ?>setting/edit_footer.php"
       class="sidebar-link<?php echo $matchesPage('edit_footer') ? ' is-active' : ''; ?>">
        Edit Footer
    </a>
    <a href="<?php echo $adminBasePath; ?>beranda/edit_beranda.php"
       class="sidebar-link<?php echo $matchesPage('edit_beranda') ? ' is-active' : ''; ?>">
        Edit Beranda
    </a>
    <a href="<?php echo $adminBasePath; ?>beranda/edit_banner.php"
       class="sidebar-link<?php echo $matchesPage('edit_banner') ? ' is-active' : ''; ?>">
        Edit Banner
    </a>

    <div class="menu-header">Manajemen Konten</div>

    <div class="dropdown-item">
        <button type="button"
                class="dropdown-toggle<?php echo $matchesPage(['edit_visi_misi','edit_struktur','edit_logo']) ? ' is-open' : ''; ?>"
                data-sidebar-toggle="profilMenu">
            <span>Profil</span>
            <span class="dropdown-icon" aria-hidden="true">&rsaquo;</span>
        </button>
        <div class="submenu-wrapper<?php echo $matchesPage(['edit_visi_misi','edit_struktur','edit_logo']) ? ' is-open' : ''; ?>"
             id="profilMenu">
            <a href="<?php echo $adminBasePath; ?>profil/edit_visi_misi.php"
               class="sidebar-link<?php echo $matchesPage('edit_visi_misi') ? ' is-active' : ''; ?>">
                Visi &amp; Misi
            </a>
            <a href="<?php echo $adminBasePath; ?>profil/edit_struktur.php"
               class="sidebar-link<?php echo $matchesPage('edit_struktur') ? ' is-active' : ''; ?>">
                Struktur Organisasi
            </a>
            <a href="<?php echo $adminBasePath; ?>profil/edit_logo.php"
               class="sidebar-link<?php echo $matchesPage('edit_logo') ? ' is-active' : ''; ?>">
                Edit Logo
            </a>
        </div>
    </div>

    <div class="dropdown-item">
        <button type="button"
                class="dropdown-toggle<?php echo $matchesPage(['edit_galeri','edit_agenda']) ? ' is-open' : ''; ?>"
                data-sidebar-toggle="galeriMenu">
            <span>Galeri</span>
            <span class="dropdown-icon" aria-hidden="true">&rsaquo;</span>
        </button>
        <div class="submenu-wrapper<?php echo $matchesPage(['edit_galeri','edit_agenda']) ? ' is-open' : ''; ?>"
             id="galeriMenu">
            <div class="menu-subheader">Galeri Foto</div>
            <a href="<?php echo $adminBasePath; ?>galeri/edit_galeri.php"
               class="sidebar-link<?php echo $matchesPage('edit_galeri') ? ' is-active' : ''; ?>">
                Kelola Galeri
            </a>
            <div class="menu-subheader">Agenda</div>
            <a href="<?php echo $adminBasePath; ?>galeri/edit_agenda.php"
               class="sidebar-link<?php echo $matchesPage('edit_agenda') ? ' is-active' : ''; ?>">
                Kelola Agenda
            </a>
        </div>
    </div>

    <div class="dropdown-item">
        <button type="button"
                class="dropdown-toggle<?php echo $matchesPage(['tambah_penelitian','edit_penelitian','tambah_pengabdian','edit_pengabdian']) ? ' is-open' : ''; ?>"
                data-sidebar-toggle="arsipMenu">
            <span>Arsip</span>
            <span class="dropdown-icon" aria-hidden="true">&rsaquo;</span>
        </button>
        <div class="submenu-wrapper<?php echo $matchesPage(['tambah_penelitian','edit_penelitian','tambah_pengabdian','edit_pengabdian']) ? ' is-open' : ''; ?>"
             id="arsipMenu">
            <div class="menu-subheader">Penelitian</div>
            <a href="<?php echo $adminBasePath; ?>arsip/tambah_penelitian.php"
               class="sidebar-link<?php echo $matchesPage('tambah_penelitian') ? ' is-active' : ''; ?>">
                Tambah Penelitian
            </a>
            <a href="<?php echo $adminBasePath; ?>arsip/edit_penelitian.php"
               class="sidebar-link<?php echo $matchesPage('edit_penelitian') ? ' is-active' : ''; ?>">
                Kelola Penelitian
            </a>
            <div class="menu-subheader">Pengabdian</div>
            <a href="<?php echo $adminBasePath; ?>arsip/tambah_pengabdian.php"
               class="sidebar-link<?php echo $matchesPage('tambah_pengabdian') ? ' is-active' : ''; ?>">
                Tambah Pengabdian
            </a>
            <a href="<?php echo $adminBasePath; ?>arsip/edit_pengabdian.php"
               class="sidebar-link<?php echo $matchesPage('edit_pengabdian') ? ' is-active' : ''; ?>">
                Kelola Pengabdian
            </a>
        </div>
    </div>

    <div class="dropdown-item">
        <button type="button"
                class="dropdown-toggle<?php echo $matchesPage(['edit_sarana','lihat_pesan']) ? ' is-open' : ''; ?>"
                data-sidebar-toggle="layananMenu">
            <span>Layanan</span>
            <span class="dropdown-icon" aria-hidden="true">&rsaquo;</span>
        </button>
        <div class="submenu-wrapper<?php echo $matchesPage(['edit_sarana','lihat_pesan']) ? ' is-open' : ''; ?>"
             id="layananMenu">
            <a href="<?php echo $adminBasePath; ?>layanan/edit_sarana_prasarana.php"
               class="sidebar-link<?php echo $matchesPage('edit_sarana') ? ' is-active' : ''; ?>">
                Sarana &amp; Prasarana
            </a>
            <a href="<?php echo $adminBasePath; ?>layanan/lihat_pesan.php"
               class="sidebar-link<?php echo $matchesPage('lihat_pesan') ? ' is-active' : ''; ?>">
                Pesan Konsultatif
            </a>
        </div>
    </div>

    <a href="<?php echo $projectBasePath; ?>user/index.php" class="sidebar-link">
        Logout
    </a>

</aside>

