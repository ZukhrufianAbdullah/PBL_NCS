<?php
// File: admin/edit_footer.php (LOKASI BARU: admin/edit_footer.php)
session_start();
$pageTitle = 'Edit Footer Details';
$currentPage = 'edit_footer';
$adminPageStyles = ['forms'];

require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: footer, credit tim, sosial media)</h1>
    <p>Kelola kolom developer, daftar sosial media, dan teks hak cipta yang tampil pada footer website.</p>
</div>

<div class="card">
    <form method="post"
          action="<?php echo $adminBasePath; ?>proses/proses_footer.php"
          enctype="multipart/form-data">
        <fieldset>
            <legend>Konten Footer</legend>
            <div class="form-group">
                <label for="title_footer">Judul Kolom Developer (Kolom: title_footer)</label>
                <input type="text" id="title_footer" name="title_footer" value="Developed by" data-autofocus="true">
            </div>
            <div class="form-group">
                <label for="credit_tim_nama">Daftar Nama Developer/Tim (Kolom: nama)</label>
                <textarea id="credit_tim_nama" name="credit_tim_nama" rows="6">D4 Teknik Informatika
Esatovin Ebenhaezer Victoria
Muhammad Nuril Huda
Nurfinika Lailasari
Zukrufian Abdullah</textarea>
                <span class="form-help-text">Setiap baris akan ditampilkan sebagai item terpisah.</span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Manajemen Sosial Media</legend>
            <div class="form-group">
                <label for="nama_sosialmedia">Nama Sosial Media (Kolom: nama_sosialmedia)</label>
                <input type="text" id="nama_sosialmedia" name="nama_sosialmedia" placeholder="Contoh: Instagram / Twitter">
            </div>
            <div class="form-group">
                <label for="url_sosialmedia">URL Penuh (Kolom: url)</label>
                <input type="url" id="url_sosialmedia" name="url_sosialmedia" placeholder="https://instagram.com/labncs">
            </div>
            <div class="form-group">
                <label for="media_path_sosmed">Gambar/Icon (Kolom: media_path)</label>
                <input type="file" id="media_path_sosmed" name="media_path_sosmed" accept="image/*">
            </div>
            <div class="form-group">
                <button type="button" class="btn-primary">Tambahkan Sosial Media Ke Daftar</button>
            </div>
        </fieldset>

        <fieldset>
            <legend>Hak Cipta</legend>
            <div class="form-group">
                <label for="copyright_text">Teks Hak Cipta (Kolom: copyright_text)</label>
                <textarea id="copyright_text" name="copyright_text" rows="2">© 2025 Network and Cyber Security Laboratory. All Rights Reserved.</textarea>
            </div>
        </fieldset>

        <div class="form-group">
            <button type="submit" class="btn-primary">Simpan Detail Footer</button>
        </div>
    </form>
</div>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>