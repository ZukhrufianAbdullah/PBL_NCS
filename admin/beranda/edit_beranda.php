<?php
session_start();
$pageTitle = 'Edit Halaman Beranda';
$currentPage = 'edit_beranda';
$adminPageStyles = ['forms'];

require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1><?php echo $pageTitle; ?></h1>
    <p>Gunakan form ini untuk mengubah judul dan deskripsi yang tampil di halaman utama website.</p>
</div>

<div class="card">
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

        <div class="form-group">
            <input type="submit" name="submit" class="btn-primary" value="Simpan Perubahan Home Page">
        </div>
    </form>
</div>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>