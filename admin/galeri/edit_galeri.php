<?php
// File: admin/galeri/edit_galeri.php
session_start();

$pageTitle = 'Kelola Galeri Foto/Video';
$currentPage = 'edit_galeri';
$adminPageStyles = ['tables', 'dashboard'];

include_once '../../config/koneksi.php';
require_once __DIR__ . '/../../app/helpers/galeri_helper.php';
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

require_once dirname(__DIR__) . '/includes/admin_header.php';
?>
<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: galeri)</h1>
    <p>Kelola dan pantau seluruh postingan galeri foto/video pada halaman utama website NCS Lab.</p>
</div>
        <!-- Alert -->
        <?php if (!empty($alert_message)): ?>
        <div class="<?php echo $alert_type; ?> admin-alert">
            <?php echo $alert_message; ?>
        </div>
        <?php endif; ?>
        
        <!-- Statistics -->
        <div class="stats-grid" style="margin-bottom: 24px;">
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
            
        </div>
        
        <!-- Content Box -->
        <div class="card">
            <div class="table-actions">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Cari judul, deskripsi..." 
                           onkeyup="searchTable('searchInput', 'galeriTable')">
                </div>
                <a href="<?php echo $adminBasePath; ?>galeri/tambah_galeri.php" class="btn-primary">
                    + Tambah Galeri Baru
                </a>
            </div>
            
            <?php if(empty($galeri_items)): ?>
                <div class="alert-info">
                    Belum ada data galeri. Silakan tambah data baru.
                </div>
            <?php else: ?>
            
            <table id="galeriTable" class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Media</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Aksi</th>
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
                                <?php $imageSrc = $projectBasePath . $item['media_path']; ?>
                                <img src="<?php echo htmlspecialchars($imageSrc); ?>" 
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
                            <a href="<?php echo $adminBasePath; ?>galeri/edit_galeri_form.php?id=<?php echo $item['id_galeri']; ?>" 
                               class="btn-warning btn-small btn-action">
                                ✏️ Edit
                            </a>
                            <a href="<?php echo $adminBasePath; ?>proses/proses_galeri.php?action=delete&id=<?php echo $item['id_galeri']; ?>" 
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
                        <?php $imageSrc = $projectBasePath . $item['media_path']; ?>
                        <img src="<?php echo htmlspecialchars($imageSrc); ?>" 
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
        
    </div>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>