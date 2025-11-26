<?php
session_start();
$pageTitle = 'Edit Banner Halaman Utama';
$currentPage = 'edit_banner';
$adminPageStyles = ['forms'];
include '../../config/koneksi.php';

require_once dirname(__DIR__) . '/includes/admin_header.php';

// Ambil title_banner
$qTitleBanner = pg_query($conn, "SELECT setting_value FROM settings WHERE setting_name = 'title_banner'");
$titleBanner = pg_fetch_assoc($qTitleBanner)['setting_value'] ?? '';

// Ambil subheadline_banner
$qSubheadlineBanner = pg_query($conn, "SELECT setting_value FROM settings WHERE setting_name = 'subheadline_banner'");
$subheadlineBanner = pg_fetch_assoc($qSubheadlineBanner)['setting_value'] ?? '';

// Ambil background_banner
$qBackgroundBanner = pg_query($conn, "SELECT setting_value FROM settings WHERE setting_name = 'image_banner'");
$backgroundBanner = pg_fetch_assoc($qBackgroundBanner)['setting_value'] ?? '';
?>


<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: banner)</h1>
    <p>Gunakan form ini untuk mengubah teks dan latar belakang visual di bagian paling atas halaman utama (hero section).</p>
</div>

<div class="card">
    <form method="post"
          action="<?php echo $adminBasePath; ?>../admin/proses/proses_banner.php"
          enctype="multipart/form-data">
        <fieldset>
            <legend>Konten Teks Banner</legend>

            <div class="form-group">
                <label for="header_banner">Judul Utama Banner (Kolom: header)</label>
                <input type="text" id="title_banner" name="title_banner" value="<?php echo htmlspecialchars($titleBanner); ?>" data-autofocus="true">
            </div>

            <div class="form-group">
                <label for="subheadline">Sub Judul / Tagline (Kolom: subheadline)</label>
                <input type="text" id="subheadline_banner" name="subheadline_banner" value="<?php echo htmlspecialchars($subheadlineBanner); ?>" data-autofocus="true">
            </div>
        </fieldset>

        <fieldset>
            <legend>Background Banner</legend>
            <div class="form-group">
                <label for="media_path">Upload Latar Belakang Baru (Kolom: media_path)</label>
                <input type="file" id="image_banner" name="image_banner" accept="image/*">
                <span class="form-help-text">Unggah logo bertipe PNG/JPG/SVG. Biarkan kosong jika tidak ingin mengubah.</span>
            </div>
            <?php if (!empty($backgroundBanner)): ?>
            <div style="margin:10px 0;">
                <p>Background saat ini:</p>
                <img src="../../uploads/banner/<?php echo $backgroundBanner; ?>"
                     alt="Background Banner" style="max-height:120px;">
            </div>
        <?php endif; ?>
        </fieldset>

        <div class="form-group">
            <input type="submit" name="submit" class="btn-primary">Simpan Perubahan Header &amp; Logo</button>
        </div>
    </form>
</div>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>
