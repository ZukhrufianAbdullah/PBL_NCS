<?php 
// File: admin/galeri/tambah_agenda.php
session_start();
$page_title = "Tambah Acara Agenda";
$current_page = "tambah_agenda";
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
        <a href="../beranda/edit_beranda.php">Edit Beranda</a>
        <a href="tambah_galeri.php">Galeri (Tambah)</a>
        <a href="tambah_agenda.php" class="<?php echo $current_page == 'tambah_agenda' ? 'active' : ''; ?>">Agenda (Tambah)</a>
        <a href="../arsip/tambah_penelitian.php">Penelitian (Tambah)</a>
        <a href="../logout.php">Logout</a>
    </div>

    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?> (Tabel: agenda)</h1>
        </div>

        <p>Form ini digunakan untuk menambahkan acara atau *workshop* ke halaman Agenda.</p>

        <form method="post" action="../../proses/proses_agenda.php">
            
            <fieldset style="border: 1px solid #ccc; padding: 20px;">
                <legend style="font-size: 1.2em; font-weight: bold; color: var(--primary-color);">Detail Acara</legend>
                
                <div class="form-group">
                    <label for="judul_agenda">Judul Acara (Kolom: judul_agenda)</label>
                    <input type="text" id="judul_agenda" name="judul_agenda" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi Singkat (Kolom: deskripsi)</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="tanggal_agenda">Tanggal Acara (Kolom: tanggal_agenda)</label>
                    <input type="date" id="tanggal_agenda" name="tanggal_agenda" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="status">Status (Kolom: status)</label>
                    <select id="status" name="status">
                        <option value="1">Aktif</option>
                        <option value="0">Arsip</option>
                    </select>
                </div>
            </fieldset>

            <div class="form-group" style="margin-top: 25px;">
                <input type="submit" class="btn-primary" value="Tambahkan Acara Agenda">
            </div>
        </form>

    </div>
</body>
</html>