<?php 
// File: admin/profil/edit_struktur.php
session_start();
include '../../config/koneksi.php';

$page_title = "Edit Struktur Organisasi";
$current_page = "edit_struktur";
$base_url = '../'; 
$assetUrl = '../../assets/admin';

// Ambil semua anggota (join anggota_lab -> dosen)
$sql = "SELECT a.id_anggota, a.jabatan, d.id_dosen, d.nama_dosen, d.media_path
        FROM anggota_lab a
        JOIN dosen d ON a.id_dosen = d.id_dosen
        ORDER BY d.nama_dosen ASC";
$res = pg_query($conn, $sql);
$members = [];
if ($res && pg_num_rows($res) > 0) {
    while ($r = pg_fetch_assoc($res)) $members[] = $r;
}

// Ambil page content (judul & deskripsi) untuk page profil_struktur
$pageRes = pg_query_params($conn, "SELECT id_page FROM pages WHERE nama = $1 LIMIT 1", ['profil_struktur']);
$id_page = null;
$judul_page = '';
$deskripsi_page = '';
if ($pageRes && pg_num_rows($pageRes) > 0) {
    $pageRow = pg_fetch_assoc($pageRes);
    $id_page = $pageRow['id_page'];

    $pcRes = pg_query_params($conn, "SELECT content_key, content_value FROM page_content WHERE id_page = $1", [$id_page]);
    if ($pcRes && pg_num_rows($pcRes) > 0) {
        while ($row = pg_fetch_assoc($pcRes)) {
            if ($row['content_key'] === 'judul') $judul_page = $row['content_value'];
            if ($row['content_key'] === 'deskripsi') $deskripsi_page = $row['content_value'];
        }
    }
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
        .dosen-table { width: 100%; border-collapse: collapse; background-color: white; margin-top: 20px; }
        .dosen-table th, .dosen-table td { padding: 10px; border: 1px solid #ccc; text-align: left; vertical-align: middle; }
        .dosen-table thead tr { background-color: #eee; } 
        .thumb { width: 64px; height: 64px; object-fit: cover; border-radius: 6px; }
        .action-form { display:inline-block; margin:0 4px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <a href="../index.php">Dashboard</a>
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('manajemenKonten')">
                MANAJEMEN KONTEN
                <span class="dropdown-icon" id="icon-manajemenKonten">></span>
            </a>
            <div class="submenu-wrapper" id="manajemenKonten">
                <div class="dropdown-item">
                    <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('subMenuProfile')">
                        Profile
                        <span class="dropdown-icon" id="icon-subMenuProfile">></span>
                    </a>
                    <div class="submenu-wrapper" id="subMenuProfile">
                        <a href="edit_visi_misi.php">Visi & Misi</a>
                        <a href="edit_logo.php">Logo</a>
                        <a href="edit_struktur.php" class="<?php echo $current_page == 'edit_struktur' ? 'active' : ''; ?>">Struktur Organisasi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?> (Tabel: dosen & anggota_lab)</h1>
        </div>

        <p>Gunakan halaman ini untuk menambah anggota baru, serta melihat dan mengelola detail semua dosen/staf yang ada (nama, jabatan, foto, dll.).</p>

        <!-- Form: Edit page content (judul & deskripsi) -->
        <fieldset style="border: 1px solid #ccc; padding: 20px; margin-bottom: 30px;">
            <legend>Konten Halaman Struktur Organisasi</legend>
            <form method="post" action="../proses/proses_struktur.php">
                <input type="hidden" name="edit_page_content" value="1">
                <div class="form-group">
                    <label for="judul_page">Judul Halaman</label>
                    <input type="text" id="judul_page" name="judul_page" value="<?php echo htmlspecialchars($judul_page); ?>">
                </div>
                <div class="form-group">
                    <label for="deskripsi_page">Deskripsi Singkat Halaman</label>
                    <textarea id="deskripsi_page" name="deskripsi_page" rows="4"><?php echo htmlspecialchars($deskripsi_page); ?></textarea>
                </div>
                <div class="form-group" style="margin-top: 10px;">
                    <input type="submit" name="submit" class="btn-primary" value="Simpan Konten Halaman">
                </div>
            </form>
        </fieldset>

        <!-- Form: Tambah anggota -->
        <fieldset style="border: 1px solid #ccc; padding: 20px; margin-bottom: 30px;">
            <legend>Tambah Anggota Tim Baru</legend>
            <form method="post" action="../proses/proses_struktur.php" enctype="multipart/form-data">
                <input type="hidden" name="tambah" value="1">
                <div class="form-group">
                    <label for="nama_dosen_new">Nama Lengkap & Gelar (Kolom: nama_dosen)</label>
                    <input type="text" id="nama_dosen_new" name="nama_dosen" required>
                </div>
                <div class="form-group">
                    <label for="jabatan_new">Jabatan / Role (Kolom: jabatan)</label>
                    <input type="text" id="jabatan_new" name="jabatan" required>
                </div>
                <div class="form-group">
                    <label for="media_path_dosen_new">Foto Profil (Kolom: media_path)</label>
                    <input type="file" id="media_path_dosen_new" name="foto" accept="image/*">
                </div>
                <input type="submit" class="btn-primary" value="Tambahkan Anggota Baru">
            </form>
        </fieldset>

        <h2>Daftar Semua Dosen/Staf Aktif</h2>
        <table class="dosen-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($members)): ?>
                    <tr><td colspan="4" class="text-muted">Belum ada anggota.</td></tr>
                <?php else: ?>
                    <?php foreach ($members as $m): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($m['nama_dosen']); ?></td>
                            <td><?php echo htmlspecialchars($m['jabatan']); ?></td>
                            <td>
                                <?php $img = !empty($m['media_path']) ? '../../uploads/dosen/' . $m['media_path'] : '../../uploads/dosen/default.png'; ?>
                                <img src="<?php echo $img; ?>" alt="" class="thumb">
                            </td>
                            <td>
                                <!-- EDIT: tampilkan form inline / modal link — di sini gunakan form sederhana -->
                                <form class="action-form" method="post" action="../proses/proses_struktur.php" enctype="multipart/form-data">
                                    <input type="hidden" name="edit" value="1">
                                    <input type="hidden" name="id_anggota" value="<?php echo $m['id_anggota']; ?>">
                                    <input type="hidden" name="id_dosen" value="<?php echo $m['id_dosen']; ?>">
                                    <input type="hidden" name="nama_dosen" value="<?php echo htmlspecialchars($m['nama_dosen']); ?>">
                                    <input type="hidden" name="jabatan" value="<?php echo htmlspecialchars($m['jabatan']); ?>">
                                    <button type="button" onclick="openEdit(<?php echo $m['id_anggota']; ?>)" class="btn-primary" style="background-color: orange;">Edit</button>
                                </form>

                                <!-- HAPUS -->
                                <form class="action-form" method="post" action="../proses/proses_struktur.php" onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
                                    <input type="hidden" name="hapus" value="1">
                                    <input type="hidden" name="id_anggota" value="<?php echo $m['id_anggota']; ?>">
                                    <button type="submit" class="btn-primary" style="background-color: #e74c3c;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Dialog / UI sederhana untuk edit (bisa diganti modal). 
             Untuk kesederhanaan: kita buka jendela pop-up prompt untuk edit nama & jabatan;
             atau Anda bisa implement modal JS sesuai admin-dashboard.js -->
    </div>

<script>
function openEdit(id) {
    // ambil current row values (dari DOM) dan buka prompt sederhana
    const row = document.querySelector('input[name="id_anggota"][value="'+id+'"]').closest('tr');
    const currentName = row.querySelector('input[name="nama_dosen"]').value;
    const currentJabatan = row.querySelector('input[name="jabatan"]').value;
    const newName = prompt('Ubah nama:', currentName);
    if (newName === null) return;
    const newJabatan = prompt('Ubah jabatan:', currentJabatan);
    if (newJabatan === null) return;

    // buat form dynamic untuk submit edit (tanpa upload foto)
    const f = document.createElement('form');
    f.method = 'post';
    f.action = '../proses/proses_struktur.php';

    const hiddenEdit = document.createElement('input'); hiddenEdit.type='hidden'; hiddenEdit.name='edit'; hiddenEdit.value='1'; f.appendChild(hiddenEdit);
    const hidIdAng = document.createElement('input'); hidIdAng.type='hidden'; hidIdAng.name='id_anggota'; hidIdAng.value = id; f.appendChild(hidIdAng);
    const hidIdDosen = document.createElement('input'); hidIdDosen.type='hidden'; hidIdDosen.name='id_dosen'; hidIdDosen.value = row.querySelector('input[name="id_dosen"]').value; f.appendChild(hidIdDosen);
    const hidNama = document.createElement('input'); hidNama.type='hidden'; hidNama.name='nama_dosen'; hidNama.value = newName; f.appendChild(hidNama);
    const hidJabatan = document.createElement('input'); hidJabatan.type='hidden'; hidJabatan.name='jabatan'; hidJabatan.value = newJabatan; f.appendChild(hidJabatan);

    document.body.appendChild(f);
    f.submit();
}
</script>

</body>
</html>
