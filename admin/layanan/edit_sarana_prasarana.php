<?php 
// File: admin/layanan/edit_sarana_prasarana.php
session_start();
$page_title = "Manajemen Sarana & Prasarana";
$current_page = "edit_sarana";
$base_url = '../../'; // Path relatif naik dua tingkat ke folder admin/
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>style_admin.css">
    <script src="<?php echo $base_url; ?>script_admin.js"></script>
    <style>
        .sarana-table th, .sarana-table td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        .sarana-table thead tr { background-color: #eee; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>ADMIN NCS LAB</h2>
        <a href="../index.php">Dashboard</a>
        <a href="../beranda/edit_beranda.php">Edit Beranda</a>
        <a href="../edit_header.php">Edit Header Title</a> 
        <a href="../profil/edit_logo.php">Edit Logo</a> 
        <a href="../edit_footer.php">Edit Footer Details</a> 
        <a href="../dosen/edit_dosen.php">Profil Dosen/Staf</a>
        
        <h3>MANAJEMEN KONTEN</h3>
        <a href="edit_sarana_prasarana.php" class="<?php echo $current_page == 'edit_sarana' ? 'active' : ''; ?>">Sarana & Prasarana</a>
        <a href="lihat_pesan.php">Pesan Konsultatif</a>
        <a href="../logout.php">Logout</a>
    </div>

    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?> (Tabel: sarana)</h1>
        </div>

        <p>Gunakan form ini untuk menambah sarana/prasarana atau layanan baru yang ditampilkan di halaman Services/sarana-prasarana.</p>

        <form method="post" action="../../proses/proses_layanan.php" enctype="multipart/form-data">
            
            <fieldset style="border: 1px solid #ccc; padding: 20px; margin-bottom: 30px;">
                <legend style="font-size: 1.2em; font-weight: bold; color: var(--primary-color);">Tambah Sarana/Layanan Baru</legend>
                
                <div class="form-group">
                    <label for="nama_sarana">Nama Sarana/Layanan (Kolom: nama_sarana)</label>
                    <input type="text" id="nama_sarana" name="nama_sarana" placeholder="Contoh: Dedicated Server Room" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi (Kolom: deskripsi)</label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <label for="media_path">Foto Sarana (Kolom: media_path)</label>
                    <input type="file" id="media_path" name="media_path" accept="image/*" required>
                </div>
                
                <input type="submit" class="btn-primary" value="Tambahkan Sarana">
            </fieldset>

            <h3 style="margin-top: 30px; color: var(--primary-color);">Daftar Sarana Aktif Saat Ini</h3>
            <table class="sarana-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Sarana</th>
                        <th>Deskripsi Singkat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Dedicated Server Room</td>
                        <td>Infrastruktur berdaya tinggi untuk hosting...</td>
                        <td><button type="button" class="btn-primary" style="background-color: orange;">Edit</button></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Network Forensics Tools</td>
                        <td>Perangkat lunak untuk analisis jaringan...</td>
                        <td><button type="button" class="btn-primary" style="background-color: orange;">Edit</button></td>
                    </tr>
                </tbody>
            </table>
        </form>

    </div>
</body>
</html>