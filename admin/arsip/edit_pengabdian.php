<?php 
// File: admin/arsip/edit_pengabdian.php
session_start();

$page_title = "Kelola Pengabdian";
$current_page = "edit_pengabdian";
$adminPageStyles = ['forms', 'tables'];
include '../../config/koneksi.php';
require_once dirname(__DIR__) . '/includes/admin_header.php';

// Ambil data dosen untuk dropdown
$dosenOptions = [];
$dosenResult = pg_query($conn, "SELECT id_dosen, nama_dosen FROM dosen ORDER BY nama_dosen ASC");
if ($dosenResult) {
    $dosenOptions = pg_fetch_all($dosenResult) ?: [];
}

// Ambil data pengabdian dari database
$qPengabdian = pg_query($conn, "
    SELECT * FROM view_pengabdian ORDER BY tahun DESC");

// Ambil data section title & description untuk halaman pengabdian
$page_key = "arsip_pengabdian";
$qPage = pg_query_params($conn, "SELECT id_page FROM pages WHERE nama = $1", array($page_key));
if ($qPage && pg_num_rows($qPage) > 0) {
    $page = pg_fetch_assoc($qPage);
    $id_page = $page['id_page'];
    
    // Ambil section title
    $qTitle = pg_query_params($conn, 
        "SELECT content_value FROM page_content WHERE id_page = $1 AND content_key = 'section_title'",
        array($id_page)
    );
    $section_title = $qTitle && pg_num_rows($qTitle) > 0 ? pg_fetch_assoc($qTitle)['content_value'] : 'Pengabdian Masyarakat';
    
    // Ambil section description
    $qDesc = pg_query_params($conn, 
        "SELECT content_value FROM page_content WHERE id_page = $1 AND content_key = 'section_description'",
        array($id_page)
    );
    $section_description = $qDesc && pg_num_rows($qDesc) > 0 ? pg_fetch_assoc($qDesc)['content_value'] : 'Daftar kegiatan pengabdian masyarakat yang dilakukan oleh NCS Lab';
} else {
    $section_title = '';
    $section_description = '';
}
?>

<div class="admin-header">
    <h1><?php echo $page_title; ?> </h1>
    <p>Kelola data pengabdian masyarakat di sini</p>
</div>

<!-- ============================
     FORM EDIT SECTION TITLE & DESCRIPTION
=============================== -->
<div class="card">
    <form method="post" action="../proses/proses_pengabdian.php">
        <input type="hidden" name="edit_page_content" value="1">
        <fieldset>
            <legend>Judul dan Deskripsi Halaman Pengabdian</legend>
            <div class="form-group">
                <label for="judul_page">Judul Halaman</label>
                <input type="text" id="judul_page" name="judul_page" placeholder="Masukkan judul halaman pengabdian"
                       value="<?php echo htmlspecialchars($section_title); ?>"
                       data-autofocus="true">
            </div>
            <div class="form-group">
                <label for="deskripsi_page">Deskripsi Halaman</label>
                <textarea id="deskripsi_page" name="deskripsi_page" rows="4" placeholder="Masukkan deskripsi halaman pengabdian"><?php echo htmlspecialchars($section_description);      ?></textarea>
            </div>
        </fieldset>
        <div class="form-group">
            <button type="submit" class="btn-primary">Simpan Judul & Deskripsi</button>
        </div>
    </form>
</div>

<!-- ============================
     FORM TAMBAH PENGABDIAN
=============================== -->
<div class="card">
    <form method="post" action="../proses/proses_pengabdian.php">
        <input type="hidden" name="tambah" value="1">

        <fieldset>
            <legend>Tambah Pengabdian Baru</legend>
            
            <div class="form-group">
                <label for="judul_pengabdian">Judul Pengabdian</label>
                <input type="text" id="judul_pengabdian" name="judul_pengabdian" placeholder="Masukkan judul pengabdian" required>
            </div>
            
            <div class="form-group">
                <label for="skema">Skema</label>
                <input type="text" id="skema" name="skema" placeholder="Masukkan skema pengabdian" required>
            </div>
            
            <div class="form-group">
                <label for="tahun">Tahun Pelaksanaan</label>
                <input type="number" id="tahun" name="tahun" value="<?php echo date('Y'); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="id_ketua">Ketua Tim</label>
                <select id="id_ketua" name="id_ketua" required>
                    <option value="">Pilih Ketua</option>
                    <?php foreach ($dosenOptions as $dosen): ?>
                        <option value="<?php echo $dosen['id_dosen']; ?>">
                            <?php echo htmlspecialchars($dosen['nama_dosen']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </fieldset>

        <div class="form-group">
            <button type="submit" class="btn-primary">Publikasikan Pengabdian</button>
        </div>
    </form>
</div>

<br>

<!-- ============================
     TABEL DAFTAR PENGABDIAN
=============================== -->
<div class="card">
    <div class="card-header">
        <h3>Daftar Pengabdian Masyarakat</h3>
    </div>

    <table class="data-table" id="pengabdianTable">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th>Judul Pengabdian</th>
                <th class="col-jabatan">Ketua Tim</th>
                <th class="col-urutan">Skema</th>
                <th class="col-urutan">Tahun</th>
                <th style="width:300px;text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $hasData = false;

            while ($row = pg_fetch_assoc($qPengabdian)): 
                $hasData = true;
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td>
                    <strong><?php echo htmlspecialchars($row['judul_pengabdian']); ?></strong>
                    <?php if (!empty($row['deskripsi'])): ?>
                    <br><small style="color: #666;"><?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?></small>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['nama_dosen'] ?? '-'); ?></td>
                <td style="text-align: center;">
                    <span class="badge badge-info"><?php echo htmlspecialchars($row['skema']); ?></span>
                </td>
                <td style="text-align: center;"><?php echo $row['tahun']; ?></td>
                <td style="text-align: center;">
                    <button class="btn-warning" 
                            onclick='openEditModal(<?php echo json_encode($row); ?>)'>
                        Edit
                    </button>

                    <form method="post" action="../proses/proses_pengabdian.php" 
                          style="display:inline;" 
                          onsubmit="return confirm('Yakin ingin menghapus pengabdian ini?');">
                        <input type="hidden" name="hapus" value="1">
                        <input type="hidden" name="id_pengabdian" value="<?php echo $row['id_pengabdian']; ?>">
                        <button type="submit" class="btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>

            <?php if (!$hasData): ?>
            <tr>
                <td colspan="6" style="text-align:center; padding:15px; color:#777;">
                    <strong>Belum ada pengabdian masyarakat yang ditambahkan</strong>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- ============================
     MODAL EDIT PENGABDIAN
=============================== -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h3>Edit Pengabdian Masyarakat</h3>
        <form method="post" action="../proses/proses_pengabdian.php">
            <input type="hidden" name="edit" value="1">
            <input type="hidden" name="id_pengabdian" id="edit_id">

            <div class="form-group">
                <label>Judul Pengabdian</label>
                <input type="text" name="judul_pengabdian" id="edit_judul" required>
            </div>

            <div class="form-group">
                <label>Skema</label>
                <input type="text" name="skema" id="edit_skema" required>
            </div>

            <div class="form-group">
                <label>Tahun</label>
                <input type="number" name="tahun" id="edit_tahun" required>
            </div>

            <div class="form-group">
                <label>Ketua Tim</label>
                <select name="id_ketua" id="edit_ketua" required>
                    <option value="">Pilih Ketua</option>
                    <?php foreach ($dosenOptions as $dosen): ?>
                        <option value="<?php echo $dosen['id_dosen']; ?>">
                            <?php echo htmlspecialchars($dosen['nama_dosen']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-top: 20px; display: flex; gap: 10px;">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================
     SCRIPT JS
=============================== -->
<script>
function openEditModal(row) {
    document.getElementById("edit_id").value = row.id_pengabdian;
    document.getElementById("edit_judul").value = row.judul_pengabdian;
    document.getElementById("edit_skema").value = row.skema || '';
    document.getElementById("edit_tahun").value = row.tahun;
    document.getElementById("edit_ketua").value = row.id_ketua || '';

    document.getElementById("editModal").style.display = "block";
}

function closeModal() {
    document.getElementById("editModal").style.display = "none";
}

// Close modal ketika klik di luar modal
window.onclick = function(event) {
    const modal = document.getElementById("editModal");
    if (event.target == modal) {
        closeModal();
    }
}
</script>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>