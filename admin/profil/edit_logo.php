<?php
// File: admin/profil/edit_logo.php (Lokasi TETAP: admin/profil/edit_logo.php)
session_start();
$pageTitle = 'Edit Logo Website';
$currentPage = 'edit_logo';
$adminPageStyles = ['forms'];
include '../../config/koneksi.php';
require_once dirname(__DIR__) . '/includes/admin_header.php';

// Ambil data judul
$qJudulLogo = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'profil_logo' AND pc.content_key = 'judul_logo'
    LIMIT 1");
$judulLogo = pg_fetch_assoc($qJudulLogo)['content_value'] ?? '';

// Ambil data deskripsi
$qDeskripsiLogo = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'profil_logo' AND pc.content_key = 'deskripsi_logo'
    LIMIT 1");
$deskripsiLogo = pg_fetch_assoc($qDeskripsiLogo)['content_value'] ?? '';

// Ambil data Logo 1
$qLogo1 = pg_query($conn, "
    SELECT media_path
    FROM logo 
    WHERE nama_logo = 'logo_utama'
    LIMIT 1");
$logo1 = pg_fetch_assoc($qLogo1)['media_path'] ?? '';

// Ambil data Logo 2
$qLogo2 = pg_query($conn, "
    SELECT media_path
    FROM logo
    WHERE nama_logo = 'logo_deskripsi'
    LIMIT 1");
$logo2 = pg_fetch_assoc($qLogo2)['media_path'] ?? '';
?>

<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: logo)</h1>
    <p>Kelola halaman logo di sini</p>
</div>

<div class="card">
    <form method="post" action="../proses/proses_logo.php">
        <input type="hidden" name="edit_page_content" value="1">
        <fieldset>
            <legend>Judul dan Deskripsi Logo</legend>
            <div class="form-group">
                <label for="judul_page">Judul Halaman></label>
                <input type="text"
                       id="judul_logo"
                       name="judul_logo"
                       value="<?php echo htmlspecialchars($judulLogo);?>"
                       required
                       data-autofocus="true">
            </div>
            <div class="form-group">
                <label for="deskripsi_page">Deskripsi Singkat Halaman</label>
                <textarea id="deskripsi_logo"
                          name="deskripsi_logo"
                          rows="3"><?php echo htmlspecialchars($deskripsiLogo); ?></textarea>
            </div>
        </fieldset>

        <div class="form-group">
            <button type="submit" name = "submit_judul_deskripsi_logo" class="btn-primary">Simpan Konten Halaman</button>
        </div>
    </form>
</div>

<div class="card">
    <form method="post" action="../proses/proses_logo.php" enctype="multipart/form-data">
        <fieldset>
            <legend>Upload Logo Website</legend>

            <div class="form-group">
                <label for="file_logo1">Logo Utama</label>
                <input type="file" id="file_logo1" name="file_logo1" accept="image/*">
                <span class="form-help-text">Unggah logo bertipe PNG/JPG/JPEG/SVG. Biarkan kosong jika tidak ingin mengubah.</span>
            </div>
            <?php if (!empty($logo1)): ?>
            <div style="margin:10px 0;">
                <p>Logo Utama saat ini:</p>
                <img src="../../uploads/logo/<?php echo $logo1; ?>"
                     alt="Logo Utama" style="max-height:120px;">    
            </div>
            <?php endif; ?>
            <input type="hidden" name="id_logo1" value="<?php echo htmlspecialchars($logo1); ?>">

            <div class="form-group">
                <label for="file_logo2">Logo Deskripsi</label>
                <input type="file" id="file_logo2" name="file_logo2" accept="image/*">
                <span class="form-help-text">Unggah logo bertipe PNG/JPG/JPEG/SVG. Biarkan kosong jika tidak ingin mengubah.</span>
            </div>
            <?php if (!empty($logo2)): ?>
            <div style="margin:10px 0;">
                <p>Logo Deskripsi saat ini:</p>
                <img src="../../uploads/logo/<?php echo $logo2; ?>"
                     alt="Logo Deskripsi" style="max-height:120px;">    
            </div>
            <?php endif; ?>
            <input type="hidden" name="id_logo2" value="<?php echo htmlspecialchars($logo2); ?>">
        </fieldset>
        <div class="form-group">
            <button type="submit" name="submit_logo" class="btn-primary">Simpan Perubahan Logo</button>
        </div>
    </form>
</div>
<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>