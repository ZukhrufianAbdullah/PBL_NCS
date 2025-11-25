<?php
// File: admin/edit_header_logo.php (MENGGANTIKAN edit_header.php DAN edit_logo.php)
session_start();
$pageTitle = 'Edit Header & Logo';
$currentPage = 'edit_header';
$adminPageStyles = ['forms'];

require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: header &amp; logo)</h1>
    <p>Gunakan form berikut untuk memperbarui judul utama website beserta aset logo yang tampil pada header.</p>
</div>

<div class="card">
    <form method="post"
          action="<?php echo $adminBasePath; ?>proses/proses_header_logo.php"
          enctype="multipart/form-data">

        <fieldset>
            <legend>Konten Header (Judul)</legend>
            <div class="form-group">
                <label for="title_text">Judul Utama Website (Kolom: title_text)</label>
                <input type="text"
                       id="title_text"
                       name="title_text"
                       value="Network and Cyber Security Laboratory"
                       data-autofocus="true">
                <span class="form-help-text">Teks ini selalu tampil pada bagian paling atas website.</span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Manajemen Logo Utama</legend>
            <div class="form-group">
                <label for="nama_logo">Nama/Deskripsi Logo (Kolom: nama_logo)</label>
                <input type="text" id="nama_logo" name="nama_logo" value="Logo NCS Utama">
            </div>
            <div class="form-group">
                <label for="media_path">Pilih File Logo Baru (Kolom: media_path)</label>
                <input type="file" id="media_path" name="media_path" accept="image/*">
                <span class="form-help-text">Unggah logo bertipe PNG/JPG/SVG. Biarkan kosong jika tidak ingin mengubah.</span>
            </div>
        </fieldset>

        <div class="form-group">
            <button type="submit" class="btn-primary">Simpan Perubahan Header &amp; Logo</button>
        </div>
    </form>
</div>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>
