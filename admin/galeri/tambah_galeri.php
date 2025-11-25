<?php 
session_start();

$pageTitle = 'Tambah Postingan Galeri';
$currentPage = 'tambah_galeri';
$adminPageStyles = ['forms', 'tables'];

$base_Url = '..'; 
//$base_Url = '../admin'; 
$assetUrl = '/PBL_NCS/assets/admin';

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
require_once dirname(__DIR__) . '/includes/admin_header.php';
?>
<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: galeri)</h1>
    <p>Tambahkan postingan galeri baru dan atur konten section halaman galeri.</p>
</div>

<div class="card">
    <form method="post"
          action="<?php echo $adminBasePath; ?>proses/proses_galeri.php"
          enctype="multipart/form-data">
        <input type="hidden" name="tambah" value="1">
        <fieldset>
            <legend>Detail Postingan</legend>
            <div class="form-group">
                <label for="judul">Judul Postingan</label>
                <input type="text" id="judul" name="judul" required data-autofocus="true">
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi Postingan</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="tanggal_kegiatan">Tanggal Kegiatan</label>
                <input type="date"
                       id="tanggal_kegiatan"
                       name="tanggal_kegiatan"
                       value="<?php echo date('Y-m-d'); ?>"
                       required>
            </div>
            <div class="form-group">
                <label for="foto_path">Gambar Postingan</label>
                <input type="file" id="foto_path" name="foto_path" accept="image/*" required>
            </div>
        </fieldset>
        <div class="form-group">
            <button type="submit" class="btn-primary">Tambahkan Postingan Galeri</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3>Konten Halaman Galeri</h3>
    </div>
    <form method="post" action="<?php echo $adminBasePath; ?>proses/proses_galeri.php">
        <input type="hidden" name="edit_page" value="1">
        <div class="form-group">
            <label for="judul_page">Section Title</label>
            <input type="text"
                   id="judul_page"
                   name="judul_page"
                   value="<?php echo htmlspecialchars($pc['section_title'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="deskripsi_page">Section Description</label>
            <textarea id="deskripsi_page"
                      name="deskripsi_page"
                      rows="3"><?php echo htmlspecialchars($pc['section_description'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <button type="submit" name="submit_page" class="btn-primary">Simpan Konten Halaman</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3>Daftar Galeri</h3>
    </div>
    <table class="data-table">
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
                            <?php
                            $imageSrc = $projectBasePath . ltrim($g['foto_path'], '/');
                            ?>
                            <img src="<?php echo htmlspecialchars($imageSrc); ?>" class="table-img" alt="<?php echo htmlspecialchars($g['judul']); ?>">
                        </td>
                        <td><?php echo htmlspecialchars($g['judul']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($g['deskripsi'])); ?></td>
                        <td><?php echo htmlspecialchars($g['tanggal_kegiatan']); ?></td>
                        <td>
                            <form method="post"
                                  action="<?php echo $adminBasePath; ?>proses/proses_galeri.php"
                                  class="action-form">
                                <input type="hidden" name="edit" value="1">
                                <input type="hidden" name="id_galeri" value="<?php echo $g['id_galeri']; ?>">
                                <button type="button"
                                        class="btn-warning"
                                        onclick="openEditGaleri(<?php echo $g['id_galeri']; ?>)">
                                    Edit
                                </button>
                            </form>
                            <form method="post"
                                  action="<?php echo $adminBasePath; ?>proses/proses_galeri.php"
                                  onsubmit="return confirm('Hapus postingan ini?');"
                                  class="action-form">
                                <input type="hidden" name="hapus" value="1">
                                <input type="hidden" name="id_galeri" value="<?php echo $g['id_galeri']; ?>">
                                <button type="submit" class="btn-danger">Hapus</button>
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
        if (parseInt(input.value, 10) === parseInt(id, 10)) {
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
            f.action = '<?php echo $adminBasePath; ?>proses/proses_galeri.php';
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

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>
