<?php
// File: admin/profil/edit_logo.php (Lokasi TETAP: admin/profil/edit_logo.php)
session_start();
$pageTitle = 'Edit Logo Website';
$currentPage = 'edit_logo';
$adminPageStyles = ['forms'];

$baseUrl = '/PBL_NCS';

// --- Data Dummy ---
$logos = [
    [
        'id' => 1,
        'nama_logo' => 'Politeknik Negeri Malang',
        'media_path' => $base_url . '/asset/img/logo_polinema.png', 
    ],
    [
        'id' => 2,
        'nama_logo' => 'Jurusan Teknologi Informasi',
        'media_path' => $base_url . '/asset/img/logo_jti.png', 
    ],
];

require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: logo)</h1>
    <p>Kelola logo utama yang tampil pada halaman profil beserta teks pendukungnya.</p>
</div>

<div class="card">
    <form method="post"
          action="<?php echo $adminBasePath; ?>proses/proses_logo.php"
          enctype="multipart/form-data">
        <fieldset>
            <legend>Pengaturan Judul Utama</legend>
            <div class="form-group">
                <label for="judul_sub">Sub Judul <span class="required">*</span></label>
                <span class="form-subtitle">Contoh: Visi &amp; Misi</span>
                <input type="text"
                       id="judul_sub"
                       name="judul_sub"
                       value="<?php echo htmlspecialchars($data['judul_sub'] ?? ''); ?>"
                       required
                       data-autofocus="true">
            </div>
            <div class="form-group">
                <label for="deskripsi_sub">Deskripsi Sub Judul <span class="required">*</span></label>
                <span class="form-subtitle">Teks pendek yang tampil di bawah judul utama.</span>
                <textarea id="deskripsi_sub"
                          name="deskripsi_sub"
                          rows="3"
                          required><?php echo htmlspecialchars($data['deskripsi_sub'] ?? ''); ?></textarea>
            </div>
        </fieldset>

        <div class="form-group">
            <button type="submit" class="btn-primary">Unggah &amp; Simpan Logo</button>
        </div>
    </form>
</div>

<div class="logo-grid">
    <?php foreach ($logos as $logo): ?>
        <div class="logo-card">
            <div class="logo-container">
                <img src="<?php echo htmlspecialchars($logo['media_path']); ?>"
                     alt="<?php echo htmlspecialchars($logo['nama_logo']); ?>">
            </div>

            <p class="logo-title"><?php echo htmlspecialchars($logo['nama_logo']); ?></p>

            <div class="card-actions">
                <button type="button"
                        class="btn-warning"
                        onclick="openEditModal(
                            <?php echo $logo['id']; ?>,
                            '<?php echo addslashes($logo['nama_logo']); ?>',
                            '<?php echo addslashes($logo['media_path']); ?>'
                        )">
                    Edit
                </button>
                <a href="javascript:void(0)" class="btn-danger">Hapus</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('editModal')">&times;</span>
        <h3 id="editModalTitle">Edit Logo</h3>
        <form action="<?php echo $adminBasePath; ?>proses/proses_logo.php"
              method="POST"
              enctype="multipart/form-data">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id_logo" id="edit_id">
            <input type="hidden" name="old_media_path" id="edit_old_path">

            <div class="form-group">
                <label for="edit_nama">Nama Logo <span class="required">*</span></label>
                <input type="text" id="edit_nama" name="nama_logo" required>
            </div>

            <div class="form-group">
                <label for="edit_file">Upload Logo Baru (PNG/JPG, Max 2MB)</label>
                <input type="file"
                       id="edit_file"
                       name="logo_file"
                       accept="image/png, image/jpeg, image/jpg"
                       onchange="previewImage(this, 'edit_preview')">
                <span class="form-help-text">Kosongkan jika tidak ingin mengganti logo.</span>
            </div>

            <div class="form-group">
                <label>Preview Logo Saat Ini</label>
                <img id="edit_preview" class="preview-img" src="" alt="Preview Logo">
            </div>

            <button type="submit" class="btn-primary" name="submit">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
    function openModal(modalId, title = '') {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        modal.style.display = 'block';
        const titleEl = modal.querySelector('h3');
        if (title && titleEl) {
            titleEl.innerText = title;
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
        }
    }

    function openEditModal(id, nama, path) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nama').value = nama;
        const relativePath = path.substring(path.indexOf('asset/img/'));
        document.getElementById('edit_old_path').value = relativePath;
        document.getElementById('edit_preview').src = path;
        document.getElementById('edit_file').value = '';
        document.getElementById('editModalTitle').innerText = 'Edit Logo: ' + nama;
        openModal('editModal');
    }

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (!preview) {
            return;
        }
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else if (previewId === 'add_preview') {
            preview.src = '';
        }
    }

    window.onclick = function (event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    };
</script>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>