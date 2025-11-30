<?php
// File: admin/layanan/edit_sarana_prasarana.php
session_start();
$pageTitle = 'Manajemen Sarana & Prasarana';
$currentPage = 'edit_sarana';
$adminPageStyles = ['forms', 'tables'];
include_once '../../config/koneksi.php';
require_once dirname(__DIR__) . '/includes/admin_header.php';

// Ambil data Sarana
$qSarana = pg_query($conn, "
    SELECT * 
    FROM sarana
    ORDER BY nama_sarana ASC");

// Ambil data section title & description untuk halaman sarana
$page_key = "layanan_sarana";
$qPage = pg_query_params($conn, "SELECT id_page FROM pages WHERE nama = $1", array($page_key));
if ($qPage && pg_num_rows($qPage) > 0) {
    $page = pg_fetch_assoc($qPage);
    $id_page = $page['id_page'];
    
    // Ambil section title
    $qTitle = pg_query_params($conn, 
        "SELECT content_value FROM page_content WHERE id_page = $1 AND content_key = 'section_title'",
        array($id_page)
    );
    $section_title = $qTitle && pg_num_rows($qTitle) > 0 ? pg_fetch_assoc($qTitle)['content_value'] : 'Sarana & Prasarana';
    
    // Ambil section description
    $qDesc = pg_query_params($conn, 
        "SELECT content_value FROM page_content WHERE id_page = $1 AND content_key = 'section_description'",
        array($id_page)
    );
    $section_description = $qDesc && pg_num_rows($qDesc) > 0 ? pg_fetch_assoc($qDesc)['content_value'] : 'Fasilitas dan infrastruktur yang mendukung kegiatan NCS Lab';
} else {
    $section_title = 'Sarana & Prasarana';
    $section_description = 'Fasilitas dan infrastruktur yang mendukung kegiatan NCS Lab';
}
?>
<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: sarana)</h1>
    <p>Kelola halaman sarana dan prasarana di sini</p>
</div>

<!-- ============================
     FORM EDIT SECTION TITLE & DESCRIPTION
=============================== -->
<div class="card">
    <form method="post" action="../proses/proses_sarana_prasarana.php">
        <input type="hidden" name="edit_section_content" value="1">
        <fieldset>
            <legend>Judul dan Deskripsi Halaman Sarana Prasarana</legend>
            <div class="form-group">
                <label for="section_title">Judul Halaman</label>
                <input type="text" id="section_title" name="section_title" 
                       value="<?php echo htmlspecialchars($section_title); ?>"
                       data-autofocus="true">
            </div>
            <div class="form-group">
                <label for="section_description">Deskripsi Halaman</label>
                <textarea id="section_description" name="section_description" rows="4"><?php echo htmlspecialchars($section_description); ?></textarea>
            </div>
        </fieldset>
        <div class="form-group">
            <button type="submit" class="btn-primary">Simpan Judul & Deskripsi</button>
        </div>
    </form>
</div>

<div class="card">
    <form method="post" action="../proses/proses_sarana_prasarana.php" enctype="multipart/form-data">
        <input type="hidden" name="tambah_sarana" value="1">
        <fieldset>
            <legend>Tambah Sarana dan Prasarana Baru</legend>
            
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama_sarana" required>
            </div>

            <div class="form-group">
                <label>Upload Gambar</label>
                <input type="file" name="gambar" accept=".png,.jpg,.jpeg,.svg" required>
            </div>
        </fieldset>
        <div class="form-group">
            <button type="submit" class="btn-primary">Tambah Sarana dan Prasarana</button>
        </div>
    </form>
</div>

<!-- ======================================================
     TABEL DATA SARANA
====================================================== -->
<div class="card">
    <div class="card-header">
        <h3>Daftar Sarana dan Prasarana</h3>
    </div>

    <table class="data-table" id="saranaTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Nama Sarana</th>
                <th style="width:150px;text-align:center;">Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php 
            $no = 1; $hasData = false;
            while ($row = pg_fetch_assoc($qSarana)):
                $hasData = true;
            ?>
            <tr>
                <td><?php echo $no++; ?></td>

                <td>
                    <img src="../../uploads/sarana_prasarana/<?php echo htmlspecialchars($row['media_path']); ?>"
                         style="width:70px;border-radius:4px;">
                </td>

                <td><?php echo htmlspecialchars($row['nama_sarana']); ?></td>

                <td style="text-align:center;">

                    <!-- Tombol Edit -->
                    <button class="btn-warning"
                            onclick='openEditModal(<?php echo json_encode($row); ?>)'>
                        Edit
                    </button>

                    <!-- Tombol Hapus -->
                    <form method="post" action="../proses/proses_sarana_prasarana.php"
                          style="display:inline;"
                          onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                        <input type="hidden" name="hapus_sarana" value="1">
                        <input type="hidden" name="id_sarana" value="<?php echo $row['id_sarana']; ?>">
                        <button type="submit" class="btn-danger">Hapus</button>
                    </form>

                </td>
            </tr>
            <?php endwhile; ?>

            <?php if (!$hasData): ?>
            <tr>
                <td colspan="4" style="text-align:center;padding:15px;color:#777;">
                    <strong>Belum ada sarana ditambahkan</strong>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>

    </table>
</div>

<!-- ======================================================
     MODAL EDIT
====================================================== -->
<div id="editModal" class="modal" style="
    display:none; position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
    
    <div class="modal-content" style="background:#fff; padding:20px; width:420px; border-radius:8px;">
        <h3>Edit Sarana</h3>

        <form method="post" action="../proses/proses_sarana_prasarana.php" enctype="multipart/form-data">
            <input type="hidden" name="edit_sarana" value="1">
            <input type="hidden" name="id_sarana" id="edit_id">

            <div class="form-group">
                <label>Nama Sarana</label>
                <input type="text" name="nama_sarana" id="edit_nama" required>
            </div>

            <div class="form-group">
                <label>Gambar Saat Ini</label><br>
                <img id="edit_preview" src="" 
                     style="width:120px;border-radius:6px;margin-bottom:8px;">
            </div>

            <div class="form-group">
                <label>Ganti Gambar</label>
                <input type="file" name="gambar" accept=".png,.jpg,.jpeg,.svg">
            </div>

            <div class="form-group" style="margin-top:10px;">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <button type="button" class="btn-danger" onclick="closeModal()">Batal</button>
            </div>

        </form>
    </div>
</div>

<!-- ======================================================
     SCRIPT JS
====================================================== -->
<script>
function openEditModal(row) {
    document.getElementById("edit_id").value = row.id_sarana;
    document.getElementById("edit_nama").value = row.nama_sarana;

    // Tampilkan gambar lama
    document.getElementById("edit_preview").src =
        "../../uploads/sarana_prasarana/" + row.media_path;

    document.getElementById("editModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("editModal").style.display = "none";
}
</script>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>