<?php
// File: admin/profil/edit_visi_misi.php
session_start();
$pageTitle = 'Edit Visi & Misi';
$currentPage = 'edit_visi_misi';
$adminPageStyles = ['forms'];

require_once dirname(__DIR__) . '/includes/admin_header.php';
?>
<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: profil)</h1>
    <p>Perbarui judul halaman serta teks visi dan misi laboratorium di sini.</p>
</div>

<div class="card">
    <form method="post" action="<?php echo $adminBasePath; ?>proses/proses_struktur.php">
        <input type="hidden" name="edit_page_content" value="1">
        <fieldset>
            <legend>Konten Halaman Struktur Organisasi</legend>
            <div class="form-group">
                <label for="judul_page">Judul Halaman</label>
                <input type="text"
                       id="judul_page"
                       name="judul_page"
                       value="<?php echo htmlspecialchars($judul_page ?? ''); ?>"
                       data-autofocus="true">
            </div>
            <div class="form-group">
                <label for="deskripsi_page">Deskripsi Singkat Halaman</label>
                <textarea id="deskripsi_page"
                          name="deskripsi_page"
                          rows="4"><?php echo htmlspecialchars($deskripsi_page ?? ''); ?></textarea>
            </div>
        </fieldset>

        <div class="form-group">
            <button type="submit" name="submit" class="btn-primary">Simpan Konten Halaman</button>
        </div>
    </form>
</div>

<div class="card">
    <form method="post" action="<?php echo $adminBasePath; ?>proses/proses_visi_misi.php">
        <fieldset>
            <legend>Detail Visi &amp; Misi</legend>
            <div class="form-group">
                <label for="visi">Isi Visi (Kolom: visi)</label>
                <textarea id="visi"
                          name="visi"
                          rows="8"><?php echo htmlspecialchars($visi ?? 'Masukkan teks Visi di sini...'); ?></textarea>
            </div>
            <div class="form-group">
                <label for="misi">Isi Misi (Kolom: misi)</label>
                <textarea id="misi"
                          name="misi"
                          rows="10"><?php echo htmlspecialchars($misi ?? 'Masukkan teks Misi di sini...'); ?></textarea>
            </div>
        </fieldset>

        <div class="form-group">
            <button type="submit" name="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>