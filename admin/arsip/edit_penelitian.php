<?php 
// File: admin/arsip/edit_penelitian.php
session_start();

$page_title = "KELOLA PENELITIAN";
$current_page = "edit_penelitian";
$adminPageStyles = ['forms', 'tables'];
include '../../config/koneksi.php';
require_once dirname(__DIR__) . '/includes/admin_header.php';

// Ambil data dosen untuk dropdown
$dosenOptions = [];
$dosenResult = pg_query($conn, "SELECT id_dosen, nama_dosen FROM dosen ORDER BY nama_dosen ASC");
if ($dosenResult) {
    $dosenOptions = pg_fetch_all($dosenResult) ?: [];
}

// Ambil data penelitian dari database
$qPenelitian = pg_query($conn, "
    SELECT p.*, d.nama_dosen 
    FROM penelitian p 
    LEFT JOIN dosen d ON p.id_author = d.id_dosen 
    ORDER BY p.tahun DESC, p.id_penelitian DESC");

// Ambil data section title & description untuk halaman penelitian
$page_key = "arsip_penelitian";
$qPage = pg_query_params($conn, "SELECT id_page FROM pages WHERE nama = $1", array($page_key));
if ($qPage && pg_num_rows($qPage) > 0) {
    $page = pg_fetch_assoc($qPage);
    $id_page = $page['id_page'];
    
    // Ambil section title
    $qTitle = pg_query_params($conn, 
        "SELECT content_value FROM page_content WHERE id_page = $1 AND content_key = 'section_title'",
        array($id_page)
    );
    $section_title = $qTitle && pg_num_rows($qTitle) > 0 ? pg_fetch_assoc($qTitle)['content_value'] : 'Penelitian';
    
    // Ambil section description
    $qDesc = pg_query_params($conn, 
        "SELECT content_value FROM page_content WHERE id_page = $1 AND content_key = 'section_description'",
        array($id_page)
    );
    $section_description = $qDesc && pg_num_rows($qDesc) > 0 ? pg_fetch_assoc($qDesc)['content_value'] : 'Daftar penelitian yang dilakukan oleh NCS Lab';
} else {
    $section_title = 'Penelitian';
    $section_description = 'Daftar penelitian yang dilakukan oleh NCS Lab';
}
?>

<div class="admin-header">
    <h1><?php echo $page_title; ?> (Tabel: penelitian)</h1>
    <p>Kelola data penelitian dan publikasi di sini</p>
</div>

<!-- ============================
     FORM EDIT SECTION TITLE & DESCRIPTION
=============================== -->
<div class="card">
    <form method="post" action="../proses/proses_penelitian.php">
        <input type="hidden" name="edit_page" value="1">
        <fieldset>
            <legend>Judul dan Deskripsi Halaman Penelitian</legend>
            <div class="form-group">
                <label for="judul_page">Judul Halaman</label>
                <input type="text" id="judul_page" name="judul_page" 
                       value="<?php echo htmlspecialchars($section_title); ?>"
                       data-autofocus="true">
            </div>
            <div class="form-group">
                <label for="deskripsi_page">Deskripsi Halaman</label>
                <textarea id="deskripsi_page" name="deskripsi_page" rows="4"><?php echo htmlspecialchars($section_description); ?></textarea>
            </div>
        </fieldset>
        <div class="form-group">
            <button type="submit" class="btn-primary">Simpan Judul & Deskripsi</button>
        </div>
    </form>
</div>

<!-- ============================
     FORM TAMBAH PENELITIAN
=============================== -->
<div class="card">
    <form method="post" action="../proses/proses_penelitian.php" enctype="multipart/form-data">
        <input type="hidden" name="tambah" value="1">

        <fieldset>
            <legend>Tambah Penelitian Baru</legend>
            
            <div class="form-group">
                <label for="judul_penelitian">Judul Penelitian</label>
                <input type="text" id="judul_penelitian" name="judul_penelitian" required>
            </div>
            
            <div class="form-group">
                <label for="deskripsi">Deskripsi Singkat</label>
                <textarea id="deskripsi" name="deskripsi" rows="6"></textarea>
            </div>
            
            <div class="form-group">
                <label for="tahun">Tahun Publikasi</label>
                <input type="number" id="tahun" name="tahun" value="<?php echo date('Y'); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="id_author">Penulis</label>
                <select id="id_author" name="id_author">
                    <option value="">Pilih Penulis</option>
                    <?php foreach ($dosenOptions as $dosen): ?>
                        <option value="<?php echo $dosen['id_dosen']; ?>">
                            <?php echo htmlspecialchars($dosen['nama_dosen']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="pdf">Upload File PDF</label>
                <input type="file" id="pdf" name="pdf" accept=".pdf">
                <span class="form-help-text">Unggah file PDF hasil penelitian.</span>
            </div>
        </fieldset>

        <div class="form-group">
            <button type="submit" class="btn-primary">Publikasikan Penelitian</button>
        </div>
    </form>
</div>

<br>

<!-- ============================
     TABEL DAFTAR PENELITIAN
=============================== -->
<div class="card">
    <div class="card-header">
        <h3>Daftar Penelitian</h3>
    </div>

    <table class="data-table" id="penelitianTable">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th>Judul Penelitian</th>
                <th class="col-jabatan">Peneliti</th>
                <th class="col-urutan">Tahun</th>
                <th class="col-status">File</th>
                <th class="col-aksi">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $hasData = false;

            while ($row = pg_fetch_assoc($qPenelitian)): 
                $hasData = true;
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td>
                    <strong><?php echo htmlspecialchars($row['judul_penelitian']); ?></strong>
                    <?php if (!empty($row['deskripsi'])): ?>
                    <br><small style="color: #666;"><?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?></small>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['nama_dosen'] ?? '-'); ?></td>
                <td style="text-align: center;"><?php echo $row['tahun']; ?></td>
                <td style="text-align: center;">
                    <?php if (!empty($row['media_path'])): ?>
                        <span class="badge badge-success">Ada PDF</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Tidak Ada</span>
                    <?php endif; ?>
                </td>
                <td style="text-align: center;">
                    <button class="btn-warning" 
                            onclick='openEditModal(<?php echo json_encode($row); ?>)'>
                        Edit
                    </button>

                    <form method="post" action="../proses/proses_penelitian.php" 
                          style="display:inline;" 
                          onsubmit="return confirm('Yakin ingin menghapus penelitian ini?');">
                        <input type="hidden" name="hapus" value="1">
                        <input type="hidden" name="id_penelitian" value="<?php echo $row['id_penelitian']; ?>">
                        <button type="submit" class="btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>

            <?php if (!$hasData): ?>
            <tr>
                <td colspan="6" style="text-align:center; padding:15px; color:#777;">
                    <strong>Belum ada penelitian yang ditambahkan</strong>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- ============================
     MODAL EDIT PENELITIAN
=============================== -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h3>Edit Penelitian</h3>
        <form method="post" action="../proses/proses_penelitian.php" enctype="multipart/form-data">
            <input type="hidden" name="edit" value="1">
            <input type="hidden" name="id_penelitian" id="edit_id">

            <div class="form-group">
                <label>Judul Penelitian</label>
                <input type="text" name="judul_penelitian" id="edit_judul" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" id="edit_deskripsi" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label>Tahun</label>
                <input type="number" name="tahun" id="edit_tahun" required>
            </div>

            <div class="form-group">
                <label>Penulis</label>
                <select name="id_author" id="edit_author">
                    <option value="">Pilih Penulis</option>
                    <?php foreach ($dosenOptions as $dosen): ?>
                        <option value="<?php echo $dosen['id_dosen']; ?>">
                            <?php echo htmlspecialchars($dosen['nama_dosen']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>File PDF (Biarkan kosong jika tidak ingin mengubah)</label>
                <input type="file" name="pdf" accept=".pdf">
                <span class="form-help-text" id="current_file"></span>
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
    document.getElementById("edit_id").value = row.id_penelitian;
    document.getElementById("edit_judul").value = row.judul_penelitian;
    document.getElementById("edit_deskripsi").value = row.deskripsi || '';
    document.getElementById("edit_tahun").value = row.tahun;
    document.getElementById("edit_author").value = row.id_author || '';
    
    // Tampilkan info file saat ini
    const currentFile = document.getElementById("current_file");
    if (row.media_path) {
        currentFile.innerHTML = `File saat ini: <strong>${row.media_path}</strong>`;
        currentFile.style.color = "var(--success-green)";
    } else {
        currentFile.innerHTML = "Belum ada file PDF";
        currentFile.style.color = "var(--danger-red)";
    }

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