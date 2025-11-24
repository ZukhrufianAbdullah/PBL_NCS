<?php 
// File: admin/layanan/edit_sarana_prasarana.php
session_start();
require_once '../../config/koneksi.php';
$page_title = "Manajemen Sarana & Prasarana";
$current_page = "edit_sarana";
$base_url = '../../'; // Path relatif naik dua tingkat ke folder admin/
$assetUrl = '../../assets/admin';
$saranaList = [];
$result = pg_query($conn, "SELECT id_sarana, nama_sarana, media_path FROM sarana ORDER BY id_sarana DESC");
if ($result) {
    $saranaList = pg_fetch_all($result) ?: [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo $assetUrl; ?>/css/admin-dashboard.css">
    <script src="<?php echo $assetUrl; ?>/js/admin-dashboard.js"></script>
    <style>
        .sarana-table th, .sarana-table td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        .sarana-table thead tr { background-color: #eee; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>ADMIN NCS LAB</h2>
        <a href="../index.php">Dashboard</a>
        <a href="../beranda/edit_beranda.php">Edit Beranda</a>
        <a href="../edit_header.php">Edit Header Title</a> 
        <a href="../profil/edit_logo.php">Edit Logo</a> 
        <a href="../edit_footer.php">Edit Footer Details</a> 
        <a href="../dosen/edit_dosen.php">Profil Dosen/Staf</a>
        
        <h3>MANAJEMEN KONTEN</h3>
        <a href="edit_sarana_prasarana.php" class="<?php echo $current_page == 'edit_sarana' ? 'active' : ''; ?>">Sarana & Prasarana</a>
        <a href="lihat_pesan.php">Pesan Konsultatif</a>
        <a href="../logout.php">Logout</a>
    </div>

    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?> (Tabel: sarana)</h1>
        </div>

        <p>Gunakan form ini untuk menambah sarana/prasarana atau layanan baru yang ditampilkan di halaman Services/sarana-prasarana.</p>

        <form method="post" action="../proses/proses_sarana_prasarana.php" enctype="multipart/form-data">
            <input type="hidden" name="tambah_sarana" value="1">
            
            <fieldset style="border: 1px solid #ccc; padding: 20px; margin-bottom: 30px;">
                <legend style="font-size: 1.2em; font-weight: bold; color: var(--primary-color);">Tambah Sarana/Layanan Baru</legend>
                
                <div class="form-group">
                    <label for="nama_sarana">Nama Sarana/Layanan (Kolom: nama_sarana)</label>
                    <input type="text" id="nama_sarana" name="nama_sarana" placeholder="Contoh: Dedicated Server Room" required>
                </div>
                <div class="form-group">
                    <label for="media">Foto Sarana (Kolom: media_path)</label>
                    <input type="file" id="media" name="media" accept="image/*" required>
                </div>
                
                <input type="submit" class="btn-primary" value="Tambahkan Sarana">
            </fieldset>

            <h3 style="margin-top: 30px; color: var(--primary-color);">Daftar Sarana Aktif Saat Ini</h3>
            <table class="sarana-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Sarana</th>
                        <th>Deskripsi Singkat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($saranaList)): ?>
                        <tr>
                            <td colspan="4" style="text-align:center;">Belum ada data sarana.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($saranaList as $sarana): ?>
                            <tr>
                                <td><?php echo $sarana['id_sarana']; ?></td>
                                <td><?php echo htmlspecialchars($sarana['nama_sarana']); ?></td>
                                <td>
                                    <?php if (!empty($sarana['media_path'])): ?>
                                        <img src="<?php echo $base_url . '/uploads/sarana/' . htmlspecialchars($sarana['media_path']); ?>" alt="<?php echo htmlspecialchars($sarana['nama_sarana']); ?>" style="height:60px;">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="../proses/proses_sarana_prasarana.php?hapus=<?php echo $sarana['id_sarana']; ?>" onclick="return confirm('Hapus sarana ini?')" class="btn-primary" style="background-color:#dc3545;">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </form>

    </div>
</body>
</html>