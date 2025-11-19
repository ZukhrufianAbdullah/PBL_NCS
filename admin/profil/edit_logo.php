<?php 
// File: admin/profil/edit_logo.php (Lokasi TETAP: admin/profil/edit_logo.php)
session_start();
$page_title = "Edit Logo Website";
$current_page = "edit_logo";
$base_url = '../'; // Path relatif naik satu tingkat ke folder admin/
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
    
    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?> (Tabel: logo)</h1>
        </div>

        <p>Gunakan form ini untuk mengubah logo utama website (Profil/logo).</p>

        <form method="post" action="../proses/proses_logo.php" enctype="multipart/form-data">
            
            <fieldset style="border: 1px solid #ccc; padding: 20px;">
                <legend style="font-size: 1.2em; font-weight: bold; color: var(--primary-color);">Manajemen Logo Utama</legend>
                
                <div class="form-group">
                    <label for="nama_logo">Nama/Deskripsi Logo (Kolom: nama_logo)</label>
                    <input type="text" id="nama_logo" name="nama_logo" value="Logo NCS Utama">
                </div>

                <div class="form-group">
                    <label for="media_path">Pilih File Logo Baru (Kolom: media_path)</label>
                    <input type="file" id="media_path" name="media_path" accept="image/*" required>
                    <small>Unggah gambar (PNG, JPG, SVG). Logo saat ini: [simulasi logo_saat_ini.png]</small>
                </div>
            </fieldset>

            <div class="form-group" style="margin-top: 25px;">
                <input type="submit" class="btn-primary" value="Unggah & Simpan Logo">
            </div>
        </form>

    </div>
</body>
</html>