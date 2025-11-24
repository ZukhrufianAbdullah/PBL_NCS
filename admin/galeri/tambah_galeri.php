<?php 
// File: admin/galeri/tambah_galeri.php
session_start();

$page_title = "Tambah Galeri Foto/Video";
$current_page = "tambah_galeri";
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
        
        <form id="formGaleri" method="POST" enctype="multipart/form-data" 
              onsubmit="return validateForm('formGaleri')">
            
            <fieldset>
                <legend>Informasi Galeri</legend>
                
                <div class="form-group">
                    <label for="judul">Judul Galeri <span style="color: red;">*</span></label>
                    <input type="text" id="judul" name="judul" required
                           placeholder="Contoh: Workshop Cyber Security 2024">
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi <span style="color: red;">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" required
                              placeholder="Masukkan deskripsi kegiatan..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="tanggal_kegiatan">Tanggal Kegiatan <span style="color: red;">*</span></label>
                    <input type="date" id="tanggal_kegiatan" name="tanggal_kegiatan" required>
                </div>
            </fieldset>
            
            <fieldset>
                <legend>Upload Media</legend>
                
                <div class="form-group">
                    <label for="jenis_media">Jenis Media <span style="color: red;">*</span></label>
                    <select id="jenis_media" name="jenis_media" required 
                            onchange="toggleMediaInput()">
                        <option value="">-- Pilih Jenis Media --</option>
                        <option value="foto">Foto</option>
                        <option value="video">Video (YouTube URL)</option>
                    </select>
                </div>
                
                <div class="form-group" id="uploadFoto" style="display: none;">
                    <label for="file_foto">Upload Foto <span style="color: red;">*</span></label>
                    <input type="file" id="file_foto" name="file_foto" 
                           accept="image/*"
                           onchange="previewImage(this, 'preview_foto')">
                    <small style="color: #666; display: block; margin-top: 5px;">
                        Format: JPG, PNG, GIF. Maksimal 5MB
                    </small>
                    
                    <img id="preview_foto" 
                         src="" alt="Preview" 
                         style="max-width: 400px; display: none; margin-top: 10px; border: 1px solid #ddd; padding: 10px;">
                </div>
                
                <div class="form-group" id="uploadVideo" style="display: none;">
                    <label for="url_video">URL Video YouTube <span style="color: red;">*</span></label>
                    <input type="url" id="url_video" name="url_video" 
                           placeholder="https://www.youtube.com/watch?v=xxxxx">
                    <small style="color: #666; display: block; margin-top: 5px;">
                        Masukkan link video YouTube
                    </small>
                </div>
            </fieldset>
            
            <div class="form-group" style="padding: 0 20px;">
                <button type="submit" class="btn-primary" name="submit">
                    Simpan Galeri
                </button>
                <a href="edit_galeri.php" class="btn-secondary" 
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
                <li>Pilih jenis media yang akan diupload (Foto atau Video YouTube)</li>
                <li>Untuk foto: Upload file gambar dengan format JPG, PNG, atau GIF</li>
                <li>Untuk video: Masukkan link YouTube video</li>
                <li>Pastikan deskripsi menjelaskan kegiatan dengan jelas</li>
            </ul>
        </div>
    </div>

    <script src="/admin/asset/js/script_admin.js"></script>
    <script>
        // Toggle input berdasarkan jenis media
        function toggleMediaInput() {
            const jenisMedia = document.getElementById('jenis_media').value;
            const uploadFoto = document.getElementById('uploadFoto');
            const uploadVideo = document.getElementById('uploadVideo');
            const fileFoto = document.getElementById('file_foto');
            const urlVideo = document.getElementById('url_video');
            
            if (jenisMedia === 'foto') {
                uploadFoto.style.display = 'block';
                uploadVideo.style.display = 'none';
                fileFoto.required = true;
                urlVideo.required = false;
                urlVideo.value = '';
            } else if (jenisMedia === 'video') {
                uploadFoto.style.display = 'none';
                uploadVideo.style.display = 'block';
                fileFoto.required = false;
                urlVideo.required = true;
                fileFoto.value = '';
            } else {
                uploadFoto.style.display = 'none';
                uploadVideo.style.display = 'none';
                fileFoto.required = false;
                urlVideo.required = false;
            }
        }
    </script>
</body>
</html>