<?php 
// File: admin/edit_header_logo.php (MENGGANTIKAN edit_header.php DAN edit_logo.php)
session_start();
$page_title = "Edit Header Title & Logo";
$current_page = "edit_header_logo";
$base_url = './'; 
$assetUrl = '../../assets/admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?> - NCS Lab</title>
    <link rel="stylesheet" href="<?php echo $assetUrl; ?>/css/admin-dashboard.css">
    <script src="<?php echo $assetUrl; ?>/js/admin-dashboard.js"></script>
</head>
<body>

    <div class="sidebar">
        <h2>ADMIN NCS LAB</h2>
        
        <a href="index.php" class="<?php echo $current_page == 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
        <a href="../beranda/edit_beranda.php">Edit Beranda</a>
        
        <a href="edit_header_logo.php" class="<?php echo $current_page == 'edit_header_logo' ? 'active' : ''; ?>">Edit Header & Logo</a> 
        <a href="edit_footer.php">Edit Footer Details</a> 
        <a href="dosen/edit_dosen.php">Profil Dosen/Staf</a>

        <h3>MANAJEMEN KONTEN</h3>
        <a href="logout.php" style="margin-top: 20px;">Logout</a>
    </div>

    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?> (Tabel: header & logo)</h1>
        </div>

        <p>Gunakan form ini untuk mengubah Judul Utama Header website dan Logo utama.</p>

        <form method="post" action="proses/proses_header_logo.php" enctype="multipart/form-data">
            
            <fieldset style="border: 1px solid #ccc; padding: 20px; margin-bottom: 30px;">
                <legend style="font-size: 1.2em; font-weight: bold; color: var(--primary-color);">Konten Header (Judul)</legend>
                <div class="form-group">
                    <label for="title_text">Judul Utama Website (Kolom: title_text)</label>
                    <input type="text" id="title_text" name="title_text" value="Network and Cyber Security Laboratory">
                    <small>Ini adalah teks judul yang selalu tampil di bagian atas website.</small>
                </div>
            </fieldset>
            
            <fieldset style="border: 1px solid #ccc; padding: 20px; margin-bottom: 30px;">
                <legend style="font-size: 1.2em; font-weight: bold; color: var(--primary-color);">Manajemen Logo Utama</legend>
                
                <div class="form-group">
                    <label for="nama_logo">Nama/Deskripsi Logo (Kolom: nama_logo)</label>
                    <input type="text" id="nama_logo" name="nama_logo" value="Logo NCS Utama">
                </div>

                <div class="form-group">
                    <label for="media_path">Pilih File Logo Baru (Kolom: media_path)</label>
                    <input type="file" id="media_path" name="media_path" accept="image/*">
                    <small>Unggah gambar (PNG, JPG, SVG). Biarkan kosong jika tidak ingin mengubah.</small>
                </div>
            </fieldset>

            <div class="form-group" style="margin-top: 25px;">
                <input type="submit" class="btn-primary" value="Simpan Perubahan Header & Logo">
            </div>
        </form>

    </div>
</body>
</html>