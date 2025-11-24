<?php 
// File: admin/profil/edit_struktur.php
session_start();

$page_title = "Manajemen Struktur Organisasi";
$current_page = "edit_struktur";
$base_url = '../../';

// Dummy data header
$header_data = [
    'judul' => 'Struktur Organisasi',
    'deskripsi' => 'Meet the dedicated researchers and students of our laboratory.',
];

// Dummy data anggota
$struktur = [
    [
        'id_anggota' => 1,
        'nama' => 'Ben Carter',
        'jabatan' => 'Postdoctoral Researcher',
        'focus' => 'Malware Analysis',
        'foto' => 'asset/img/dosen1.jpg', 
        'urutan' => 1
    ],
    [
        'id_anggota' => 2,
        'nama' => 'Aisha Khan',
        'jabatan' => 'PhD Candidate',
        'focus' => 'Intrusion Detection',
        'foto' => 'asset/img/dosen2.jpg',
        'urutan' => 2
    ],
    [
        'id_anggota' => 3,
        'nama' => 'Carlos Gomez',
        'jabatan' => 'PhD Candidate',
        'focus' => 'Network Traffic Analysis',
        'foto' => 'asset/img/dosen3.jpg',
        'urutan' => 3
    ],
    [
        'id_anggota' => 4,
        'nama' => 'Maria Rodriguez',
        'jabatan' => 'Research Assistant',
        'focus' => 'Data Recovery',
        'foto' => 'asset/img/dosen4.jpg',
        'urutan' => 4
    ]
];

usort($struktur, function($a, $b) {
    return $a['urutan'] <=> $b['urutan'];
});

// Alert messages
$alert_message = '';
$alert_type = '';

