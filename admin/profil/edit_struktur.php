<?php 
// File: admin/profil/edit_struktur.php
session_start();
$page_title = "Edit Struktur Organisasi";
$current_page = "edit_struktur";
$base_url = '../'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>style_admin.css">
    <script src="<?php echo $base_url; ?>script_admin.js"></script>
    <style>
        .dosen-table { width: 100%; border-collapse: collapse; background-color: white; margin-top: 20px; }
        .dosen-table th, .dosen-table td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        .dosen-table thead tr { background-color: #eee; } 
    </style>
</head>
<body>

    <div class="sidebar">
        <a href="../index.php">Dashboard</a>
        <div class="dropdown-item">
            <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('manajemenKonten')">
                MANAJEMEN KONTEN
                <span class="dropdown-icon" id="icon-manajemenKonten">></span>
            </a>
            <div class="submenu-wrapper" id="manajemenKonten">
                <div class="dropdown-item">
                    <a href="javascript:void(0);" class="dropdown-toggle" onclick="toggleMenu('subMenuProfile')">
                        Profile
                        <span class="dropdown-icon" id="icon-subMenuProfile">></span>
                    </a>
                    <div class="submenu-wrapper" id="subMenuProfile">
                        <a href="edit_visi_misi.php">Visi & Misi</a>
                        <a href="edit_logo.php">Logo</a>
                        <a href="edit_struktur.php" class="<?php echo $current_page == 'edit_struktur' ? 'active' : ''; ?>">Struktur Organisasi</a>
                    </div>
                </div>
                </div>
        </div>
    </div>

    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?> (Tabel: dosen & anggota lab)</h1>
        </div>

        <p>Gunakan halaman ini untuk menambah anggota baru, serta melihat dan mengelola detail semua dosen/staf yang ada (nama, jabatan, foto, dll.).</p>

        <fieldset style="border: 1px solid #ccc; padding: 20px; margin-bottom: 30px;">
            <legend>Tambah Anggota Tim Baru</legend>
            <form method="post" action="../proses/proses_dosen.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nama_dosen_new">Nama Lengkap & Gelar (Kolom: nama_dosen)</label>
                    <input type="text" id="nama_dosen_new" name="nama_dosen_new" required>
                </div>
                <div class="form-group">
                    <label for="jabatan_new">Jabatan / Role (Kolom: jabatan)</label>
                    <input type="text" id="jabatan_new" name="jabatan_new" required>
                </div>
                <div class="form-group">
                    <label for="media_path_dosen_new">Foto Profil (Kolom: media_path)</label>
                    <input type="file" id="media_path_dosen_new" name="media_path_dosen_new" accept="image/*">
                </div>
                <input type="submit" class="btn-primary" value="Tambahkan Anggota Baru">
            </form>
        </fieldset>

        <h2>Daftar Semua Dosen/Staf Aktif</h2>
        <table class="dosen-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Ben Carter</td>
                    <td>Postdoctoral Researcher</td>
                    <td>[Foto Tampil]</td>
                    <td><button class="btn-primary" style="background-color: orange;">Edit</button></td>
                </tr>
                <tr>
                    <td>Aisha Khan</td>
                    <td>PhD Candidate</td>
                    <td>[Foto Tampil]</td>
                    <td><button class="btn-primary" style="background-color: orange;">Edit</button></td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>