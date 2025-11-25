<?php 
// File: admin/layanan/lihat_pesan.php
session_start();
$page_title = "Pesan Masuk Konsultatif";
$current_page = "lihat_pesan";

$base_Url = '..'; 
//$base_Url = '../admin'; 
$assetUrl = '/PBL_NCS/assets/admin';

// Data dummy untuk pesan konsultatif (Tabel: konsultatif)
$dummy_pesan = [
    ['id' => 1, 'nama_pengirim' => 'Esatovin', 'isi_pesan' => 'Saya tertarik konsultasi tentang kriptografi kuantum.', 'tanggal_kirim' => '2025-11-15 10:30'],
    ['id' => 2, 'nama_pengirim' => 'Muhammad Nuril', 'isi_pesan' => 'Perlu bantuan setting VPN untuk kantor.', 'tanggal_kirim' => '2025-11-14 14:00'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo $assetUrl; ?>/css/admin-dashboard.css">

    <style>
        .pesan-table th, .pesan-table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .pesan-table thead tr { background-color: #f7f7f7; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>ADMIN NCS LAB</h2>
        <a href="index.php">Dashboard</a> 
        
        <div class="menu-header">PENGATURAN TAMPILAN</div>
        <a href="<?php echo $base_Url; ?>/setting/edit_header.php">Edit Header</a>
        <a href="<?php echo $base_Url; ?>/setting/edit_footer.php">Edit Footer</a>
        <a href="<?php echo $base_Url; ?>/beranda/edit_beranda.php">Edit Beranda</a>
        <a href="<?php echo $base_Url; ?>/beranda/edit_banner.php">Edit Banner</a>

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
    </div>

    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?> (Tabel: konsultatif)</h1>
        </div>

        <p>Daftar pesan masuk dari pengunjung yang ingin berkonsultasi (Services/konsultatif).</p>

        <table class="pesan-table">
            <thead>
                <tr>
                    <th>Waktu Kirim</th>
                    <th>Nama Pengirim</th>
                    <th>Isi Pesan Singkat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dummy_pesan as $pesan): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pesan['tanggal_kirim']); ?></td>
                    <td><?php echo htmlspecialchars($pesan['nama_pengirim']); ?></td>
                    <td><?php echo htmlspecialchars(substr($pesan['isi_pesan'], 0, 50)) . '...'; ?></td>
                    <td>
                        <button class="btn-primary" style="background-color: orange; padding: 5px 10px;">Lihat Detail</button>
                        <a href="hapus_pesan.php?id=<?php echo $pesan['id']; ?>" onclick="return confirm('Yakin hapus pesan ini?')" class="btn-primary" style="background-color: red; padding: 5px 10px; text-decoration: none;">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <p style="margin-top: 20px;">*Untuk melihat pesan lengkap, klik tombol "Lihat Detail".</p>
    </div>
    <script src="<?php echo $assetUrl; ?>/js/admin-dashboard.js"></script>
</body>
</html>