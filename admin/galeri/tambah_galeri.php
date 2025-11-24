<?php 
session_start();

$page_title = "Tambah Postingan Galeri";
$current_page = "tambah_galeri";
$base_url = '../../';
$assetUrl = '../../assets/admin';

include_once '../../config/koneksi.php';
require_once __DIR__ . '/../../app/helpers/galeri_helper.php';

// Ambil semua data galeri
$galeriItems = get_galeri_items_all($conn);

// Ambil page content galeri (judul section + deskripsi)
$pageId = galeri_ensure_page($conn, 'galeri_page');
$pcRes = pg_query_params($conn, "SELECT content_key, content_value FROM page_content WHERE id_page=$1", array($pageId));
$pc = [];
if ($pcRes && pg_num_rows($pcRes) > 0) {
    while ($r = pg_fetch_assoc($pcRes)) $pc[$r['content_key']] = $r['content_value'];
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
        .galeri-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
        }
        .galeri-table th, .galeri-table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
            vertical-align: top;
        }
        .galeri-actions { display:flex; gap:6px; }
        .thumb {
            width: 100px;
            height: 70px;
            object-fit: cover;
            border-radius: 4px;
            border:1px solid #ddd;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <a href="../index.php">Dashboard</a>
    <a href="../beranda/edit_beranda.php">Edit Beranda</a>
    <a href="../edit_header.php">Edit Header Title</a>
    <a href="../profil/edit_logo.php">Edit Logo</a>
    <a href="../edit_footer.php">Edit Footer Details</a>
    <a href="../dosen/edit_dosen.php">Profil Dosen/Staf</a>
    <div class="menu-header">MANAJEMEN KONTEN</div>
    <a href="tambah_galeri.php" class="<?php echo $current_page == 'tambah_galeri' ? 'active' : ''; ?>">Galeri (Tambah)</a>
    <a href="tambah_agenda.php">Agenda (Tambah)</a>
    <a href="../arsip/tambah_penelitian.php">Penelitian (Tambah)</a>
    <a href="../logout.php">Logout</a>
</div>

<div class="content">
    <div class="admin-header">
        <h1><?php echo $page_title; ?> (Tabel: galeri)</h1>
    </div>

    <p>Form ini digunakan untuk menambahkan postingan gambar dan deskripsi ke halaman Galeri.</p>

    <!-- Form Tambah Galeri -->
    <form method="post" action="../proses/proses_galeri.php" enctype="multipart/form-data">
        <input type="hidden" name="tambah" value="1">

        <fieldset style="border: 1px solid #ccc; padding: 20px;">
            <legend style="font-size: 1.2em; font-weight: bold; color: var(--primary-color);">
                Detail Postingan
            </legend>

            <div class="form-group">
                <label for="judul">Judul Postingan</label>
                <input type="text" id="judul" name="judul" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Postingan</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="tanggal_kegiatan">Tanggal Kegiatan</label>
                <input type="date" id="tanggal_kegiatan" name="tanggal_kegiatan" value="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="form-group">
                <label for="foto_path">Gambar Postingan</label>
                <input type="file" id="foto_path" name="foto_path" accept="image/*" required>
            </div>
            
            <div class="form-group" style="margin-top: 25px;">
                <input type="submit" class="btn-primary" value="Tambahkan Postingan Galeri">
            </div>
        </fieldset>
    </form>

    <!-- Form Edit Section Page -->
    <fieldset style="border: 1px solid #ccc; padding: 20px; margin-top: 20px;">
        <legend>Konten Halaman Galeri (Section)</legend>

        <form method="post" action="../proses/proses_galeri.php">
            <input type="hidden" name="edit_page" value="1">

            <div class="form-group">
                <label for="judul_page">Section Title</label>
                <input type="text" id="judul_page" name="judul_page" 
                       value="<?php echo htmlspecialchars($pc['section_title'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="deskripsi_page">Section Description</label>
                <textarea id="deskripsi_page" name="deskripsi_page" rows="3"><?php 
                    echo htmlspecialchars($pc['section_description'] ?? ''); 
                ?></textarea>
            </div>

            <div class="form-group">
                <input type="submit" name="submit_page" class="btn-primary" value="Simpan Konten Halaman">
            </div>
        </form>
    </fieldset>

    <h2>Daftar Galeri</h2>

    <table class="galeri-table">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($galeriItems)): ?>
                <tr><td colspan="5" class="text-muted">Belum ada postingan galeri.</td></tr>
            <?php else: ?>
                <?php foreach ($galeriItems as $g): ?>
                    <tr>
                        <td>
                            <img src="<?php echo $base_url . $g['foto_path']; ?>" class="thumb">
                        </td>
                        <td><?php echo htmlspecialchars($g['judul']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($g['deskripsi'])); ?></td>
                        <td><?php echo htmlspecialchars($g['tanggal_kegiatan']); ?></td>
                        <td class="galeri-actions">

                            <!-- Edit -->
                            <form method="post" action="../proses/proses_galeri.php" style="display:inline-block;">
                                <input type="hidden" name="edit" value="1">
                                <input type="hidden" name="id_galeri" value="<?php echo $g['id_galeri']; ?>">
                                <button type="button" class="btn-primary" style="background:orange"
                                    onclick="openEditGaleri(<?php echo $g['id_galeri']; ?>)">Edit</button>
                            </form>

                            <!-- Delete -->
                            <form method="post" action="../proses/proses_galeri.php" onsubmit="return confirm('Hapus postingan ini?');">
                                <input type="hidden" name="hapus" value="1">
                                <input type="hidden" name="id_galeri" value="<?php echo $g['id_galeri']; ?>">
                                <button type="submit" class="btn-primary" style="background:#e74c3c">Hapus</button>
                            </form>

                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function openEditGaleri(id) {
    const rows = document.querySelectorAll('input[name="id_galeri"]');
    for (const input of rows) {
        if (parseInt(input.value) === parseInt(id)) {
            const tr = input.closest('tr');
            const currentTitle = tr.children[1].textContent.trim();
            const currentDesc = tr.children[2].textContent.trim();
            const currentDate = tr.children[3].textContent.trim();

            const newTitle = prompt('Ubah judul:', currentTitle);
            if (newTitle === null) return;

            const newDesc = prompt('Ubah deskripsi:', currentDesc);
            if (newDesc === null) return;

            const newDate = prompt('Ubah tanggal (YYYY-MM-DD):', currentDate);
            if (newDate === null) return;

            const f = document.createElement('form');
            f.method = 'post';
            f.action = '../proses/proses_galeri.php';

            f.innerHTML = `
                <input type="hidden" name="edit" value="1">
                <input type="hidden" name="id_galeri" value="${id}">
                <input type="hidden" name="judul" value="${newTitle}">
                <input type="hidden" name="deskripsi" value="${newDesc}">
                <input type="hidden" name="tanggal_kegiatan" value="${newDate}">
            `;

            document.body.appendChild(f);
            f.submit();
        }
    }
}
</script>

</body>
</html>
