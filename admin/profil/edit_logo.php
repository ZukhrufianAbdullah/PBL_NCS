<?php 
// File: admin/profil/edit_logo.php (Lokasi TETAP: admin/profil/edit_logo.php)
session_start();
$page_title = "Edit Logo Website";
$current_page = "edit_logo";

$base_Url = '..'; 
//$base_Url = '../admin'; 
$assetUrl = '/PBL_NCS/assets/admin';
$base_url = '/PBL_NCS';

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

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo $assetUrl; ?>/css/admin-dashboard.css">
</head>
<body>

    <div class="sidebar">
        <h2>ADMIN NCS LAB</h2>
        <a href="index.php">Dashboard</a> 
        
        <div class="menu-header">PENGATURAN TAMPILAN</div>
        <a href="<?php echo $base_Url; ?>/setting/edit_header.php">Edit Header</a>
        <a href="<?php echo $base_Url; ?>/setting/edit_footer.php">Edit Footer</a>
        <a href="<?php echo $base_Url; ?>/beranda/edit_beranda.php">Edit Beranda</a>
        <a href="<?php echo $base_Url; ?>/beranda/edit_banner.php">Edit Banner</a>

        <div class="menu-header">MANAJEMEN KONTEN</div>
        
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('manajemenKonten')">
                PROFIL
                <span class="dropdown-icon" id="icon-manajemenKonten">></span>
            </a>
            <div class="submenu-wrapper" id="manajemenKonten">
                <a href="<?php echo $base_Url;?>/profil/edit_visi_misi.php">Visi & Misi</a>
                <a href="<?php echo $base_Url;?>/profil/edit_struktur.php">Struktur Organisasi</a>
                <a href="<?php echo $base_Url;?>/profil/edit_logo.php">Edit Logo</a>
            </div>
        </div>
        
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('galeriMenu')">
                GALERI
                <span class="dropdown-icon" id="icon-galeriMenu">></span>
            </a>
            <div class="submenu-wrapper" id="galeriMenu">
                <div class="menu-subheader">GALERI FOTO/VIDEO</div>
                <a href="<?php echo $base_Url;?>/galeri/tambah_galeri.php">Tambah Galeri</a>
                <a href="<?php echo $base_Url;?>/galeri/edit_galeri.php">Kelola Galeri</a>
                <div class="menu-subheader">AGENDA</div>
                <a href="<?php echo $base_Url;?>/galeri/tambah_agenda.php">Tambah Agenda</a>
                <a href="<?php echo $base_Url;?>/galeri/edit_agenda.php">Kelola Agenda</a>
            </div>
        </div>
        
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('arsipMenu')">
                ARSIP
                <span class="dropdown-icon" id="icon-arsipMenu">></span>
            </a>
            <div class="submenu-wrapper" id="arsipMenu">
                <div class="menu-subheader">PENELITIAN</div>
                <a href="<?php echo $base_Url;?>/arsip/tambah_penelitian.php">Tambah Penelitian</a>
                <a href="<?php echo $base_Url;?>/arsip/edit_penelitian.php">Kelola Penelitian</a>
                <div class="menu-subheader">PENGABDIAN</div>
                <a href="<?php echo $base_Url;?>/arsip/tambah_pengabdian.php">Tambah Pengabdian</a>
                <a href="<?php echo $base_Url;?>/arsip/edit_pengabdian.php">Kelola Pengabdian</a>
            </div>
        </div>

        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('layananMenu')">
                LAYANAN
                <span class="dropdown-icon" id="icon-layananMenu">></span>
            </a>
            <div class="submenu-wrapper" id="layananMenu">
                <a href="<?php echo $base_Url;?>/layanan/edit_sarana_prasarana.php">Sarana & Prasarana</a>
                <a href="<?php echo $base_Url;?>/layanan/lihat_pesan.php">Pesan Konsultatif</a>
            </div>
        </div>
    </div>

    
    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?> (Tabel: logo)</h1>
                <p>Gunakan form ini untuk mengubah logo utama website (Profil/logo).</p>
        </div>

        <form method="post" action="../proses/proses_logo.php" enctype="multipart/form-data">    

                <fieldset>
                <legend>Pengaturan Judul Utama</legend>
                
                <div class="form-group">
                    <label for="judul_sub">Sub Judul <span style="color: red;">*</span></label>
                    <span class="form-subtitle">Contoh: Visi & Misi</span>
                    <input type="text" id="judul_sub" name="judul_sub" value="<?php echo htmlspecialchars($data['judul_sub']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="deskripsi_sub">Deskripsi Sub Judul <span style="color: red;">*</span></label>
                    <span class="form-subtitle">Teks di bawah sub judul utama. Contoh: Our guiding principles...</span>
                    <textarea id="deskripsi_sub" name="deskripsi_sub" rows="3" required><?php echo htmlspecialchars($data['deskripsi_sub']); ?></textarea>
                </div>
            </fieldset>

            <fieldset style="border: 1px solid #ccc; padding: 20px;">
                <legend style="font-size: 1.2em; font-weight: bold; color: var(--primary-color);">Manajemen Logo Utama</legend>
            </fieldset>

<div class="logo-grid">
    <?php foreach ($logos as $logo): ?>
    <div class="logo-card">
        <div class="logo-container">
            <img src="<?php echo htmlspecialchars($logo['media_path']); ?>"
                 alt="<?php echo htmlspecialchars($logo['nama_logo']); ?>">
        </div>

        <p class="logo-title"><?php echo htmlspecialchars($logo['nama_logo']); ?></p>

        <div class="card-actions">
        <button type="button" class="btn-warning" 
        onclick="openEditModal(<?php echo $logo['id']; ?>,
                               '<?php echo $logo['nama_logo']; ?>',
                               '<?php echo $logo['media_path']; ?>')
                               return false;">
         Edit
        </button>


            <a href="..." class="btn-danger">Hapus</a>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
            <div class="form-group" style="margin-top: 25px;">
                <input type="submit" class="btn-primary" value="Unggah & Simpan Logo">
            </div>
        </form>

    </div>
        <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('editModal')">&times;</span>
            <h3 id="editModalTitle">Edit Logo</h3>
            <form action="<?php echo $base_url; ?>profil/proses/proses_logo.php"method="POST" enctype="multipart/form-data">
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
    <script src="<?php echo $assetUrl; ?>/js/admin-dashboard.js"></script>
</body>
</html>