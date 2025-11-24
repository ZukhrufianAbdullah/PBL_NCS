<?php 
// File: admin/profil/edit_visi_misi.php
session_start();
$page_title = "Edit Visi & Misi";
$current_page = "visi_misi";
// Hitung base_url untuk akses CSS/JS di folder induk admin/
$base_url = '../';
$assetUrl = '../../assets/admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo $assetUrl; ?>/css/admin-dashboard.css">
    <script src="<?php echo $assetUrl; ?>/js/admin-dashboard.js"></script>
</head>
<body>

    <div class="sidebar">
        <a href="../index.php">Dashboard</a>
        <a href="edit_visi_misi.php" class="<?php echo $current_page == 'visi_misi' ? 'active' : ''; ?>">Visi & Misi</a>
        </div>

    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?> (Tabel: profil)</h1>
        </div>

        <p>Edit teks Visi dan Misi lab Anda di sini.</p>

        <form method="post" action="../proses/proses_visi_misi.php">
            
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
</body>
</html>