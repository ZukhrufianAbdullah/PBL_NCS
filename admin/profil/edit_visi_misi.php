<?php 
// File: admin/profil/edit_visi_misi.php
session_start();

$page_title = "Edit Visi & Misi";
$current_page = "edit_visi_misi";
$base_url = '../../';
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
        
        <a href="../index.php">Dashboard</a>

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
        <div class="admin-header">
            <h1><?php echo $page_title; ?></h1>
        </div>
        
        <?php if(isset($_GET['success'])): ?>
        <div class="alert-success">
            Visi dan Misi berhasil diperbarui!
        </div>
        <?php endif; ?>
        
        <form id="formVisiMisi" method="POST">
            
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
            
            <fieldset>
                <legend>Detail Visi & Misi</legend>
                <div class="form-group">
                    <label for="visi">Isi Visi (Kolom: visi)</label>
                    <textarea id="visi" name="visi" rows="8">Masukkan teks Visi di sini...</textarea>
                </div>
                <div class="form-group">
                    <label for="misi">Isi Misi (Kolom: misi)</label>
                    <textarea id="misi" name="misi" rows="10">Masukkan teks Misi di sini...</textarea>
                </div>
            </fieldset>

            <div class="form-group" style="margin-top: 25px;">
                <input type="submit" class="btn-primary" value="Simpan Perubahan">
            </div>
        </form>

    <script src="/admin/asset/js/script_admin.js"></script>

</body>
</html>