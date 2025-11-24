<?php 
// File: admin/beranda/edit_banner.php
session_start();

$page_title = "Edit Banner Utama";
$current_page = "edit_banner";
$base_url = '../';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="/admin/asset/css/style_admin.css">
</head>
<body>

    <div class="sidebar">
        <h2>ADMIN NCS LAB</h2>
        
        <a href="index.php">Dashboard</a>
        
        <div class="menu-header">PENGATURAN TAMPILAN</div>
        <a href="/admin/include/edit_header.php">Edit Header</a>
        <a href="/admin/include/edit_footer.php">Edit Footer</a>
        <a href="/admin/admin/beranda/edit_beranda.php">Edit Beranda</a>
        <a href="/admin/admin/beranda/edit_banner.php">Edit Banner</a>

        <div class="menu-header">MANAJEMEN KONTEN</div>
        
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('manajemenKonten')">
                PROFIL
                <span class="dropdown-icon" id="icon-manajemenKonten">></span>
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
            <h1><?php echo $page_title; ?> (Tabel: banner)</h1>
        </div>

        <p>Gunakan form ini untuk mengubah teks dan latar belakang visual di bagian paling atas halaman utama (hero section).</p>

        <form method="post" action="../../proses/proses_banner.php" enctype="multipart/form-data">
            
            <fieldset style="border: 1px solid #ccc; padding: 20px; margin-bottom: 30px;">
                <legend style="font-size: 1.2em; font-weight: bold; color: var(--primary-color);">Konten Teks Banner</legend>
                
                <div class="form-group">
                    <label for="header_banner">Judul Utama Banner (Kolom: header)</label>
                    <input type="text" id="header_banner" name="header_banner" placeholder="Contoh: Network and Cyber Security Laboratory" required>
                </div>
                
                <div class="form-group">
                    <label for="subheadline">Sub Judul / Tagline (Kolom: subheadline)</label>
                    <input type="text" id="subheadline" name="subheadline" placeholder="Contoh: Innovating in Network Security & Cyber Defense" required>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi Singkat (Kolom: deskripsi)</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Teks kecil di bawah subheadline (opsional)"></textarea>
                </div>
            </fieldset>

            <fieldset style="border: 1px solid #ccc; padding: 20px;">
                <legend style="font-size: 1.2em; font-weight: bold; color: var(--primary-color);">Background Banner</legend>
                
                <div class="form-group">
                    <label for="media_path">Upload Latar Belakang Baru (Kolom: media_path)</label>
                    <input type="file" id="media_path" name="media_path" accept="image/*,video/*" required>
                    <small>File saat ini: [simulasi current_banner.jpg]. Unggah gambar atau video (opsional).</small>
                </div>
            </fieldset>

            <div class="form-group" style="margin-top: 25px;">
                <input type="submit" class="btn-primary" value="Simpan Perubahan Banner">
            </div>
        </form>

    </div>

    <script src="/admin/asset/js/script_admin.js"></script>
</body>
</html>