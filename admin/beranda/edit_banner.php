<?php 
// File: admin/beranda/edit_banner.php
session_start();
$page_title = "Edit Banner Halaman Utama";
$current_page = "edit_banner";
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
        <h2>ADMIN NCS LAB</h2>
        
        <a href="../index.php" class="<?php echo $current_page == 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
        <a href="edit_beranda.php">Edit Beranda</a>
        <a href="edit_banner.php" class="<?php echo $current_page == 'edit_banner' ? 'active' : ''; ?>">Edit Banner</a> <a href="../profil/edit_header.php">Edit Header Title</a> 
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
</body>
</html>