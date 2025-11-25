<?php
session_start();
$pageTitle = 'Dashboard Admin';
$currentPage = 'dashboard';
$adminPageStyles = ['dashboard'];

$total_galeri = 0;
$total_agenda = 0;
$total_penelitian = 0;
$total_pesan = 0;

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1>Selamat Datang di <?php echo $pageTitle; ?></h1>
    <p>Kelola seluruh konten website Network &amp; Cyber Security Laboratory dari panel ini.</p>
</div>

        <div class="menu-header">MANAJEMEN KONTEN</div>
        
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('manajemenKonten')">
                PROFIL
                <span class="dropdown-icon" id="icon-manajemenKonten">></span>
            </a>
            <div class="submenu-wrapper" id="manajemenKonten">
                <a href="<?php echo $base_Url;?>/profil/edit_visi_misi.php">Visi & Misi</a>
                <a href="<?php echo $base_Url;?>/profil/edit_struktur.php">Struktur Organisasi</a>
                <a href="<?php echo $base_Url;?>/profil/edit_logo.php">Edit Logo</a>
            </div>
        </div>
        
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('galeriMenu')">
                GALERI
                <span class="dropdown-icon" id="icon-galeriMenu">></span>
            </a>
            <div class="submenu-wrapper" id="galeriMenu">
                <div class="menu-subheader">GALERI FOTO/VIDEO</div>
                <a href="<?php echo $base_Url;?>/galeri/tambah_galeri.php">Tambah Galeri</a>
                <a href="<?php echo $base_Url;?>/galeri/edit_galeri.php">Kelola Galeri</a>
                <div class="menu-subheader">AGENDA</div>
                <a href="<?php echo $base_Url;?>/galeri/tambah_agenda.php">Tambah Agenda</a>
                <a href="<?php echo $base_Url;?>/galeri/edit_agenda.php">Kelola Agenda</a>
            </div>
        </div>
        
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('arsipMenu')">
                ARSIP
                <span class="dropdown-icon" id="icon-arsipMenu">></span>
            </a>
            <div class="submenu-wrapper" id="arsipMenu">
                <div class="menu-subheader">PENELITIAN</div>
                <a href="<?php echo $base_Url;?>/arsip/tambah_penelitian.php">Tambah Penelitian</a>
                <a href="<?php echo $base_Url;?>/arsip/edit_penelitian.php">Kelola Penelitian</a>
                <div class="menu-subheader">PENGABDIAN</div>
                <a href="<?php echo $base_Url;?>/arsip/tambah_pengabdian.php">Tambah Pengabdian</a>
                <a href="<?php echo $base_Url;?>/arsip/edit_pengabdian.php">Kelola Pengabdian</a>
            </div>
        </div>

        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('layananMenu')">
                LAYANAN
                <span class="dropdown-icon" id="icon-layananMenu">></span>
            </a>
            <div class="submenu-wrapper" id="layananMenu">
                <a href="<?php echo $base_Url;?>/layanan/edit_sarana_prasarana.php">Sarana & Prasarana</a>
                <a href="<?php echo $base_Url;?>/layanan/lihat_pesan.php">Pesan Konsultatif</a>
            </div>
        </div>

       <a href="/PBL_NCS/index.php">Logout</a>

    </div>
    <p>
        Gunakan menu sidebar untuk mengelola konten website. Semua perubahan yang Anda lakukan akan langsung tersimpan
        ke database dan ditampilkan di website utama. Pastikan untuk memeriksa preview sebelum menyimpan perubahan penting.
    </p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Galeri</h3>
        <p class="stat-number"><?php echo $total_galeri; ?></p>
        <small>Foto &amp; Video</small>
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
    <div class="action-grid">
        <a href="<?php echo $adminBasePath; ?>galeri/tambah_galeri.php" class="btn-primary">+ Tambah Galeri</a>
        <a href="<?php echo $adminBasePath; ?>galeri/tambah_agenda.php" class="btn-primary">+ Tambah Agenda</a>
        <a href="<?php echo $adminBasePath; ?>arsip/tambah_penelitian.php" class="btn-primary">+ Tambah Penelitian</a>
        <a href="<?php echo $adminBasePath; ?>layanan/lihat_pesan.php" class="btn-success">📧 Lihat Pesan</a>
    </div>
</div>

<div class="card card-info">
    <div class="card-header">
        <h3>📋 Aktivitas Terakhir</h3>
    </div>
    <p class="text-gray">Belum ada aktivitas terbaru.</p>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>