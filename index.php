<?php 
// File: admin/index.php
session_start();
$page_title = "Dashboard Admin";
$current_page = "dashboard";
$base_url = './'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>style_admin.css">
    <script src="<?php echo $base_url; ?>script_admin.js"></script>
</head>
<body>

    <div class="sidebar">
        <h2>ADMIN NCS LAB</h2>
        
        <a href="index.php" class="<?php echo $current_page == 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
        
        <a href="beranda/edit_beranda.php">Edit Beranda</a>
        
        <div class="menu-header">PENGATURAN TAMPILAN</div>
        <a href="edit_header.php">Edit Header Title</a> 
        <a href="profil/edit_logo.php">Edit Logo</a> 
        <a href="edit_footer.php">Edit Footer Details</a> 
        
        <a href="dosen/edit_dosen.php">Profil Dosen/Staf</a>

        <div class="menu-header">MANAJEMEN KONTEN</div>
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('manajemenKonten')">
                PROFIL
                <span class="dropdown-icon" id="icon-manajemenKonten">></span>
            </a>
            <div class="submenu-wrapper" id="manajemenKonten">
                <a href="profil/edit_visi_misi.php">Visi & Misi</a>
                <a href="profil/edit_struktur.php">Struktur Organisasi</a>
            </div>
        </div>
        
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('galeriMenu')">
                GALERI
                <span class="dropdown-icon" id="icon-galeriMenu">></span>
            </a>
            <div class="submenu-wrapper" id="galeriMenu">
                <a href="galeri/tambah_galeri.php">Galeri (Tambah)</a>
                <a href="galeri/tambah_agenda.php">Agenda (Tambah)</a>
            </div>
        </div>
        
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('arsipMenu')">
                ARSIP
                <span class="dropdown-icon" id="icon-arsipMenu">></span>
            </a>
            <div class="submenu-wrapper" id="arsipMenu">
                <a href="arsip/tambah_penelitian.php">Penelitian (Tambah)</a>
                <a href="arsip/tambah_pengabdian.php">Pengabdian (Tambah)</a>
            </div>
        </div>

        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('layananMenu')">
                LAYANAN
                <span class="dropdown-icon" id="icon-layananMenu">></span>
            </a>
            <div class="submenu-wrapper" id="layananMenu">
                <a href="layanan/edit_sarana_prasarana.php">Sarana & Prasarana</a>
                <a href="layanan/lihat_pesan.php">Pesan Konsultatif</a>
            </div>
        </div>
        
        <a href="logout.php" style="margin-top: 20px;">Logout</a>
    </div>

    <div class="content">
        <div class="admin-header">
            <h1>Selamat Datang di <?php echo $page_title; ?></h1>
        </div>
        
        <p>Gunakan menu sidebar untuk mengelola konten sesuai struktur folder.</p>
    </div>

</body>
</html>