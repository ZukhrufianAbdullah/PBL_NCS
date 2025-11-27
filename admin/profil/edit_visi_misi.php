<?php
// File: admin/profil/edit_visi_misi.php
session_start();
$pageTitle = 'Edit Visi & Misi';
$currentPage = 'edit_visi_misi';
$adminPageStyles = ['forms'];
include '../../config/koneksi.php';
require_once dirname(__DIR__) . '/includes/admin_header.php';

// Ambil data judul
$qJudulVisiMisi = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'profil_visi_misi' AND pc.content_key = 'judul_visi_misi'
    LIMIT 1");
$judulVisiMisi = pg_fetch_assoc($qJudulVisiMisi)['content_value'] ?? '';

// Ambil data deskripsi
$qDeskripsiVisiMisi = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'profil_visi_misi' AND pc.content_key = 'deskripsi_visi_misi'
    LIMIT 1");
$deskripsiVisiMisi = pg_fetch_assoc($qDeskripsiVisiMisi)['content_value'] ?? '';

//Ambil data visi
$qVisi = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'profil_visi_misi' AND pc.content_key = 'visi'
    LIMIT 1");
$visi = pg_fetch_assoc($qVisi)['content_value'] ?? '';

//Ambil data misi
$qMisi = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'profil_visi_misi' AND pc.content_key = 'misi'
    LIMIT 1");
$misi = pg_fetch_assoc($qMisi)['content_value'] ?? '';


?>

<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: profil)</h1>
    <p>Kelola halaman visi dan misi di sini</p>
</div>

<div class="card">
    <form method="post" action="../proses/proses_visi_misi.php">
        <input type="hidden" name="edit_page_content" value="1">
        <fieldset>
            <legend>Judul dan Deskripsi Visi Misi</legend>
            <div class="form-group">
                <label for="judul_page">Judul Halaman</label>
                <input type="text"
                       id="judul"
                       name="judul"
                       value="<?php echo htmlspecialchars($judulVisiMisi); ?>"
                       data-autofocus="true">
            </div>
            <div class="form-group">
                <label for="deskripsi_page">Deskripsi Singkat Halaman</label>
                <textarea id="deskripsi"
                          name="deskripsi"
                          rows="4"><?php echo htmlspecialchars($deskripsiVisiMisi); ?></textarea>
            </div>
        </fieldset>

        <div class="form-group">
            <button type="submit" name="submit_judul_deskripsi_visi_misi" class="btn-primary">Simpan Konten Halaman</button>
        </div>
    </form>
</div>

<div class="card">
    <form method="post" action="../proses/proses_visi_misi.php">
        <fieldset>
            <legend>Detail Visi &amp; Misi</legend>
            <div class="form-group">
                <label for="visi">Isi Visi (Kolom: visi)</label>
                <textarea id="visi"
                          name="visi"
                          rows="8"><?php echo htmlspecialchars($visi); ?></textarea>
            </div>
            <div class="form-group">
                <label for="misi">Isi Misi (Kolom: misi)</label>
                <textarea id="misi"
                          name="misi"
                          rows="10"><?php echo htmlspecialchars($misi); ?></textarea>
            </div>
        </fieldset>

        <div class="form-group">
            <button type="submit" name="submit_visi_misi" class="btn-primary">Simpan Perubahan Visi Misi</button>
        </div>
    </form>
</div>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>