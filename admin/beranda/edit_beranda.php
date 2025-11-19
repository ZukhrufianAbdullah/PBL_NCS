<?php 
// File: admin/beranda/edit_beranda.php
session_start();
$page_title = "Edit Halaman Beranda";
$current_page = "edit_beranda";
$base_url = '../'; 
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
        <a href="edit_beranda.php" class="<?php echo $current_page == 'edit_beranda' ? 'active' : ''; ?>">Edit Beranda</a>
        </div>

    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?> (Tabel: home)</h1>
        </div>

        <p>Gunakan form ini untuk mengubah judul dan deskripsi yang tampil di halaman utama website.</p>

        <form method="post" action="../proses/proses_beranda.php">
            
            <fieldset>
                <legend>Konten Halaman Utama</legend>
                
                <div class="form-group">
                    <label for="judul">Judul Halaman Utama (Kolom: judul)</label>
                    <input type="text" id="judul" name="judul" value="Network and Cyber Security Laboratory">
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi Halaman Utama (Kolom: deskripsi)</label>
                    <textarea id="deskripsi" name="deskripsi" rows="8">Our lab focuses on pioneering research and developing robust solutions...</textarea>
                </div>
            </fieldset>

            <div class="form-group" style="margin-top: 25px;">
                <input type="submit" class="btn-primary" value="Simpan Perubahan Home Page">
            </div>
        </form>

    </div>

</body>
</html>