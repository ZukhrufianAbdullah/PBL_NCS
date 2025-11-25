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

            <div class="form-group" style="margin-top: 25px;">
                <input type="submit" class="btn-primary" value="Unggah & Simpan Logo">
            </div>
        </form>

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

    <script src="<?php echo $assetUrl; ?>/js/admin-dashboard.js"></script>
</body>
</html>