<?php 
// File: admin/edit_footer.php (LOKASI BARU: admin/edit_footer.php)
session_start();
$page_title = "Edit Footer Details";
$current_page = "edit_footer";

$base_Url = '..'; 
//$base_Url = '../'; 
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
            <h1><?php echo $page_title; ?> (Tabel: footer, credit tim, sosial media)</h1>
        </div>

        <p>Kelola detail Footer: Developer (credit tim), Sosial Media, dan Hak Cipta.</p>

        <form method="post" action="proses/proses_footer.php" enctype="multipart/form-data">
            
            <fieldset style="border: 1px solid #ccc; padding: 20px;">
                <legend style="font-size: 1.2em; font-weight: bold; color: var(--primary-color);">Konten Footer</legend>
                
                <div class="form-group">
                    <label for="title_footer">Judul Kolom Developer (Kolom: title_footer di footer)</label>
                    <input type="text" id="title_footer" name="title_footer" value="Developed by">
                </div>
                <div class="form-group">
                    <label for="credit_tim_nama">Daftar Nama Developer/Tim (Kolom: nama di credit tim)</label>
                    <textarea id="credit_tim_nama" name="credit_tim_nama" rows="6">D4 Teknik Informatika
Esatovin Ebenhaezer Victoria
Muhammad Nuril Huda
Nurfinika Lailasari
Zukrufian Abdullah</textarea>
                    <small>Setiap baris akan ditampilkan di kolom developer.</small>
                </div>
                
                <hr style="margin: 25px 0;">

                <h3 style="font-size: 1.1em; color: var(--primary-color); margin-bottom: 15px;">Manajemen Sosial Media</h3>
                
                <fieldset style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">
                    <legend>Tambah Sosial Media Baru (Tabel: sosial media)</legend>
                    <div class="form-group">
                        <label for="nama_sosialmedia">Nama Sosial Media (Kolom: nama_sosialmedia)</label>
                        <input type="text" id="nama_sosialmedia" name="nama_sosialmedia" placeholder="Contoh: Instagram / Twitter">
                    </div>
                    <div class="form-group">
                        <label for="url_sosialmedia">URL Penuh (Kolom: url)</label>
                        <input type="url" id="url_sosialmedia" name="url_sosialmedia" placeholder="Contoh: https://instagram.com/labncs">
                    </div>
                    <div class="form-group">
                        <label for="media_path_sosmed">Gambar/Icon (Kolom: media_path)</label>
                        <input type="file" id="media_path_sosmed" name="media_path_sosmed" accept="image/*">
                    </div>
                    <button class="btn-primary" type="button" style="background-color: #28a745;">Tambahkan Sosial Media Ke Daftar</button> 
                </fieldset>
                
                <hr style="margin: 25px 0;">

                <div class="form-group">
                    <label for="copyright_text">Teks Hak Cipta Paling Bawah (Kolom: copyright_text)</label>
                    <textarea id="copyright_text" name="copyright_text" rows="2">© 2025 Network and Cyber Security Laboratory. All Rights Reserved.</textarea>
                </div>
            </fieldset>

            <div class="form-group" style="margin-top: 25px;">
                <input type="submit" class="btn-primary" value="Simpan Detail Footer">
            </div>
        </form>

    </div>
    <script src="<?php echo $assetUrl; ?>/js/admin-dashboard.js"></script>
</body>
</html>