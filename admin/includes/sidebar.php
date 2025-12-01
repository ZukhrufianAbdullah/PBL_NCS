<?php
$matchesPage = function ($keys) use ($currentPage) {
    $keys = (array) $keys;
    return in_array($currentPage, $keys, true);
};
?>
<aside class="sidebar" data-sidebar>
    <h2>ADMIN NCS LAB</h2>

    <a href="<?php echo $adminBasePath; ?>index.php"
       class="sidebar-link<?php echo $matchesPage('dashboard') ? ' is-active' : ''; ?>"
       data-page="dashboard">
        Dashboard
    </a>

    <div class="menu-header">Pengaturan Tampilan</div>
    <a href="<?php echo $adminBasePath; ?>setting/edit_header.php"
       class="sidebar-link<?php echo $matchesPage('edit_header') ? ' is-active' : ''; ?>"
       data-page="edit_header">
        Edit Header
    </a>
    <a href="<?php echo $adminBasePath; ?>setting/edit_footer.php"
       class="sidebar-link<?php echo $matchesPage('edit_footer') ? ' is-active' : ''; ?>"
       data-page="edit_footer">
        Edit Footer
    </a>
    <a href="<?php echo $adminBasePath; ?>beranda/edit_beranda.php"
       class="sidebar-link<?php echo $matchesPage('edit_beranda') ? ' is-active' : ''; ?>"
       data-page="edit_beranda">
        Edit Beranda
    </a>
    <a href="<?php echo $adminBasePath; ?>beranda/edit_banner.php"
       class="sidebar-link<?php echo $matchesPage('edit_banner') ? ' is-active' : ''; ?>"
       data-page="edit_banner">
        Edit Banner
    </a>

    <div class="menu-header">Manajemen Konten</div>

    <div class="dropdown-item">
        <button type="button"
                class="dropdown-toggle<?php echo $matchesPage(['edit_visi_misi','edit_struktur','edit_logo']) ? ' is-open' : ''; ?>"
                data-sidebar-toggle="profilMenu"
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

    <div class="dropdown-item">
        <button type="button"
                class="dropdown-toggle<?php echo $matchesPage(['edit_galeri','edit_agenda']) ? ' is-open' : ''; ?>"
                data-sidebar-toggle="galeriMenu"
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

    <div class="dropdown-item">
        <button type="button"
                class="dropdown-toggle<?php echo $matchesPage(['edit_penelitian','edit_pengabdian']) ? ' is-open' : ''; ?>"
                data-sidebar-toggle="arsipMenu"
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

    <div class="dropdown-item">
        <button type="button"
                class="dropdown-toggle<?php echo $matchesPage(['edit_sarana','lihat_pesan']) ? ' is-open' : ''; ?>"
                data-sidebar-toggle="layananMenu"
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

    <a href="<?php echo $projectBasePath; ?>user/index.php" class="sidebar-link" data-page="logout">
        Logout
    </a>
</aside>