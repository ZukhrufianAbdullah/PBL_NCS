<?php
// File: admin/galeri/edit_galeri.php
session_start();

$pageTitle = 'Kelola Galeri Foto';
$currentPage = 'edit_galeri';
$adminPageStyles = ['tables', 'dashboard', 'forms'];
include_once '../../config/koneksi.php';
require_once dirname(__DIR__) . '/includes/admin_header.php';

//Ambil data judul
$qJudulGaleri = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'galeri_galeri' AND pc.content_key = 'judul_galeri'
    LIMIT 1");
$judulGaleri = pg_fetch_assoc($qJudulGaleri)['content_value'] ?? '';

// Ambil data deskripsi
$qDeskripsiGaleri = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'galeri_galeri' AND pc.content_key = 'deskripsi_galeri'
    LIMIT 1");
$deskripsiGaleri = pg_fetch_assoc($qDeskripsiGaleri)['content_value'] ?? '';

// Ambil data Galeri
$qGaleri = pg_query($conn, "
    SELECT * 
    FROM galeri
    ORDER BY tanggal ASC");
?>

<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: galeri)</h1>
    <p>Kelola halaman agenda di sini</p>
</div>
<div class="card">
    <form method="post" action="../proses/proses_galeri.php">
        <input type="hidden" name="edit_page_content" value="1">
        <fieldset>
            <legend>Judul dan Deskripsi Galeri</legend>
            <div class="form-group">
                <label for="judul_galeri">Judul Halaman</label>
                <input type="text"
                       id="judul_galeri"
                       name="judul_galeri"
                       value="<?php echo htmlspecialchars($judulGaleri);?>"
                       data-autofocus ="true">
            </div>
            <div class="form-group">
                <label for="deskripsi_galeri">Deskripsi Singkat Halaman</label>
                <textarea id="deskripsi_galeri"
                          name="deskripsi_galeri"
                          rows="4"><?php echo htmlspecialchars($deskripsiGaleri); ?></textarea> 
            </div>
        </fieldset>
        <div class="form-group">
            <button type="submit" name="submit_judul_deskripsi_galeri" class="btn-primary">Simpan Konten Halaman</button>
        </div>
    </form>
</div>
<!-- =========================================================
     FORM TAMBAH GALERI
========================================================= -->
<div class="card">
    <form method="post" action="../proses/proses_galeri.php" enctype="multipart/form-data">
        <input type="hidden" name="tambah_galeri" value="1">

        <fieldset>
            <legend>Tambah Galeri Baru</legend>

            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="judul" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" required value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label>Upload Gambar</label>
                <input type="file" name="gambar" accept=".png,.jpg,.jpeg,.svg" required>
            </div>

        </fieldset>

        <div class="form-group">
            <button type="submit" class="btn-primary">Tambah Galeri</button>
        </div>
    </form>
</div>


<br>

<!-- =========================================================
     TABEL DAFTAR GALERI
========================================================= -->
<div class="card">
    <div class="card-header">
        <h3>Daftar Galeri</h3>
    </div>

    <table class="data-table" id="galeriTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Tanggal</th>
                <th style="width:150px;text-align:center;">Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php 
        $no = 1;
        $hasData = false;
        while ($row = pg_fetch_assoc($qGaleri)):
            $hasData = true;
        ?>
        <tr>
            <td><?php echo $no++; ?></td>

            <td>
                <img src="../../uploads/galeri/<?php echo htmlspecialchars($row['media_path']); ?>"
                     style="width:70px;border-radius:4px;">
            </td>

            <td><?php echo htmlspecialchars($row['judul']); ?></td>

            <td><?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?></td>

            <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>

            <td style="text-align:center;">

                <!-- Tombol Edit -->
                <button class="btn-warning"
                        onclick='openEditModal(<?php echo json_encode($row); ?>)'>
                    Edit
                </button>

                <!-- Tombol Hapus -->
                <form method="post" action="../proses/proses_galeri.php"
                      style="display:inline;"
                      onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                    <input type="hidden" name="hapus" value="1">
                    <input type="hidden" name="id_galeri" value="<?php echo $row['id_galeri']; ?>">
                    <button type="submit" class="btn-danger">Hapus</button>
                </form>

            </td>
        </tr>
        <?php endwhile; ?>

        <?php if (!$hasData): ?>
        <tr>
            <td colspan="6" style="text-align:center;padding:15px;color:#777;">
                <strong>Belum ada galeri yang ditambahkan</strong>
            </td>
        </tr>
        <?php endif; ?>
        </tbody>

    </table>
</div>



<!-- =========================================================
     MODAL EDIT GALERI
========================================================= -->
<div id="editModal" class="modal" style="
    display:none; position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(0,0,0,0.5); justify-content:center; align-items:center;
">
    <div class="modal-content" style="background:#fff; padding:20px; width:420px; border-radius:8px;">
        <h3>Edit Galeri</h3>

        <form method="post" action="../proses/proses_galeri.php" enctype="multipart/form-data">
            <input type="hidden" name="edit_galeri" value="1">
            <input type="hidden" name="id_galeri" id="edit_id">

            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="judul" id="edit_judul" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" id="edit_deskripsi" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" id="edit_tanggal" required>
            </div>

            <div class="form-group">
                <label>Ganti Gambar (Opsional)</label>
                <input type="file" name="gambar" accept=".png,.jpg,.jpeg,.svg">
            </div>

            <div class="form-group" style="margin-top:10px;">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <button type="button" class="btn-danger" onclick="closeModal()">Batal</button>
            </div>
        </form>
    </div>
</div>


<!-- =========================================================
     SCRIPT JS
========================================================= -->
<script>
function openEditModal(row) {
    document.getElementById("edit_id").value = row.id_galeri;
    document.getElementById("edit_judul").value = row.judul;
    document.getElementById("edit_deskripsi").value = row.deskripsi;
    document.getElementById("edit_tanggal").value = row.tanggal;

    document.getElementById("editModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("editModal").style.display = "none";
}
</script>

        

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>