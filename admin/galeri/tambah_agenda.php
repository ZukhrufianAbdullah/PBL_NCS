<?php 
// File: admin/galeri/tambah_agenda.php
session_start();

$page_title = "Tambah Agenda";
$current_page = "tambah_agenda";
$base_url = '/admin/admin/';

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
        
        <form id="formAgenda" method="POST" onsubmit="return validateForm('formAgenda')">
            
            <fieldset>
                <legend>Informasi Agenda</legend>
                
                <div class="form-group">
                    <label for="judul_agenda">Judul Agenda <span style="color: red;">*</span></label>
                    <input type="text" id="judul_agenda" name="judul_agenda" required
                           placeholder="Contoh: Workshop Keamanan Jaringan 2024">
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi <span style="color: red;">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="6" required
                              placeholder="Masukkan deskripsi lengkap agenda kegiatan..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="tanggal_agenda">Tanggal Agenda <span style="color: red;">*</span></label>
                    <input type="date" id="tanggal_agenda" name="tanggal_agenda" required>
                </div>
                
                <div class="form-group">
                    <label for="waktu_mulai">Waktu Mulai</label>
                    <input type="time" id="waktu_mulai" name="waktu_mulai">
                </div>
                
                <div class="form-group">
                    <label for="waktu_selesai">Waktu Selesai</label>
                    <input type="time" id="waktu_selesai" name="waktu_selesai">
                </div>
                
                <div class="form-group">
                    <label for="tempat">Tempat/Lokasi</label>
                    <input type="text" id="tempat" name="tempat" 
                           placeholder="Contoh: Ruang Lab NCS, Gedung TI Lantai 3">
                </div>
            </fieldset>
            
            <fieldset>
                <legend>Status & Publikasi</legend>
                
                <div class="form-group">
                    <label for="status">Status Agenda <span style="color: red;">*</span></label>
                    <select id="status" name="status" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="1">Aktif (Tampilkan di website)</option>
                        <option value="0">Tidak Aktif (Sembunyikan)</option>
                    </select>
                    <small style="color: #666; display: block; margin-top: 5px;">
                        Status Aktif akan menampilkan agenda di halaman utama website
                    </small>
                </div>
                
                <div class="form-group">
                    <label for="kategori">Kategori Agenda</label>
                    <select id="kategori" name="kategori">
                        <option value="workshop">Workshop</option>
                        <option value="seminar">Seminar</option>
                        <option value="pelatihan">Pelatihan</option>
                        <option value="webinar">Webinar</option>
                        <option value="penelitian">Kegiatan Penelitian</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
            </fieldset>
            
            <fieldset>
                <legend>Informasi Tambahan (Opsional)</legend>
                
                <div class="form-group">
                    <label for="narasumber">Narasumber/Pembicara</label>
                    <input type="text" id="narasumber" name="narasumber" 
                           placeholder="Nama narasumber (jika ada)">
                </div>
                
                <div class="form-group">
                    <label for="kapasitas">Kapasitas Peserta</label>
                    <input type="number" id="kapasitas" name="kapasitas" 
                           placeholder="Maksimal peserta">
                </div>
                
                <div class="form-group">
                    <label for="link_pendaftaran">Link Pendaftaran</label>
                    <input type="url" id="link_pendaftaran" name="link_pendaftaran" 
                           placeholder="https://...">
                </div>
            </fieldset>
            
            <div class="form-group" style="padding: 0 20px;">
                <button type="submit" class="btn-primary" name="submit">
                    Simpan Agenda
                </button>
                <a href="edit_agenda.php" class="btn-secondary" 
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
                <li>Judul agenda harus jelas dan deskriptif</li>
                <li>Tanggal agenda menentukan urutan tampilan di website</li>
                <li>Status Aktif akan menampilkan agenda di halaman utama</li>
                <li>Agenda yang sudah lewat tanggalnya akan otomatis disembunyikan</li>
            </ul>
        </div>
    </div>

   <script src="/admin/asset/js/script_admin.js"></script>
</body>
</html>