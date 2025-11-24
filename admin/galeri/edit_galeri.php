<?php
// File: admin/galeri/edit_galeri.php
session_start();

$page_title = "Kelola Galeri Foto/Video";
$current_page = "edit_galeri";
$base_url = '/admin/admin/';


// Dummy data galeri
$galeri_items = [
    [
        'id_galeri' => 1,
        'judul' => 'Advanced Network Security Training',
        'media_path' => 'uploads/galeri/workshop1.jpg',
        'jenis_media' => 'foto',
        'deskripsi' => 'Pelatihan mendalam tentang teknik keamanan jaringan terbaru untuk mahasiswa tingkat akhir.',
        'tanggal_kegiatan' => '2024-06-15',
        'status' => 1
    ],
    [
        'id_galeri' => 2,
        'judul' => 'Expert Action: Research Cryptography',
        'media_path' => 'uploads/galeri/seminar1.jpg',
        'jenis_media' => 'foto',
        'deskripsi' => 'Seminar yang membahas aplikasi dan riset terkini dalam kriptografi pasca-kuantum.',
        'tanggal_kegiatan' => '2024-07-20',
        'status' => 1
    ],
    [
        'id_galeri' => 3,
        'judul' => 'Public Safety: Cyber Security Workshop',
        'media_path' => 'uploads/galeri/publicsafety.jpg',
        'jenis_media' => 'foto',
        'deskripsi' => 'Kegiatan pengabdian masyarakat untuk meningkatkan kesadaran keamanan siber dasar.',
        'tanggal_kegiatan' => '2024-08-01',
        'status' => 1
    ],
];

// Alert
$alert_message = '';
$alert_type = '';

