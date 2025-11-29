<?php
session_start();
$pageTitle = 'Dashboard Admin';
$currentPage = 'dashboard';
$adminPageStyles = ['dashboard'];
include '../config/koneksi.php';
require_once __DIR__ . '/includes/admin_header.php';

// Total Galeri
$qTotalGaleri = pg_query($conn, "SELECT COUNT(*) AS total FROM galeri;");
$total_galeri = pg_fetch_assoc($qTotalGaleri)['total'];

// Total Agenda
$qTotalAgenda = pg_query($conn, "SELECT COUNT(*) AS total FROM agenda;");
$total_agenda = pg_fetch_assoc($qTotalAgenda)['total'];

// Total Penelitian
$total_penelitian =0;

// Total Pesan Masuk
$total_pesan = 0;

?>

<div class="admin-header">
    <h1>Selamat Datang di <?php echo $pageTitle; ?></h1>
    <p>Kelola seluruh konten website Network &amp; Cyber Security Laboratory dari panel ini.</p>
</div>

<div class="card">
    <div class="card-header">
        <h3>📌 Informasi Sistem</h3>
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
        <small>Kegiatan yang sudah dilaksanakan</small>
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

<div class="card" style="margin-top: 30px;">
    <div class="card-header" >
        <h3>🚀 Aksi Cepat</h3>
    </div>
    <div class="action-grid">
        <a href="<?php echo $adminBasePath; ?>galeri/edit_galeri.php" class="btn-primary">+ Tambah Galeri</a>
        <a href="<?php echo $adminBasePath; ?>galeri/edit_agenda.php" class="btn-primary">+ Tambah Agenda</a>
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