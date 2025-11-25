<?php 
// File: admin/arsip/edit_pengabdian.php
session_start();

$page_title = "Kelola Pengabdian Masyarakat";
$current_page = "edit_pengabdian";
$current_menu_group = "arsipMenu"; // Tambahan untuk membuka grup menu aktif

$base_Url = '..'; 
//$base_Url = '../admin'; 
$assetUrl = '/PBL_NCS/assets/admin';

// Dummy data
$data_pengabdian = [
    [
        'id_pengabdian' => 1,
        'judul_pengabdian' => 'Pelatihan Keamanan Siber untuk UMKM',
        'skema' => 'PKM',
        'tahun' => 2024,
        'ketua' => 'Dr. Ahmad Fauzi, M.Kom'
    ],
    [
        'id_pengabdian' => 2,
        'judul_pengabdian' => 'Sosialisasi Keamanan Jaringan di Era Industri 4.0',
        'skema' => 'PPM',
        'tahun' => 2023,
        'ketua' => 'Budi Santoso, S.T., M.Cs.'
    ],
    [
        'id_pengabdian' => 3,
        'judul_pengabdian' => 'Pengembangan Aplikasi Sistem Informasi Desa',
        'skema' => 'HIB',
        'tahun' => 2024,
        'ketua' => 'Siti Aisyah, M.Kom.'
    ]
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo $assetUrl; ?>/css/admin-dashboard.css">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;507;700&display=swap" rel="stylesheet">
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
            <h1><?php echo $page_title; ?></h1>
        </div>
        
        <div class="card content-box">
            
            <div class="toolbar-top">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Cari Judul, Ketua, atau Skema..." 
                           onkeyup="searchTable('searchInput', 'pengabdianTable')">
                </div>
                <a href="tambah_pengabdian.php" class="btn-primary">
                    + Tambah Pengabdian Baru
                </a>
            </div>
            
            <table id="pengabdianTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Judul Pengabdian</th>
                        <th style="width: 200px;">Ketua Tim</th>
                        <th style="width: 100px;">Skema</th>
                        <th style="width: 80px;">Tahun</th>
                        <th style="width: 150px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($data_pengabdian as $pengabdian): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $pengabdian['judul_pengabdian']; ?></td>
                        <td><?php echo $pengabdian['ketua']; ?></td>
                        <td><span class="badge badge-info"><?php echo $pengabdian['skema']; ?></span></td>
                        <td><?php echo $pengabdian['tahun']; ?></td>
                        <td class="action-column">
                            <a href="edit_pengabdian_form.php?id=<?php echo $pengabdian['id_pengabdian']; ?>" 
                               class="btn-warning btn-action">
                                Edit
                            </a>
                            <button onclick="return confirmDelete('<?php echo $pengabdian['judul_pengabdian']; ?>')" 
                                    class="btn-danger btn-action">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const CURRENT_MENU_GROUP = '<?php echo $current_menu_group; ?>';
    </script>
    <script src="<?php echo $assetUrl; ?>/js/admin-dashboard.js"></script>
</body>
</html>