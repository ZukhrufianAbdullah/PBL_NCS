<?php
// File: admin/profil/edit_struktur.php
session_start();
include '../../config/koneksi.php';

$pageTitle = 'Edit Struktur Organisasi';
$currentPage = 'edit_struktur';
$adminPageStyles = ['forms', 'tables'];

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
            if ($row['content_key'] === 'section_title') $judul_page = $row['content_value'];
            if ($row['content_key'] === 'section_description') $deskripsi_page = $row['content_value'];
        }
    }
}
require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: dosen &amp; anggota_lab)</h1>
    <p>Tambah atau perbarui anggota laboratorium, termasuk nama, jabatan, dan foto profil.</p>
</div>

<div class="card">
    <form method="post" action="<?php echo $adminBasePath; ?>proses/proses_struktur.php">
        <input type="hidden" name="edit_page_content" value="1">
        <fieldset>
            <legend>Konten Halaman Struktur Organisasi</legend>
            <div class="form-group">
                <label for="judul_page">Judul Halaman</label>
                <input type="text"
                       id="judul_page"
                       name="judul_page"
                       value="<?php echo htmlspecialchars($judul_page ?? ''); ?>"
                       data-autofocus="true">
            </div>
            <div class="form-group">
                <label for="deskripsi_page">Deskripsi Singkat Halaman</label>
                <textarea id="deskripsi_page"
                          name="deskripsi_page"
                          rows="4"><?php echo htmlspecialchars($deskripsi_page ?? ''); ?></textarea>
            </div>
        </fieldset>
        <div class="form-group">
            <button type="submit" name="submit" class="btn-primary">Simpan Konten Halaman</button>
        </div>
    </form>
</div>

<div class="card">
    <form method="post"
          action="<?php echo $adminBasePath; ?>proses/proses_struktur.php"
          enctype="multipart/form-data">
        <input type="hidden" name="tambah" value="1">
        <fieldset>
            <legend>Tambah Anggota Tim Baru</legend>
            <div class="form-group">
                <label for="nama_dosen_new">Nama Lengkap &amp; Gelar (Kolom: nama_dosen)</label>
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
        </fieldset>
        <div class="form-group">
            <button type="submit" class="btn-primary">Tambahkan Anggota Baru</button>
        </div>
    </form>
</div>

<div class="card">
    <table class="data-table">
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
                            <?php
                            $img = !empty($m['media_path'])
                                ? $projectBasePath . 'uploads/dosen/' . $m['media_path']
                                : $projectBasePath . 'uploads/dosen/default.png';
                            ?>
                            <img src="<?php echo $img; ?>" alt="" class="table-img">
                        </td>
                        <td>
                            <button type="button" 
                                    class="btn-warning btn-sm" 
                                    onclick="openEditModal(
                                        <?php echo $m['id_anggota']; ?>, 
                                        <?php echo $m['id_dosen']; ?>, 
                                        '<?php echo htmlspecialchars($m['nama_dosen']); ?>', 
                                        '<?php echo htmlspecialchars($m['jabatan']); ?>',
                                        '<?php echo $m['media_path']; ?>'
                                    )">
                                Edit
                            </button>

                            <form class="action-form"
                                  method="post"
                                  action="<?php echo $adminBasePath; ?>proses/proses_struktur.php"
                                  onsubmit="return confirm('Yakin ingin menghapus anggota ini?');">
                                <input type="hidden" name="hapus" value="1">
                                <input type="hidden" name="id_anggota" value="<?php echo $m['id_anggota']; ?>">
                                <button type="submit" class="btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Edit Anggota -->
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Anggota</h2>
        <form id="editForm" method="post" action="<?php echo $adminBasePath; ?>proses/proses_struktur.php" enctype="multipart/form-data">
            <input type="hidden" name="edit" value="1">
            <input type="hidden" name="id_anggota" id="modal_id_anggota">
            <input type="hidden" name="id_dosen" id="modal_id_dosen">
            
            <div class="form-group">
                <label for="modal_nama_dosen">Nama Lengkap</label>
                <input type="text" id="modal_nama_dosen" name="nama_dosen" required>
            </div>
            
            <div class="form-group">
                <label for="modal_jabatan">Jabatan</label>
                <input type="text" id="modal_jabatan" name="jabatan" required>
            </div>
            
            <div class="form-group">
                <label for="modal_foto">Foto Profil</label>
                <input type="file" id="modal_foto" name="foto" accept="image/*">
                <small class="form-help-text">Biarkan kosong jika tidak ingin mengubah foto</small>
            </div>
            
            <div class="form-group">
                <label>Foto Saat Ini:</label>
                <div id="currentPhotoContainer">
                    <img id="modal_current_photo" src="" alt="Current Photo" style="max-width: 150px; max-height: 150px; border-radius: 8px;">
                    <div id="currentPhotoName" class="text-muted" style="margin-top: 5px;"></div>
                </div>
            </div>
            
            <div class="form-group" style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
// Modal functionality
const modal = document.getElementById("editModal");
const closeBtn = document.querySelector(".close");

function openEditModal(id_anggota, id_dosen, nama, jabatan, media_path) {
    document.getElementById('modal_id_anggota').value = id_anggota;
    document.getElementById('modal_id_dosen').value = id_dosen;
    document.getElementById('modal_nama_dosen').value = nama;
    document.getElementById('modal_jabatan').value = jabatan;
    
    // Set current photo preview
    const currentPhoto = document.getElementById('modal_current_photo');
    const currentPhotoName = document.getElementById('currentPhotoName');
    
    if (media_path && media_path !== 'default.png') {
        currentPhoto.src = '<?php echo $projectBasePath; ?>uploads/dosen/' + media_path;
        currentPhotoName.textContent = media_path;
    } else {
        currentPhoto.src = '<?php echo $projectBasePath; ?>uploads/dosen/default.png';
        currentPhotoName.textContent = 'default.png';
    }
    
    modal.style.display = "block";
}

function closeModal() {
    modal.style.display = "none";
    document.getElementById('editForm').reset();
}

// Close modal when clicking X or outside
closeBtn.onclick = closeModal;

window.onclick = function(event) {
    if (event.target == modal) {
        closeModal();
    }
}

// Preview image when new file is selected
document.getElementById('modal_foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('modal_current_photo').src = e.target.result;
            document.getElementById('currentPhotoName').textContent = file.name;
        }
        reader.readAsDataURL(file);
    }
});
</script>

<style>
/* Modal Styles */
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
}

.close {
    position: absolute;
    right: 1rem;
    top: 1rem;
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
}

.close:hover {
    color: #000;
}

.table-img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
}

.btn-sm {
    padding: 0.4rem 0.8rem;
    font-size: 0.875rem;
}

.action-form {
    display: inline-block;
    margin-right: 0.5rem;
}
</style>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>