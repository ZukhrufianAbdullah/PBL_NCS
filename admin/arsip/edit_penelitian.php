<?php 
// File: admin/arsip/edit_penelitian.php
session_start();

$page_title = "Kelola Penelitian";
$current_page = "edit_penelitian";
$base_url = '../';

// Dummy data
$data_penelitian = [
    [
        'id_penelitian' => 1,
        'judul_penelitian' => 'Analisis Keamanan Jaringan Menggunakan Machine Learning',
        'tahun' => 2024,
        'author' => 'Dr. Ahmad Fauzi, M.Kom',
        'status' => 'Selesai'
    ],
    [
        'id_penelitian' => 2,
        'judul_penelitian' => 'Implementasi Blockchain untuk Keamanan Data',
        'tahun' => 2024,
        'author' => 'Siti Nurhaliza, M.T',
        'status' => 'Berjalan'
    ]
];
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
        
        <a href="admin/index.php">Dashboard</a>
        
        <a href="/admin/admin/beranda/edit_beranda.php">Edit Beranda</a>
        
        <div class="menu-header">PENGATURAN TAMPILAN</div>
        <a href="/admin/include/edit_header.php">Edit Header</a>
        <a href="/admin/include/edit_footer.php">Edit Footer</a>

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
        <div class="admin-header">
            <h1><?php echo $page_title; ?></h1>
        </div>
        
        <?php if(isset($_GET['success'])): ?>
        <div class="alert-success">
            Penelitian berhasil <?php echo $_GET['success'] == 'delete' ? 'dihapus' : 'diperbarui'; ?>!
        </div>
        <?php endif; ?>
        
        <div style="background: white; padding: 20px; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Cari penelitian..." 
                           onkeyup="searchTable('searchInput', 'penelitianTable')">
                </div>
                <a href="tambah_penelitian.php" class="btn-primary">
                    + Tambah Penelitian Baru
                </a>
            </div>
            
            <table id="penelitianTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Judul Penelitian</th>
                        <th style="width: 200px;">Peneliti</th>
                        <th style="width: 80px;">Tahun</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 150px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($data_penelitian as $penelitian): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo $penelitian['judul_penelitian']; ?></strong></td>
                        <td><?php echo $penelitian['author']; ?></td>
                        <td><?php echo $penelitian['tahun']; ?></td>
                        <td>
                            <span class="badge badge-<?php echo $penelitian['status'] == 'Selesai' ? 'success' : 'warning'; ?>">
                                <?php echo $penelitian['status']; ?>
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <a href="edit_penelitian_form.php?id=<?php echo $penelitian['id_penelitian']; ?>" 
                               class="btn-warning" style="margin-right: 5px;">
                                Edit
                            </a>
                            <button onclick="return confirmDelete('<?php echo $penelitian['judul_penelitian']; ?>')" 
                                    class="btn-danger">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="/admin/asset/js/script_admin.js"></script>
</body>
</html>