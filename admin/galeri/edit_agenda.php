<?php 
// File: admin/galeri/edit_agenda.php
session_start();

$page_title = "Kelola Agenda";
$current_page = "edit_agenda";

$base_Url = '..'; 
//$base_Url = '../admin'; 
$assetUrl = '/PBL_NCS/assets/admin';

// Dummy data (nanti dari database: tabel agenda)
$data_agenda = [
    [
        'id_agenda' => 1,
        'judul_agenda' => 'Workshop Cyber Security 2024',
        'deskripsi' => 'Workshop tentang keamanan siber dan penetration testing',
        'tanggal_agenda' => '2024-12-15',
        'status' => 1,
        'kategori' => 'Workshop'
    ],
    [
        'id_agenda' => 2,
        'judul_agenda' => 'Seminar Network Security',
        'deskripsi' => 'Seminar nasional tentang keamanan jaringan komputer',
        'tanggal_agenda' => '2024-12-20',
        'status' => 1,
        'kategori' => 'Seminar'
    ],
    [
        'id_agenda' => 3,
        'judul_agenda' => 'Pelatihan Ethical Hacking',
        'deskripsi' => 'Pelatihan dasar ethical hacking untuk mahasiswa',
        'tanggal_agenda' => '2024-11-10',
        'status' => 0,
        'kategori' => 'Pelatihan'
    ]
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo $assetUrl; ?>/css/admin-dashboard.css">\

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
        
        <?php if(isset($_GET['success'])): ?>
        <div class="alert-success">
            <?php 
            if($_GET['success'] == 'delete') {
                echo 'Agenda berhasil dihapus!';
            } else {
                echo 'Data berhasil diperbarui!';
            }
            ?>
        </div>
        <?php endif; ?>
        
        <div style="background: white; padding: 20px; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px;">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Cari agenda..." 
                           onkeyup="searchTable('searchInput', 'agendaTable')"
                           style="max-width: 300px;">
                </div>
                <a href="tambah_agenda.php" class="btn-primary">
                    + Tambah Agenda Baru
                </a>
            </div>
            
            <?php if(empty($data_agenda)): ?>
                <div class="alert-info">
                    Belum ada data agenda. Silakan tambah data baru.
                </div>
            <?php else: ?>
            
            <table id="agendaTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Judul Agenda</th>
                        <th>Deskripsi</th>
                        <th style="width: 120px;">Tanggal</th>
                        <th style="width: 100px;">Kategori</th>
                        <th style="width: 80px;">Status</th>
                        <th style="width: 150px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($data_agenda as $agenda): 
                        // Cek apakah agenda sudah lewat
                        $tanggal = strtotime($agenda['tanggal_agenda']);
                        $sekarang = time();
                        $sudah_lewat = $tanggal < $sekarang;
                    ?>
                    <tr style="<?php echo $sudah_lewat ? 'opacity: 0.6;' : ''; ?>">
                        <td><?php echo $no++; ?></td>
                        <td>
                            <strong><?php echo $agenda['judul_agenda']; ?></strong>
                            <?php if($sudah_lewat): ?>
                                <br><small style="color: #dc3545;">Sudah Berlalu</small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo substr($agenda['deskripsi'], 0, 60) . '...'; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($agenda['tanggal_agenda'])); ?></td>
                        <td>
                            <span class="badge badge-info">
                                <?php echo $agenda['kategori']; ?>
                            </span>
                        </td>
                        <td>
                            <?php if($agenda['status'] == 1): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <a href="edit_agenda_form.php?id=<?php echo $agenda['id_agenda']; ?>" 
                               class="btn-warning" style="margin-right: 5px;">
                                Edit
                            </a>
                            <button onclick="return confirmDelete('<?php echo $agenda['judul_agenda']; ?>')" 
                                    class="btn-danger">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php endif; ?>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3>Informasi</h3>
            </div>
            <ul style="line-height: 1.8; color: #555; padding-left: 20px;">
                <li>Agenda dengan status <strong>Aktif</strong> akan ditampilkan di website</li>
                <li>Agenda yang sudah lewat tanggalnya akan ditandai dengan warna redup</li>
                <li>Gunakan tombol "Edit" untuk mengubah data agenda</li>
                <li>Gunakan tombol "Hapus" untuk menghapus agenda</li>
                <li>Agenda ditampilkan berurutan dari tanggal terdekat</li>
            </ul>
        </div>
    </div>

    <script src="<?php echo $assetUrl; ?>/js/admin-dashboard.js"></script>
</body>
</html>