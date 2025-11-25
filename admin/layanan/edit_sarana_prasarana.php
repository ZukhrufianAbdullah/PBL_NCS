<?php
// File: admin/layanan/edit_sarana_prasarana.php
session_start();
require_once '../../config/koneksi.php';
$pageTitle = 'Manajemen Sarana & Prasarana';
$currentPage = 'edit_sarana';
$adminPageStyles = ['forms', 'tables'];

$saranaList = [];
$result = pg_query($conn, "SELECT id_sarana, nama_sarana, media_path FROM sarana ORDER BY id_sarana DESC");
if ($result) {
    $saranaList = pg_fetch_all($result) ?: [];
}

require_once dirname(__DIR__) . '/includes/admin_header.php';
?>
<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: sarana)</h1>
    <p>Tambah dan kelola daftar sarana atau layanan yang tampil di halaman Layanan.</p>
</div>

<div class="card">
    <form method="post"
          action="<?php echo $adminBasePath; ?>proses/proses_sarana_prasarana.php"
          enctype="multipart/form-data">
        <input type="hidden" name="tambah_sarana" value="1">
        <fieldset>
            <legend>Tambah Sarana/Layanan Baru</legend>
            <div class="form-group">
                <label for="nama_sarana">Nama Sarana/Layanan (Kolom: nama_sarana)</label>
                <input type="text"
                       id="nama_sarana"
                       name="nama_sarana"
                       placeholder="Contoh: Dedicated Server Room"
                       required
                       data-autofocus="true">
            </div>
            <div class="form-group">
                <label for="media">Foto Sarana (Kolom: media_path)</label>
                <input type="file" id="media" name="media" accept="image/*" required>
            </div>
        </fieldset>
        <div class="form-group">
            <button type="submit" class="btn-primary">Tambahkan Sarana</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3>Daftar Sarana Aktif Saat Ini</h3>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Sarana</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($saranaList)): ?>
                <tr>
                    <td colspan="4" class="text-center">Belum ada data sarana.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($saranaList as $sarana): ?>
                    <tr>
                        <td><?php echo $sarana['id_sarana']; ?></td>
                        <td><?php echo htmlspecialchars($sarana['nama_sarana']); ?></td>
                        <td>
                            <?php if (!empty($sarana['media_path'])): ?>
                                <img src="<?php echo $projectBasePath . 'uploads/sarana/' . htmlspecialchars($sarana['media_path']); ?>"
                                     alt="<?php echo htmlspecialchars($sarana['nama_sarana']); ?>"
                                     class="table-img">
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo $adminBasePath; ?>proses/proses_sarana_prasarana.php?hapus=<?php echo $sarana['id_sarana']; ?>"
                               onclick="return confirm('Hapus sarana ini?');"
                               class="btn-danger">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>