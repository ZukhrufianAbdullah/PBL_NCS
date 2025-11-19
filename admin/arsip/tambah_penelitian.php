<?php 
// File: admin/arsip/tambah_penelitian.php
session_start();
$page_title = "Tambah Penelitian Baru";
$current_page = "tambah_penelitian";
$base_url = '../../'; // Path relatif naik dua tingkat ke folder admin/
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
        <a href="../galeri/tambah_agenda.php">Agenda (Tambah)</a>
        <a href="tambah_penelitian.php" class="<?php echo $current_page == 'tambah_penelitian' ? 'active' : ''; ?>">Penelitian (Tambah)</a>
        <a href="tambah_pengabdian.php">Pengabdian (Tambah)</a>
        <a href="../logout.php">Logout</a>
    </div>

    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?> (Tabel: penelitian)</h1>
        </div>

        <p>Form ini digunakan untuk menambahkan publikasi penelitian ke halaman Arsip/Penelitian.</p>

        <form method="post" action="../../proses/proses_arsip.php" enctype="multipart/form-data">
            
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
                    <label for="media_path">Upload File/Gambar Utama (Kolom: media_path)</label>
                    <input type="file" id="media_path" name="media_path" accept="image/*,.pdf">
                    <small>Dapat berupa gambar atau file PDF hasil penelitian.</small>
                </div>
            </fieldset>

            <div class="form-group" style="margin-top: 25px;">
                <input type="submit" class="btn-primary" value="Publikasikan Penelitian">
            </div>
        </form>

    </div>
</body>
</html>