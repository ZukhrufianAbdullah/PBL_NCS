<?php 
// File: admin/layanan/edit_sarana_prasarana.php
session_start();
require_once '../../config/koneksi.php';
$page_title = "Manajemen Sarana & Prasarana";
$current_page = "edit_sarana";

$base_Url = '..'; 
//$base_Url = '../admin'; 
$assetUrl = '/PBL_NCS/assets/admin';

$saranaList = [];
$result = pg_query($conn, "SELECT id_sarana, nama_sarana, media_path FROM sarana ORDER BY id_sarana DESC");
if ($result) {
    $saranaList = pg_fetch_all($result) ?: [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo $assetUrl; ?>/css/admin-dashboard.css">

    <style>
        .sarana-table th, .sarana-table td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        .sarana-table thead tr { background-color: #eee; }
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
            <h1><?php echo $page_title; ?> (Tabel: sarana)</h1>
        </div>

        <p>Gunakan form ini untuk menambah sarana/prasarana atau layanan baru yang ditampilkan di halaman Services/sarana-prasarana.</p>

        <form method="post" action="../proses/proses_sarana_prasarana.php" enctype="multipart/form-data">
            <input type="hidden" name="tambah_sarana" value="1">
            
            <fieldset style="border: 1px solid #ccc; padding: 20px; margin-bottom: 30px;">
                <legend style="font-size: 1.2em; font-weight: bold; color: var(--primary-color);">Tambah Sarana/Layanan Baru</legend>
                
                <div class="form-group">
                    <label for="nama_sarana">Nama Sarana/Layanan (Kolom: nama_sarana)</label>
                    <input type="text" id="nama_sarana" name="nama_sarana" placeholder="Contoh: Dedicated Server Room" required>
                </div>
                <div class="form-group">
                    <label for="media">Foto Sarana (Kolom: media_path)</label>
                    <input type="file" id="media" name="media" accept="image/*" required>
                </div>
                
                <input type="submit" class="btn-primary" value="Tambahkan Sarana">
            </fieldset>

            <h3 style="margin-top: 30px; color: var(--primary-color);">Daftar Sarana Aktif Saat Ini</h3>
            <table class="sarana-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Sarana</th>
                        <th>Deskripsi Singkat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($saranaList)): ?>
                        <tr>
                            <td colspan="4" style="text-align:center;">Belum ada data sarana.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($saranaList as $sarana): ?>
                            <tr>
                                <td><?php echo $sarana['id_sarana']; ?></td>
                                <td><?php echo htmlspecialchars($sarana['nama_sarana']); ?></td>
                                <td>
                                    <?php if (!empty($sarana['media_path'])): ?>
                                        <img src="<?php echo $base_url . '/uploads/sarana/' . htmlspecialchars($sarana['media_path']); ?>" alt="<?php echo htmlspecialchars($sarana['nama_sarana']); ?>" style="height:60px;">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="../proses/proses_sarana_prasarana.php?hapus=<?php echo $sarana['id_sarana']; ?>" onclick="return confirm('Hapus sarana ini?')" class="btn-primary" style="background-color:#dc3545;">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </form>

    </div>
    <script src="<?php echo $assetUrl; ?>/js/admin-dashboard.js"></script>
</body>
</html>