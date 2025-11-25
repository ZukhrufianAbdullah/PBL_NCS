<?php 
// File: admin/arsip/tambah_penelitian.php
session_start();
require_once '../../config/koneksi.php';
$page_title = "Tambah Penelitian Baru";
$current_page = "tambah_penelitian";

$base_Url = '..'; 
//$base_Url = '../admin'; 
$assetUrl = '/PBL_NCS/assets/admin';

$dosenOptions = [];
$dosenResult = pg_query($conn, "SELECT id_dosen, nama_dosen FROM dosen ORDER BY nama_dosen ASC");
if ($dosenResult) {
    $dosenOptions = pg_fetch_all($dosenResult) ?: [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo $assetUrl; ?>/css/admin-dashboard.css">

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
            <h1><?php echo $page_title; ?> (Tabel: penelitian)</h1>
        </div>

        <p>Form ini digunakan untuk menambahkan publikasi penelitian ke halaman Arsip/Penelitian.</p>

        <form method="post" action="../proses/proses_penelitian.php" enctype="multipart/form-data">
            <input type="hidden" name="tambah" value="1">
            
            <fieldset style="border: 1px solid #ccc; padding: 20px;">
                <legend style="font-size: 1.2em; font-weight: bold; color: var(--primary-color);">Detail Penelitian</legend>
                
                <div class="form-group">
                    <label for="judul_penelitian">Judul Penelitian (Kolom: judul_penelitian)</label>
                    <input type="text" id="judul_penelitian" name="judul_penelitian" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi Singkat (Kolom: deskripsi)</label>
                    <textarea id="deskripsi" name="deskripsi" rows="6"></textarea>
                </div>
                <div class="form-group">
                    <label for="tahun">Tahun Publikasi (Kolom: tahun)</label>
                    <input type="number" id="tahun" name="tahun" value="<?php echo date('Y'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="id_author">Penulis (Kolom: id_author)</label>
                    <select id="id_author" name="id_author">
                        <option value="">Pilih Penulis</option>
                        <?php foreach ($dosenOptions as $dosen): ?>
                            <option value="<?php echo $dosen['id_dosen']; ?>"><?php echo htmlspecialchars($dosen['nama_dosen']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="pdf">Upload File PDF (Kolom: media_path)</label>
                    <input type="file" id="pdf" name="pdf" accept=".pdf">
                    <small>Unggah file PDF hasil penelitian.</small>
                </div>
            </fieldset>

            <div class="form-group" style="margin-top: 25px;">
                <input type="submit" class="btn-primary" value="Publikasikan Penelitian">
            </div>
        </form>

    </div>
    <script src="<?php echo $assetUrl; ?>/js/admin-dashboard.js"></script>
</body>
</html>