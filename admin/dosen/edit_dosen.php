<?php 
// File: admin/dosen/edit_dosen.php
session_start();
$page_title = "Manajemen Profil Dosen Saya";
$current_page = "edit_dosen";
$base_url = '../'; // Path relatif naik satu tingkat ke folder admin/

// ==========================================================
// SIMULASI SESI LOGIN
// Asumsi: ID 1 adalah dosen yang sedang login. 
// Dalam implementasi nyata, ID ini didapat dari $_SESSION['user_id']
// ==========================================================
$logged_in_user_id = 1; 

// Data dummy (tiruan) untuk simulasi daftar dosen/staf dari tabel 'dosen'
$dummy_dosen = [
    // Data Dosen 1 (ID yang sedang login)
    ['id' => 1, 'nama_dosen' => 'Dr. Budi Santoso', 'jabatan' => 'Kepala Lab', 'email' => 'budi@ncs.id', 'media_path' => 'budi_santoso.jpg'], 
    
    // Data Dosen 2
    ['id' => 2, 'nama_dosen' => 'Dr. Siti Rahayu', 'jabatan' => 'Peneliti Utama', 'email' => 'siti@ncs.id', 'media_path' => 'siti_rahayu.jpg'], 
    
    // Data Dosen 3
    ['id' => 3, 'nama_dosen' => 'Ahmad Fauzi, M.Sc.', 'jabatan' => 'Staf Teknis', 'email' => 'ahmad@ncs.id', 'media_path' => 'ahmad_fauzi.jpg'],
];

// FILTER DATA: Hanya ambil profil dosen yang ID-nya sama dengan ID yang sedang login
$dosen_profile = [];
foreach ($dummy_dosen as $item) {
    if ($item['id'] === $logged_in_user_id) {
        $dosen_profile = $item;
        break; // Hentikan loop setelah menemukan profil yang cocok
    }
}

// Persiapkan nilai default
$dosen_name_val = $dosen_profile['nama_dosen'] ?? '';
$dosen_role_val = $dosen_profile['jabatan'] ?? '';
$dosen_email_val = $dosen_profile['email'] ?? '';
$dosen_photo_path = $dosen_profile['media_path'] ?? 'default.jpg';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?> - NCS Lab</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>style_admin.css">
    <script src="<?php echo $base_url; ?>script_admin.js"></script>
    <style>
        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
            margin-bottom: 20px;
            display: block;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>ADMIN NCS LAB</h2>
        
        <a href="../index.php">Dashboard</a>
        <a href="../beranda/edit_beranda.php">Edit Beranda</a>
        <a href="../edit_header.php">Edit Header Title</a> 
        <a href="../profil/edit_logo.php">Edit Logo</a> 
        <a href="edit_dosen.php" class="<?php echo $current_page == 'edit_dosen' ? 'active' : ''; ?>">Profil Dosen/Staf</a>
        <a href="../logout.php" style="margin-top: 20px;">Logout</a>
    </div>

    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?> (Tabel: dosen)</h1>
        </div>

        <?php if (!empty($dosen_profile)): ?>
            <p>Anda sedang mengedit profil Anda sendiri (**<?php echo htmlspecialchars($dosen_name_val); ?>** - ID User: <?php echo $logged_in_user_id; ?>).</p>

            <fieldset style="border: 1px solid #ccc; padding: 20px;">
                <legend style="font-size: 1.2em; font-weight: bold; color: var(--primary-color);">Informasi Profil Dosen</legend>
                
                <form method="post" action="../../proses/proses_dosen.php" enctype="multipart/form-data">
                    
                    <img src="<?php echo $base_url; ?>uploads/dosen/<?php echo htmlspecialchars($dosen_photo_path); ?>" 
                         alt="Foto Profil" class="profile-photo" title="Foto saat ini (Simulasi Path)">
                    <small>Path simulasi foto: `<?php echo $base_url; ?>uploads/dosen/<?php echo htmlspecialchars($dosen_photo_path); ?>`</small>
                    
                    <hr style="margin: 20px 0;">

                    <div class="form-group">
                        <label for="dosen_name">Nama Lengkap (Kolom: nama_dosen)</label>
                        <input type="text" id="dosen_name" name="dosen_name" value="<?php echo htmlspecialchars($dosen_name_val); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="dosen_role">Jabatan/Peran (Kolom: jabatan)</label>
                        <input type="text" id="dosen_role" name="dosen_role" value="<?php echo htmlspecialchars($dosen_role_val); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="dosen_email">Email Kontak (Kolom: email)</label>
                        <input type="email" id="dosen_email" name="dosen_email" value="<?php echo htmlspecialchars($dosen_email_val); ?>">
                    </div>

                    <div class="form-group">
                        <label for="dosen_photo">Ganti Foto Profil (Kolom: media_path)</label>
                        <input type="file" id="dosen_photo" name="dosen_photo" accept="image/*">
                        <small>Unggah foto baru untuk mengganti foto profil Anda saat ini.</small>
                    </div>
                    
                    <input type="hidden" name="dosen_id" value="<?php echo $logged_in_user_id; ?>">
                    <input type="submit" class="btn-primary" value="Simpan Profil Saya">
                </form>
            </fieldset>

        <?php else: ?>
            <div class="alert-success" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                <p>⚠️ **ERROR:** Profil dosen untuk ID yang sedang login (ID: <?php echo $logged_in_user_id; ?>) tidak ditemukan. Hubungi Administrator.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>