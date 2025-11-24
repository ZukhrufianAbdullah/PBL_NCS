<?php 
// File: admin/arsip/tambah_pengabdian.php
session_start();
require_once '../../config/koneksi.php';
$page_title = "Tambah Pengabdian Masyarakat";
$current_page = "tambah_pengabdian";
$base_url = '../../'; // Path relatif naik dua tingkat ke folder admin/
$assetUrl = '../../assets/admin';
$dosenOptions = [];
$dosenResult = pg_query($conn, "SELECT id_dosen, nama_dosen FROM dosen ORDER BY nama_dosen ASC");
if ($dosenResult) {
    $dosenOptions = pg_fetch_all($dosenResult) ?: [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo $assetUrl; ?>/css/admin-dashboard.css">
    <script src="<?php echo $assetUrl; ?>/js/admin-dashboard.js"></script>
</head>
<body>

    <div class="sidebar">
        <a href="../index.php">Dashboard</a>
        <a href="tambah_penelitian.php">Penelitian (Tambah)</a>
        <a href="tambah_pengabdian.php" class="<?php echo $current_page == 'tambah_pengabdian' ? 'active' : ''; ?>">Pengabdian (Tambah)</a>
        <a href="../layanan/edit_sarana_prasarana.php">Sarana & Prasarana</a>
        <a href="../logout.php">Logout</a>
    </div>

    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?> (Tabel: pengabdian)</h1>
        </div>

        <p>Form ini digunakan untuk menambahkan kegiatan pengabdian masyarakat ke halaman Arsip/Pengabdian.</p>

        <form method="post" action="../proses/proses_pengabdian.php">
            <input type="hidden" name="tambah" value="1">
            
            <fieldset style="border: 1px solid #ccc; padding: 20px;">
                <legend style="font-size: 1.2em; font-weight: bold; color: var(--primary-color);">Detail Pengabdian</legend>
                
                <div class="form-group">
                    <label for="judul_pengabdian">Judul Pengabdian (Kolom: judul_pengabdian)</label>
                    <input type="text" id="judul_pengabdian" name="judul_pengabdian" required>
                </div>
                <div class="form-group">
                    <label for="skema">Skema (Kolom: skema)</label>
                    <input type="text" id="skema" name="skema" placeholder="Contoh: Skema Pengabdian Internal" required>
                </div>
                <div class="form-group">
                    <label for="tahun">Tahun Pelaksanaan (Kolom: tahun)</label>
                    <input type="number" id="tahun" name="tahun" value="<?php echo date('Y'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="id_ketua">Ketua (Kolom: id_ketua)</label>
                    <select id="id_ketua" name="id_ketua" required>
                        <option value="">Pilih Ketua</option>
                        <?php foreach ($dosenOptions as $dosen): ?>
                            <option value="<?php echo $dosen['id_dosen']; ?>"><?php echo htmlspecialchars($dosen['nama_dosen']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </fieldset>

            <div class="form-group" style="margin-top: 25px;">
                <input type="submit" class="btn-primary" value="Publikasikan Pengabdian">
            </div>
        </form>

    </div>
</body>
</html>