if (isset($_GET['success'])) {
    $alert_type = 'alert-success';
    if ($_GET['success'] == 'add') {
        $alert_message = '✓ Galeri berhasil ditambahkan!';
    } elseif ($_GET['success'] == 'update') {
        $alert_message = '✓ Galeri berhasil diperbarui!';
    } elseif ($_GET['success'] == 'delete') {
        $alert_message = '✓ Galeri berhasil dihapus!';
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
    <link rel="stylesheet" href="/admin/asset/css/style_admin.css">
</head>
<body>

    <!-- SIDEBAR -->
       <div class="sidebar">
        <h2>ADMIN NCS LAB</h2>
        
        <a href="index.php">Dashboard</a>
        
        <a href="/admin/admin/beranda/edit_beranda.php">Edit Beranda</a>
        
        <div class="menu-header">PENGATURAN TAMPILAN</div>
        <a href="/admin/include/edit_header.php">Edit Header</a>
        <a href="/admin/include/edit_footer.php">Edit Footer</a>

        <div class="menu-header">MANAJEMEN KONTEN</div>
        
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('manajemenKonten')">
                PROFIL
                <span class="dropdown-icon" id="icon-manajemenKonten"></span>
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
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Galeri</h3>
                <p class="stat-number"><?php echo count($galeri_items); ?></p>
                <small>Foto & Video</small>
            </div>
            
            <div class="stat-card">
                <h3>Galeri Aktif</h3>
                <p class="stat-number">
                    <?php 
                    $aktif = array_filter($galeri_items, function($g) { return $g['status'] == 1; });
                    echo count($aktif);
                    ?>
                </p>
                <small>Ditampilkan di Website</small>
            </div>
            
            <div class="stat-card">
                <h3>Foto</h3>
                <p class="stat-number">
                    <?php 
                    $foto = array_filter($galeri_items, function($g) { return $g['jenis_media'] == 'foto'; });
                    echo count($foto);
                    ?>
                </p>
                <small>Galeri Foto</small>
            </div>
            
            <div class="stat-card">
                <h3>Video</h3>
                <p class="stat-number">
                    <?php 
                    $video = array_filter($galeri_items, function($g) { return $g['jenis_media'] == 'video'; });
                    echo count($video);
                    ?>
                </p>
                <small>Galeri Video</small>
            </div>
        </div>
        
        <!-- Content Box -->
        <div class="card">
            <div class="table-actions">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Cari judul, deskripsi..." 
                           onkeyup="searchTable('searchInput', 'galeriTable')">
                </div>
                <a href="tambah_galeri.php" class="btn-primary">
                    + Tambah Galeri Baru
                </a>
            </div>
            
            <?php if(empty($galeri_items)): ?>
                <div class="alert-info">
                    Belum ada data galeri. Silakan tambah data baru.
                </div>
            <?php else: ?>
            
            <table id="galeriTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 150px;">Media</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th style="width: 120px;">Tanggal</th>
                        <th style="width: 80px;">Jenis</th>
                        <th style="width: 80px;">Status</th>
                        <th style="width: 180px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($galeri_items as $item): 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td>
                            <?php if($item['jenis_media'] == 'foto'): ?>
                                <img src="<?php echo $base_url . $item['media_path']; ?>" 
                                     alt="<?php echo htmlspecialchars($item['judul']); ?>"
                                     class="table-img">
                            <?php else: ?>
                                <div style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; background: #f0f0f0; border-radius: 8px;">
                                    <span style="font-size: 24px;">🎥</span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?php echo htmlspecialchars($item['judul']); ?></strong></td>
                        <td><?php echo substr(htmlspecialchars($item['deskripsi']), 0, 80) . '...'; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($item['tanggal_kegiatan'])); ?></td>
                        <td>
                            <span class="badge <?php echo $item['jenis_media'] == 'foto' ? 'badge-info' : 'badge-warning'; ?>">
                                <?php echo strtoupper($item['jenis_media']); ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php if($item['status'] == 1): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="action-cell">
                            <a href="edit_galeri_form.php?id=<?php echo $item['id_galeri']; ?>" 
                               class="btn-warning btn-small btn-action">
                                ✏️ Edit
                            </a>
                            <a href="<?php echo $base_url; ?>galeri/proses/proses_galeri.php?action=delete&id=<?php echo $item['id_galeri']; ?>" 
                               class="btn-danger btn-small" 
                               onclick="return confirmDelete('<?php echo htmlspecialchars($item['judul']); ?>')">
                                🗑️ Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php endif; ?>
        </div>
        
        <!-- Preview Card (Figma Style) -->
        <div class="card card-preview">
            <div class="card-header">
                <h3>👁️ Preview Tampilan Website (Grid 3 Kolom)</h3>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; margin-top: 20px;">
                <?php foreach ($galeri_items as $item): ?>
                <div class="anggota-card" style="border-top: 3px solid #FCD917;">
                    <?php if($item['jenis_media'] == 'foto'): ?>
                        <img src="<?php echo $base_url . $item['media_path']; ?>" 
                             alt="<?php echo htmlspecialchars($item['judul']); ?>" 
                             style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 15px;">
                    <?php else: ?>
                        <div style="width: 100%; height: 200px; display: flex; align-items: center; justify-content: center; background: #f0f0f0; border-radius: 8px; margin-bottom: 15px;">
                            <span style="font-size: 48px;">🎥</span>
                        </div>
                    <?php endif; ?>
                    <p style="color: #666; font-size: 12px; margin: 0 0 5px 0;">
                        <?php echo date('M d, Y', strtotime($item['tanggal_kegiatan'])); ?>
                    </p>
                    <h4 style="margin: 0 0 10px 0; color: #060771; font-size: 16px;">
                        <?php echo htmlspecialchars($item['judul']); ?>
                    </h4>
                    <p style="color: #666; font-size: 14px; line-height: 1.6;">
                        <?php echo substr(htmlspecialchars($item['deskripsi']), 0, 100) . '...'; ?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Info Card -->
        <div class="card card-info">
            <div class="card-header">
                <h3>💡 Informasi</h3>
            </div>
            <ul class="guideline-list">
                <li>Galeri dengan status <strong>Aktif</strong> akan ditampilkan di website</li>
                <li>Gunakan foto dengan resolusi minimal 800x600 px untuk hasil terbaik</li>
                <li>Video harus berupa URL YouTube yang valid</li>
                <li>Deskripsi maksimal 500 karakter</li>
                <li>Galeri ditampilkan dalam grid 3 kolom di website</li>
            </ul>
        </div>
    </div>

    <script src="/admin/asset/js/script_admin.js"></script>
</body>
</html>