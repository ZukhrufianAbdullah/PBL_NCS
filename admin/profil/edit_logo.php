<?php 
// File: admin/profil/edit_logo.php
session_start();

$page_title = "Manajemen Logo";
$current_page = "edit_logo";
$base_url = '../';

// --- Data Dummy ---
$logos = [
    [
        'id' => 1,
        'nama_logo' => 'Politeknik Negeri Malang',
        'media_path' => $base_url . 'asset/img/logo_polinema.png', 
    ],
    [
        'id' => 2,
        'nama_logo' => 'Jurusan Teknologi Informasi',
        'media_path' => $base_url . 'asset/img/logo_jti.png', 
    ],
];

// --- Logika Alert ---
$alert_message = '';
$alert_type = '';

if (isset($_GET['success'])) {
    $alert_type = 'alert-success';
    if ($_GET['success'] == 'add') {
        $alert_message = 'Logo baru berhasil ditambahkan!';
    } elseif ($_GET['success'] == 'update') {
        $alert_message = 'Logo berhasil diperbarui!';
    } elseif ($_GET['success'] == 'delete') {
        $alert_message = 'Logo berhasil dihapus!';
    }
} elseif (isset($_GET['error'])) {
    $alert_type = 'alert-error';
    $alert_message = htmlspecialchars($_GET['error']); 
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="/admin/asset/css/style_admin.css">
    </head>
<body>

    <div class="sidebar">
        <h2>ADMIN NCS LAB</h2>
        
        <a href="index.php">Dashboard</a>

        <div class="menu-header">PENGATURAN TAMPILAN</div>
        <a href="/admin/include/edit_header.php">Edit Header</a>
        <a href="/admin/include/edit_footer.php">Edit Footer</a>
        <a href="/admin/admin/beranda/edit_beranda.php">Edit Beranda</a>
        <a href="/admin/admin/beranda/edit_banner.php">Edit Banner</a>

        <div class="menu-header">MANAJEMEN KONTEN</div>
        
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('manajemenKonten')">
                PROFIL
                <span class="dropdown-icon" id="icon-manajemenKonten">></span>
            </a>
            <div class="submenu-wrapper" id="manajemenKonten">
                <a href="/admin/admin/profil/edit_visi_misi.php">Visi & Misi</a>
                <a href="/admin/admin/profil/edit_struktur.php">Struktur Organisasi</a>
                <a href="/admin/admin/profil/edit_logo.php">Edit Logo</a>
            </div>
        </div>
        
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('galeriMenu')">
                GALERI
                <span class="dropdown-icon" id="icon-galeriMenu">></span>
            </a>
            <div class="submenu-wrapper" id="galeriMenu">
                <div class="menu-subheader">GALERI FOTO/VIDEO</div>
                <a href="/admin/admin/galeri/tambah_galeri.php">Tambah Galeri</a>
                <a href="/admin/admin/galeri/edit_galeri.php">Kelola Galeri</a>
                <div class="menu-subheader">AGENDA</div>
                <a href="/admin/admin/galeri/tambah_agenda.php">Tambah Agenda</a>
                <a href="/admin/admin/galeri/edit_agenda.php">Kelola Agenda</a>
            </div>
        </div>
        
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('arsipMenu')">
                ARSIP
                <span class="dropdown-icon" id="icon-arsipMenu">></span>
            </a>
            <div class="submenu-wrapper" id="arsipMenu">
                <div class="menu-subheader">PENELITIAN</div>
                <a href="/admin/admin/arsip/tambah_penelitian.php">Tambah Penelitian</a>
                <a href="/admin/admin/arsip/edit_penelitian.php">Kelola Penelitian</a>
                <div class="menu-subheader">PENGABDIAN</div>
                <a href="/admin/admin/arsip/tambah_pengabdian.php">Tambah Pengabdian</a>
                <a href="/admin/admin/arsip/edit_pengabdian.php">Kelola Pengabdian</a>
            </div>
        </div>

        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('layananMenu')">
                LAYANAN
                <span class="dropdown-icon" id="icon-layananMenu">></span>
            </a>
            <div class="submenu-wrapper" id="layananMenu">
                <a href="/admin/admin/layanan/edit_sarana_prasarana.php">Sarana & Prasarana</a>
                <a href="/admin/admin/layanan/lihat_pesan.php">Pesan Konsultatif</a>
            </div>
        </div>
    </div>

    <div class="content">
        
        <div class="logo-header">
            <h1>Logo</h1>
            <p>The official logos of the Network and Cyber Security Laboratory and its affiliated institutions. Please use them in accordance with our branding guidelines.</p>
        </div>
        
        <?php if (!empty($alert_message)): ?>
        <div class="<?php echo $alert_type; ?> admin-alert">
            <?php echo $alert_message; ?>
        </div>
        <?php endif; ?>  
        
        <div class="form-action-header">
            <button class="btn-primary" onclick="openModal('addModal', 'Tambah Logo Baru')">
                + Tambah Logo
            </button>
            <a href="../index.php" class="btn-secondary">
                Kembali
            </a>
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
                    <button class="btn-warning" onclick="openEditModal(<?php echo $logo['id']; ?>, '<?php echo htmlspecialchars($logo['nama_logo']); ?>', '<?php echo htmlspecialchars($logo['media_path']); ?>')">
                        Edit
                    </button>
                    <a href="<?php echo $base_url; ?>profil/proses/proses_logo.php?action=delete&id=<?php echo $logo['id']; ?>" 
                       class="btn-danger" 
                       onclick="return confirm('Anda yakin ingin menghapus logo <?php echo htmlspecialchars($logo['nama_logo']); ?>?')">
                        Hapus
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="card card-info">
            <div class="card-header">
                <h3>Panduan Upload Logo</h3>
            </div>
            <ul class="guideline-list">
                <li>Format file yang diperbolehkan: PNG, JPG, atau JPEG</li>
                <li>Ukuran maksimal file: 2MB</li>
                <li>Resolusi yang disarankan: 500x500 pixels (rasio 1:1)</li>
                <li>Gunakan logo dengan background transparan untuk hasil terbaik</li>
            </ul>
        </div>
    </div>
           
    
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('addModal')">&times;</span>
            <h3 id="modalTitle">Tambah Logo Baru</h3>
            <form action="<?php echo $base_url; ?>profil/proses/proses_logo.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label for="add_nama">Nama Logo <span class="required">*</span></label>
                    <input type="text" id="add_nama" name="nama_logo" required>
                </div>
                
                <div class="form-group">
                    <label for="add_file">Upload Logo (PNG/JPG, Max 2MB) <span class="required">*</span></label>
                    <input type="file" id="add_file" name="logo_file" 
                           accept="image/png, image/jpeg, image/jpg"
                           required
                           onchange="previewImage(this, 'add_preview')">
                </div>
                
                <div class="form-group">
                    <label>Preview Logo</label>
                    <img id="add_preview" class="preview-img"
                         src="" 
                         alt="Preview Logo">
                </div>

                <button type="submit" class="btn-primary" name="submit">Simpan Logo</button>
            </form>
        </div>
    </div>
    
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('editModal')">&times;</span>
            <h3 id="editModalTitle">Edit Logo</h3>
            <form action="<?php echo $base_url; ?>profil/proses/proses_logo.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_logo" id="edit_id">
                <input type="hidden" name="old_media_path" id="edit_old_path">
                
                <div class="form-group">
                    <label for="edit_nama">Nama Logo <span class="required">*</span></label>
                    <input type="text" id="edit_nama" name="nama_logo" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_file">Upload Logo Baru (PNG/JPG, Max 2MB)</label>
                    <input type="file" id="edit_file" name="logo_file" 
                           accept="image/png, image/jpeg, image/jpg"
                           onchange="previewImage(this, 'edit_preview')">
                    <small class="form-help-text">*Kosongkan jika tidak ingin mengganti logo</small>
                </div>
                
                <div class="form-group">
                    <label>Preview Logo Saat Ini</label>
                    <img id="edit_preview" class="preview-img"
                         src="" 
                         alt="Preview Logo">
                </div>

                <button type="submit" class="btn-primary" name="submit">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script>
        // Fungsi untuk membuka modal
        function openModal(modalId, title = '') {
            document.getElementById(modalId).style.display = 'block';
            const titleEl = document.getElementById(modalId).querySelector('h3');
            if(title && titleEl) {
                 titleEl.innerText = title;
            }
        }

        // Fungsi untuk menutup modal
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        // Fungsi untuk memuat data ke modal edit
        function openEditModal(id, nama, path) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            // Path yang disimpan harus relatif terhadap folder 'uploads' di root
            const relativePath = path.substring(path.indexOf('asset/img/')); 
            document.getElementById('edit_old_path').value = relativePath; 
            document.getElementById('edit_preview').src = path;
            document.getElementById('edit_file').value = ''; 
            document.getElementById('editModalTitle').innerText = 'Edit Logo: ' + nama;
            openModal('editModal');
        }

        // Fungsi untuk preview gambar sebelum upload
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                 // Untuk modal Tambah, kosongkan preview jika file dibatalkan
                if (previewId === 'add_preview') {
                    preview.src = '';
                }
            }
        }

        // Tutup modal jika mengklik di luar modal
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = "none";
            }
        }
    </script>
    <script src="/admin/asset/js/script_admin.js"></script>
</body>
</html>