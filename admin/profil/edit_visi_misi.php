<?php 
// File: admin/profil/edit_visi_misi.php
session_start();
$page_title = "Edit Visi & Misi";
$current_page = "visi_misi";
// Hitung base_url untuk akses CSS/JS di folder induk admin/

$base_Url = '..'; 
//$base_Url = '../admin'; 
$assetUrl = '/PBL_NCS/assets/admin';

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
            <h1><?php echo $page_title; ?> (Tabel: profil)</h1>        
            <p>Edit teks Visi dan Misi lab Anda di sini.</p>
        </div>

        <form method="post" action="../proses/proses_visi_misi.php">

                <fieldset style="border: 1px solid #ccc; padding: 20px; margin-bottom: 30px;">
            <legend>Konten Halaman Struktur Organisasi</legend>
            <form method="post" action="../proses/proses_struktur.php">
                <input type="hidden" name="edit_page_content" value="1">
                <div class="form-group">
                    <label for="judul_page">Judul Halaman</label>
                    <input type="text" id="judul_page" name="judul_page" value="<?php echo htmlspecialchars($judul_page); ?>">
                </div>
                <div class="form-group">
                    <label for="deskripsi_page">Deskripsi Singkat Halaman</label>
                    <textarea id="deskripsi_page" name="deskripsi_page" rows="4"><?php echo htmlspecialchars($deskripsi_page); ?></textarea>
                </div>
                <div class="form-group" style="margin-top: 10px;">
                    <input type="submit" name="submit" class="btn-primary" value="Simpan Konten Halaman">
                </div>
            </form>
        </fieldset>
            
            <fieldset>
                <legend>Detail Visi & Misi</legend>
                <div class="form-group">
                    <label for="visi">Isi Visi (Kolom: visi)</label>
                    <textarea id="visi" name="visi" rows="8">Masukkan teks Visi di sini...</textarea>
                </div>
                <div class="form-group">
                    <label for="misi">Isi Misi (Kolom: misi)</label>
                    <textarea id="misi" name="misi" rows="10">Masukkan teks Misi di sini...</textarea>
                </div>
            </fieldset>

            <div class="form-group" style="margin-top: 25px;">
                <input type="submit" name="submit" class="btn-primary" value="Simpan Perubahan">
            </div>
        </form>

    </div>
    <script src="<?php echo $assetUrl; ?>/js/admin-dashboard.js"></script>
</body>
</html>