if (isset($_GET['success'])) {
    $alert_type = 'alert-success';
    if ($_GET['success'] == 'add') {
        $alert_message = '✓ Anggota tim berhasil ditambahkan!';
    } elseif ($_GET['success'] == 'update') {
        $alert_message = '✓ Data anggota berhasil diperbarui!';
    } elseif ($_GET['success'] == 'delete') {
        $alert_message = '✓ Anggota tim berhasil dihapus!';
    } elseif ($_GET['success'] == 'header') {
        $alert_message = '✓ Judul dan deskripsi berhasil diperbarui!';
    }
} elseif (isset($_GET['error'])) {
    $alert_type = 'alert-error';
    $alert_message = '❌ ' . htmlspecialchars($_GET['error']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Admin NCS Lab</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>asset/css/style_admin.css">
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

    <!-- CONTENT -->
    <div class="content">
        <!-- Header -->
        <div class="admin-header">
            <h1><?php echo $page_title; ?></h1>
        </div>
        
        <!-- Alert -->
        <?php if (!empty($alert_message)): ?>
        <div class="<?php echo $alert_type; ?> admin-alert">
            <?php echo $alert_message; ?>
        </div>
        <?php endif; ?>

        <!-- SECTION 1: Edit Header -->
        <div class="card">
            <div class="card-header">
                <h3>Pengaturan Header Section</h3>
            </div>
            <form action="<?php echo $base_url; ?>profil/proses/proses_struktur.php" method="POST">
                <input type="hidden" name="action" value="update_header">
                
                <div class="form-group">
                    <label for="judul_struktur">
                        Sub Judul <span class="required">*</span>
                    </label>
                    <span class="form-subtitle">Judul section (contoh: "Struktur Organisasi")</span>
                    <input type="text" id="judul_struktur" name="judul_struktur" 
                           value="<?php echo htmlspecialchars($header_data['judul']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="deskripsi_struktur">
                        Deskripsi Sub Judul <span class="required">*</span>
                    </label>
                    <span class="form-subtitle">Deskripsi singkat di bawah judul</span>
                    <textarea id="deskripsi_struktur" name="deskripsi_struktur" rows="2" required><?php echo htmlspecialchars($header_data['deskripsi']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn-primary" name="submit_header">💾 Simpan Header</button>
                </div>
            </form>
        </div>
        
        
        <!-- SECTION 3: Kelola Data Anggota -->
        <div class="card">
            <div class="card-header">
                <h3>Kelola Data Anggota</h3>
            </div>
            
            <div class="table-actions">
                <button class="btn-primary" onclick="openModal('addModal')">
                    + Tambah Anggota Baru
                </button>
                <a href="<?php echo $base_url; ?>index.php" class="btn-secondary">
                    ← Kembali
                </a>
            </div>
            
            <?php if(empty($struktur)): ?>
                <div class="alert-info">
                    Belum ada data anggota tim.
                </div>
            <?php else: ?>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-foto">Foto</th>
                        <th>Nama</th>
                        <th class="col-jabatan">Jabatan</th>
                        <th class="col-focus">Focus</th>
                        <th class="col-urutan">Urutan</th>
                        <th class="col-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($struktur as $anggota): 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td>
                            <img src="<?php echo $base_url . $anggota['foto']; ?>" 
                                 alt="<?php echo htmlspecialchars($anggota['nama']); ?>"
                                 class="table-img">
                        </td>
                        <td><strong><?php echo htmlspecialchars($anggota['nama']); ?></strong></td>
                        <td><?php echo htmlspecialchars($anggota['jabatan']); ?></td>
                        <td><?php echo htmlspecialchars($anggota['focus']); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($anggota['urutan']); ?></td>
                        <td class="action-cell">
                            <button class="btn-warning btn-small btn-action" 
                                    onclick='openEditModal(<?php echo json_encode($anggota); ?>)'> Edit
                            </button>
                            <a href="<?php echo $base_url; ?>profil/proses/proses_struktur.php?action=delete&id=<?php echo $anggota['id_anggota']; ?>" 
                               class="btn-danger btn-small" 
                               onclick="return confirmDelete('<?php echo htmlspecialchars($anggota['nama']); ?>')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php endif; ?>
        </div>
        
        <!-- Info Card -->
        <div class="card card-info">
            <div class="card-header">
                <h3>💡 Petunjuk</h3>
            </div>
            <ul class="guideline-list">
                <li>Klik "Tambah Anggota Baru" untuk menambah anggota tim</li>
                <li>Foto akan ditampilkan dalam bentuk bulat di website</li>
                <li>Urutan menentukan posisi tampilan di website (1 = paling kiri)</li>
                <li>Focus penelitian bersifat opsional</li>
                <li>Gunakan foto dengan resolusi minimal 300x300 px</li>
            </ul>
        </div>
    </div>

    <!-- MODAL ADD -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('addModal')">&times;</span>
            <h3>Tambah Anggota Tim Baru</h3>
            <form action="<?php echo $base_url; ?>profil/proses/proses_struktur.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label for="add_nama">
                        Nama Lengkap <span class="required">*</span>
                    </label>
                    <input type="text" id="add_nama" name="nama" required>
                </div>
                
                <div class="form-group">
                    <label for="add_jabatan">
                        Jabatan <span class="required">*</span>
                    </label>
                    <input type="text" id="add_jabatan" name="jabatan" required>
                </div>
                
                <div class="form-group">
                    <label for="add_focus">Focus Penelitian (Opsional)</label>
                    <input type="text" id="add_focus" name="focus">
                </div>
                
                <div class="form-group">
                    <label for="add_urutan">
                        Urutan Tampil <span class="required">*</span>
                    </label>
                    <input type="number" id="add_urutan" name="urutan" min="1" value="<?php echo count($struktur) + 1; ?>" required>
                </div>

                <div class="form-group">
                    <label for="add_foto">
                        Foto Anggota <span class="required">*</span>
                    </label>
                    <input type="file" id="add_foto" name="foto_anggota" 
                           accept="image/png, image/jpeg, image/jpg"
                           required
                           onchange="previewImage(this, 'add_preview')">
                    <small class="form-help-text">Format: PNG/JPG, Max 2MB, Resolusi minimal 300x300px</small>
                </div>
                
                <div class="form-group">
                    <label>Preview Foto</label>
                    <img id="add_preview" class="preview-img" style="display: none;">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-primary" name="submit">💾 Simpan Anggota</button>
                    <button type="button" class="btn-secondary" onclick="closeModal('addModal')">Batal</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- MODAL EDIT -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('editModal')">&times;</span>
            <h3 id="editModalTitle">Edit Anggota</h3>
            <form action="<?php echo $base_url; ?>profil/proses/proses_struktur.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_anggota" id="edit_id">
                <input type="hidden" name="old_foto_path" id="edit_old_foto_path">
                
                <div class="form-group">
                    <label for="edit_nama">
                        Nama Lengkap <span class="required">*</span>
                    </label>
                    <input type="text" id="edit_nama" name="nama" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_jabatan">
                        Jabatan <span class="required">*</span>
                    </label>
                    <input type="text" id="edit_jabatan" name="jabatan" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_focus">Focus Penelitian (Opsional)</label>
                    <input type="text" id="edit_focus" name="focus">
                </div>
                
                <div class="form-group">
                    <label for="edit_urutan">
                        Urutan Tampil <span class="required">*</span>
                    </label>
                    <input type="number" id="edit_urutan" name="urutan" min="1" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_foto">Foto Anggota Baru (Opsional)</label>
                    <input type="file" id="edit_foto" name="foto_anggota" 
                           accept="image/png, image/jpeg, image/jpg"
                           onchange="previewImage(this, 'edit_preview')">
                    <small class="form-help-text">*Kosongkan jika tidak ingin mengganti foto</small>
                </div>
                
                <div class="form-group">
                    <label>Preview Foto Saat Ini</label>
                    <img id="edit_preview" class="preview-img">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-primary" name="submit">💾 Simpan Perubahan</button>
                    <button type="button" class="btn-secondary" onclick="closeModal('editModal')">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(anggota) {
            const pathPrefix = "<?php echo $base_url; ?>";
            
            document.getElementById('edit_id').value = anggota.id_anggota;
            document.getElementById('edit_nama').value = anggota.nama;
            document.getElementById('edit_jabatan').value = anggota.jabatan;
            document.getElementById('edit_focus').value = anggota.focus;
            document.getElementById('edit_urutan').value = anggota.urutan;
            document.getElementById('edit_old_foto_path').value = anggota.foto;
            document.getElementById('edit_preview').src = pathPrefix + anggota.foto;
            document.getElementById('edit_preview').style.display = 'block';
            document.getElementById('edit_foto').value = '';
            document.getElementById('editModalTitle').innerText = 'Edit Anggota: ' + anggota.nama;
            
            openModal('editModal');
        }
    </script>
    <script src="/admin/asset/js/script_admin.js"></script>
</body>
</html>