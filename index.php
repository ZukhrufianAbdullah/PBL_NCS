<?php 
// File: admin/index.php
session_start();

// Cek apakah admin sudah login
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

$page_title = "Dashboard Admin";

// JANGAN UBAH INI di index.php
$current_page = "dashboard"; 

// **PERBAIKAN JALUR UTAMA:** Menggunakan Jalur Absolut dari root Admin.
// Asumsikan folder admin Anda diakses melalui /admin/
$base_path_admin = '../'; 

// Dummy statistics (nanti dari database)
$total_galeri = 0;
$total_agenda = 0;
$total_penelitian = 0;
$total_pesan = 0;

// Fungsi Helper untuk menentukan kelas 'active' pada tautan
function get_active_class($page, $current_page) {
    // Menambahkan isset() untuk menghindari Warning PHP
    return (isset($current_page) && $current_page == $page) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - NCS Lab</title>
    <link rel="stylesheet" href="asset/css/style_admin.css">
</head>
<body>

        <div class="sidebar">
        <h2>ADMIN NCS LAB</h2>
        
        <a href="index.php">Dashboard</a>
        
        <a href="/admin/admin/beranda/edit_beranda.php">Edit Beranda</a>
        
        <div class="menu-header">PENGATURAN TAMPILAN</div>
        <a href="/admin/include/edit_header.php">Edit Header</a>
        <a href="/admin/include/edit_footer.php">Edit Footer</a>

        <div class="menu-header">MANAJEMEN KONTEN</div>
        
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('manajemenKonten')">
                PROFIL
                <span class="dropdown-icon" id="icon-manajemenKonten"></span>
            </a>
            <div class="submenu-wrapper" id="manajemenKonten">
                <a href="/admin/admin/profil/edit_visi_misi.php">Visi & Misi</a>
                <a href="/admin/admin/profil/edit_struktur.php">Struktur Organisasi</a>
                <a href="/admin/admin/profil/edit_logo.php">Edit Logo</a>
            </div>
        </div>
        
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('galeriMenu')">
                GALERI
                <span class="dropdown-icon" id="icon-galeriMenu">></span>
            </a>
            <div class="submenu-wrapper" id="galeriMenu">
                <div class="menu-subheader">GALERI FOTO/VIDEO</div>
                <a href="/admin/admin/galeri/tambah_galeri.php">Tambah Galeri</a>
                <a href="/admin/admin/galeri/edit_galeri.php">Kelola Galeri</a>
                <div class="menu-subheader">AGENDA</div>
                <a href="/admin/admin/galeri/tambah_agenda.php">Tambah Agenda</a>
                <a href="/admin/admin/galeri/edit_agenda.php">Kelola Agenda</a>
            </div>
        </div>
        
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('arsipMenu')">
                ARSIP
                <span class="dropdown-icon" id="icon-arsipMenu">></span>
            </a>
            <div class="submenu-wrapper" id="arsipMenu">
                <div class="menu-subheader">PENELITIAN</div>
                <a href="/admin/admin/arsip/tambah_penelitian.php">Tambah Penelitian</a>
                <a href="/admin/admin/arsip/edit_penelitian.php">Kelola Penelitian</a>
                <div class="menu-subheader">PENGABDIAN</div>
                <a href="/admin/admin/arsip/tambah_pengabdian.php">Tambah Pengabdian</a>
                <a href="/admin/admin/arsip/edit_pengabdian.php">Kelola Pengabdian</a>
            </div>
        </div>

        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('layananMenu')">
                LAYANAN
                <span class="dropdown-icon" id="icon-layananMenu">></span>
            </a>
            <div class="submenu-wrapper" id="layananMenu">
                <a href="/admin/admin/layanan/edit_sarana_prasarana.php">Sarana & Prasarana</a>
                <a href="/admin/admin/layanan/lihat_pesan.php">Pesan Konsultatif</a>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="admin-header">
            <h1>Selamat Datang di <?php echo $page_title; ?></h1>
            <p>Kelola seluruh konten website Network & Cyber Security Laboratory dari panel ini.</p>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3>📌 Informasi Sistem</h3>
            </div>
            <p style="line-height: 1.8; color: #555;">
                Gunakan menu sidebar untuk mengelola konten website. Semua perubahan yang Anda lakukan akan langsung tersimpan ke database dan ditampilkan di website utama. Pastikan untuk memeriksa preview sebelum menyimpan perubahan penting.
            </p>
        </div>
        
        <div class="stats-grid">
            
            <div class="stat-card">
                <h3>Total Galeri</h3>
                <p class="stat-number"><?php echo $total_galeri; ?></p>
                <small>Foto & Video</small>
            </div>
            
            <div class="stat-card">
                <h3>Total Agenda</h3>
                <p class="stat-number"><?php echo $total_agenda; ?></p>
                <small>Kegiatan Mendatang</small>
            </div>
            
            <div class="stat-card">
                <h3>Total Penelitian</h3>
                <p class="stat-number"><?php echo $total_penelitian; ?></p>
                <small>Dokumen Penelitian</small>
            </div>
            
            <div class="stat-card">
                <h3>Pesan Masuk</h3>
                <p class="stat-number"><?php echo $total_pesan; ?></p>
                <small>Pesan Konsultatif</small>
            </div>
            
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3>🚀 Aksi Cepat</h3>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
                <a href="<?php echo $base_path_admin; ?>galeri/tambah_galeri.php" class="btn-primary" style="text-align: center; text-decoration: none; display: block;">
                    + Tambah Galeri
                </a>
                <a href="<?php echo $base_path_admin; ?>galeri/tambah_agenda.php" class="btn-primary" style="text-align: center; text-decoration: none; display: block;">
                    + Tambah Agenda
                </a>
                <a href="<?php echo $base_path_admin; ?>arsip/tambah_penelitian.php" class="btn-primary" style="text-align: center; text-decoration: none; display: block;">
                    + Tambah Penelitian
                </a>
                <a href="<?php echo $base_path_admin; ?>layanan/lihat_pesan.php" class="btn-success" style="text-align: center; text-decoration: none; display: block;">
                    📧 Lihat Pesan
                </a>
            </div>
        </div>
        
        <div class="card card-info">
            <div class="card-header">
                <h3>📋 Aktivitas Terakhir</h3>
            </div>
            <p style="color: #666; font-style: italic;">Belum ada aktivitas terbaru.</p>
        </div>
    </div>

    <script src="asset/js/script_admin.js"></script>
</body>
</html>