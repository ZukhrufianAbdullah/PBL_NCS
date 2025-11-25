<?php
session_start();
$pageTitle = 'Edit Banner Halaman Utama';
$currentPage = 'edit_banner';
$adminPageStyles = ['forms'];

require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: banner)</h1>
    <p>Gunakan form ini untuk mengubah teks dan latar belakang visual di bagian paling atas halaman utama (hero section).</p>
</div>

<div class="card">
    <form method="post" action="../proses/proses_banner.php" enctype="multipart/form-data">
        <fieldset>
            <legend>Konten Teks Banner</legend>

            <div class="form-group">
                <label for="header_banner">Judul Utama Banner (Kolom: header)</label>
                <input type="text" id="header_banner" name="header_banner" placeholder="Contoh: Network and Cyber Security Laboratory" required>
            </div>

            <div class="form-group">
                <label for="subheadline">Sub Judul / Tagline (Kolom: subheadline)</label>
                <input type="text" id="subheadline" name="subheadline" placeholder="Contoh: Innovating in Network Security &amp; Cyber Defense" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Singkat (Kolom: deskripsi)</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Teks kecil di bawah subheadline (opsional)"></textarea>
            </div>
        </fieldset>

        <fieldset>
            <legend>Background Banner</legend>

            <div class="form-group">
                <label for="media_path">Upload Latar Belakang Baru (Kolom: media_path)</label>
                <input type="file" id="media_path" name="media_path" accept="image/*,video/*" required>
                <small>File saat ini: [simulasi current_banner.jpg]. Unggah gambar atau video (opsional).</small>
            </div>
        </fieldset>

        <div class="form-group">
            <input type="submit" class="btn-primary" value="Simpan Perubahan Banner">
        </div>
    </form>
</div>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>
