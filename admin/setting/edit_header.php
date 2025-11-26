<?php
// File: admin/edit_header_logo.php (MENGGANTIKAN edit_header.php DAN edit_logo.php)
session_start();
$pageTitle = 'Edit Header & Logo';
$currentPage = 'edit_header';
$adminPageStyles = ['forms'];
include '../../config/koneksi.php';


require_once dirname(__DIR__) . '/includes/admin_header.php';

// Ambil title_header
$qTitle = pg_query($conn, "SELECT setting_value FROM settings WHERE setting_name = 'title_header'");
$titleHeader = pg_fetch_assoc($qTitle)['setting_value'] ?? '';
    
// Ambil logo_header
$qLogo = pg_query($conn, "SELECT setting_value FROM settings WHERE setting_name = 'logo_header'");
$logoHeader = pg_fetch_assoc($qLogo)['setting_value'] ?? '';

?>



<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: header &amp; logo)</h1>
    <p>Gunakan form berikut untuk memperbarui judul utama website beserta aset logo yang tampil pada header.</p>
</div>

<div class="card">
    <form method="post"
          action="<?php echo $adminBasePath; ?>../admin/proses/proses_header.php"
          enctype="multipart/form-data">

        <fieldset>
            <legend>Konten Header (Judul)</legend>
            <div class="form-group">
                <label for="title_text">Judul Utama Website (Kolom: title_text)</label>
                <input type="text" id="title_header" name="title_header" value="<?php echo htmlspecialchars($titleHeader); ?>" data-autofocus="true">
                <span class="form-help-text">Teks ini selalu tampil pada bagian paling atas website.</span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Manajemen Logo Utama</legend>
            <div class="form-group">
                <label for="media_path">Pilih File Logo Baru (Kolom: media_path)</label>
                <input type="file" id="logo_header" name="logo_header" accept="image/*">
                <span class="form-help-text">Unggah logo bertipe PNG/JPG/SVG. Biarkan kosong jika tidak ingin mengubah.</span>
            </div>
            <?php if (!empty($logoHeader)): ?>
            <div style="margin:10px 0;">
                <p>Logo saat ini:</p>
                <img src="../../uploads/header/<?php echo $logoHeader; ?>"
                     alt="Logo Header" style="max-height:80px;">
            </div>
        <?php endif; ?>
        </fieldset>

        <div class="form-group">
            <button type="submit" name="submit" class="btn-primary">Simpan Perubahan Header &amp; Logo</button>
        </div>
    </form>
</div>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>
