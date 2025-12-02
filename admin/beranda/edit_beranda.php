<?php
session_start();
$pageTitle = 'Edit Halaman Beranda';
$currentPage = 'edit_beranda';
$adminPageStyles = ['forms'];
include '../../config/koneksi.php';
require_once dirname(__DIR__) . '/includes/admin_header.php';

//Ambil deskripsi dari page_content
$qDeskripsi = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'home' AND pc.content_key = 'deskripsi'
    LIMIT 1");
$deskripsi = pg_fetch_assoc($qDeskripsi)['content_value'] ?? '';

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
                <label for="deskripsi">Deskripsi Halaman Utama</label>
                <textarea id="deskripsi" name="deskripsi" rows="8" placeholder="Masukkan deskripsi halaman utama"><?php 
                    echo htmlspecialchars($deskripsi, ENT_QUOTES, 'UTF-8'); 
                ?></textarea>
            </div>
        </fieldset>

        <div class="form-group">
            <input type="submit" name="submit" class="btn-primary" value="Simpan Perubahan Home Page">
        </div>
    </form>
</div>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>
