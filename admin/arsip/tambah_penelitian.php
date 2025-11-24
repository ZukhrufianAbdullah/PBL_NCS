<?php 
// File: admin/arsip/tambah_penelitian.php
session_start();

$page_title = "Tambah Penelitian";
$current_page = "tambah_penelitian";
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

    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?></h1>
        </div>
        
        <form id="formPenelitian" method="POST" enctype="multipart/form-data" 
              onsubmit="return validateForm('formPenelitian')">
            
            <fieldset>
                <legend>Informasi Penelitian</legend>
                
                <div class="form-group">
                    <label for="judul_penelitian">Judul Penelitian <span style="color: red;">*</span></label>
                    <input type="text" id="judul_penelitian" name="judul_penelitian" required
                           placeholder="Masukkan judul penelitian lengkap">
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi/Abstrak <span style="color: red;">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="8" required
                              placeholder="Masukkan deskripsi atau abstrak penelitian..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="tahun">Tahun Penelitian <span style="color: red;">*</span></label>
                    <input type="number" id="tahun" name="tahun" required 
                           min="2000" max="2030" 
                           placeholder="<?php echo date('Y'); ?>">
                </div>
            </fieldset>
            
            <fieldset>
                <legend>Peneliti/Author</legend>
                
                <div class="form-group">
                    <label for="id_author">Ketua Peneliti <span style="color: red;">*</span></label>
                    <select id="id_author" name="id_author" required>
                        <option value="">-- Pilih Ketua Peneliti --</option>
                        <option value="1">Dr. Ahmad Fauzi, M.Kom</option>
                        <option value="2">Siti Nurhaliza, M.T</option>
                        <option value="3">Budi Santoso, M.Kom</option>
                    </select>
                    <small style="color: #666; display: block; margin-top: 5px;">
                        Data diambil dari tabel dosen
                    </small>
                </div>
                
                <div class="form-group">
                    <label for="anggota_peneliti">Anggota Peneliti (Opsional)</label>
                    <textarea id="anggota_peneliti" name="anggota_peneliti" rows="3"
                              placeholder="Tuliskan nama anggota peneliti lainnya (pisahkan dengan koma)"></textarea>
                </div>
            </fieldset>
            
            <fieldset>
                <legend>Dokumen Penelitian</legend>
                
                <div class="form-group">
                    <label for="file_penelitian">Upload Dokumen (PDF, Max 10MB)</label>
                    <input type="file" id="file_penelitian" name="file_penelitian" 
                           accept=".pdf">
                    <small style="color: #666; display: block; margin-top: 5px;">
                        Upload file laporan penelitian (PDF). Opsional.
                    </small>
                </div>
                
                <div class="form-group">
                    <label for="link_publikasi">Link Publikasi (Opsional)</label>
                    <input type="url" id="link_publikasi" name="link_publikasi" 
                           placeholder="https://...">
                    <small style="color: #666; display: block; margin-top: 5px;">
                        Link ke jurnal atau publikasi online (jika ada)
                    </small>
                </div>
            </fieldset>
            
            <fieldset>
                <legend>Kategori & Status</legend>
                
                <div class="form-group">
                    <label for="kategori">Kategori Penelitian</label>
                    <select id="kategori" name="kategori">
                        <option value="fundamental">Penelitian Fundamental</option>
                        <option value="terapan">Penelitian Terapan</option>
                        <option value="pengembangan">Penelitian Pengembangan</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="sumber_dana">Sumber Dana</label>
                    <input type="text" id="sumber_dana" name="sumber_dana" 
                           placeholder="Contoh: DIKTI, Internal Politeknik, dll">
                </div>
                
                <div class="form-group">
                    <label for="status_penelitian">Status Penelitian</label>
                    <select id="status_penelitian" name="status_penelitian">
                        <option value="selesai">Selesai</option>
                        <option value="berjalan">Sedang Berjalan</option>
                        <option value="proposal">Tahap Proposal</option>
                    </select>
                </div>
            </fieldset>
            
            <div class="form-group" style="padding: 0 20px;">
                <button type="submit" class="btn-primary" name="submit">
                    Simpan Penelitian
                </button>
                <a href="edit_penelitian.php" class="btn-secondary" 
                   style="margin-left: 10px; text-decoration: none; display: inline-block;">
                    Batal
                </a>
            </div>
            
        </form>
        
        <div class="card" style="margin-top: 30px;">
            <div class="card-header">
                <h3>Petunjuk Pengisian</h3>
            </div>
            <ul style="line-height: 1.8; color: #555; padding-left: 20px;">
                <li>Field yang bertanda <span style="color: red;">*</span> wajib diisi</li>
                <li>Judul penelitian harus lengkap dan deskriptif</li>
                <li>Deskripsi/abstrak menjelaskan ringkasan penelitian</li>
                <li>Ketua peneliti dipilih dari data dosen yang sudah terdaftar</li>
                <li>Dokumen PDF akan disimpan dan dapat diunduh dari website</li>
            </ul>
        </div>
    </div>

    <script>
    const CURRENT_MENU_GROUP = '<?php echo $current_menu_group; ?>';
    </script>
    <script src="/admin/asset/js/script_admin.js"></script>
</body>
</html>