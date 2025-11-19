<?php 
// File: admin/galeri/tambah_galeri.php
session_start();
$page_title = "Tambah Postingan Galeri";
$current_page = "tambah_galeri";
$base_url = '../../'; // Naik dua tingkat ke folder admin/
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
        <a href="../index.php">Dashboard</a>
        <a href="../beranda/edit_beranda.php">Edit Beranda</a>
        <a href="../edit_header.php">Edit Header Title</a> 
        <a href="../profil/edit_logo.php">Edit Logo</a> 
        <a href="../edit_footer.php">Edit Footer Details</a> 
        <a href="../dosen/edit_dosen.php">Profil Dosen/Staf</a>
        <div class="menu-header">MANAJEMEN KONTEN</div>
        <a href="tambah_galeri.php" class="<?php echo $current_page == 'tambah_galeri' ? 'active' : ''; ?>">Galeri (Tambah)</a>
        <a href="tambah_agenda.php">Agenda (Tambah)</a>
        <a href="../arsip/tambah_penelitian.php">Penelitian (Tambah)</a>
        <a href="../logout.php">Logout</a>
    </div>

    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?> (Tabel: galeri)</h1>
        </div>

        <p>Form ini digunakan untuk menambahkan postingan gambar dan deskripsi ke halaman Galeri.</p>

        <form method="post" action="../../proses/proses_galeri.php" enctype="multipart/form-data">
            
            <fieldset style="border: 1px solid #ccc; padding: 20px;">
                <legend style="font-size: 1.2em; font-weight: bold; color: var(--primary-color);">Detail Postingan</legend>
                
                <div class="form-group">
                    <label for="judul">Judul Postingan (Kolom: judul)</label>
                    <input type="text" id="judul" name="judul" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi Postingan (Kolom: deskripsi)</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="tanggal_kegiatan">Tanggal Kegiatan (Kolom: tanggal_kegiatan)</label>
                    <input type="date" id="tanggal_kegiatan" name="tanggal_kegiatan" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="foto_path">Gambar Postingan (Kolom: foto_path)</label>
                    <input type="file" id="foto_path" name="foto_path" accept="image/*" required>
                </div>
            </fieldset>

            <div class="form-group" style="margin-top: 25px;">
                <input type="submit" class="btn-primary" value="Tambahkan Postingan Galeri">
            </div>
        </form>

    </div>
</body>
</html>