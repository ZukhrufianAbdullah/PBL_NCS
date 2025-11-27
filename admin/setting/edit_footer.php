<?php
// File: admin/setting/edit_footer.php
session_start();
$pageTitle = 'Edit Footer Details';
$currentPage = 'edit_footer';
$adminPageStyles = ['forms'];

require_once dirname(__DIR__) . '/includes/admin_header.php';

// Koneksi database
$koneksi_path = __DIR__ . '/../../config/koneksi.php';
if (file_exists($koneksi_path)) {
    include $koneksi_path;
} else {
    die("File koneksi database tidak ditemukan: " . $koneksi_path);
}

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . pg_last_error());
}

// Ambil data settings
$settings_data = [];
$query_settings = "SELECT setting_name, setting_value FROM settings WHERE setting_name IN ('site_title', 'footer_copyright', 'footer_developer_title', 'footer_credit_tim')";
$result_settings = pg_query($conn, $query_settings);

if ($result_settings) {
    while ($row = pg_fetch_assoc($result_settings)) {
        $settings_data[$row['setting_name']] = $row['setting_value'];
    }
} else {
    echo "Error: " . pg_last_error($conn);
}

// Ambil data sosial media
$sosmed_data = [];
$query_sosmed = "SELECT * FROM sosial_media ORDER BY id_sosialmedia";
$result_sosmed = pg_query($conn, $query_sosmed);

if ($result_sosmed) {
    while ($row = pg_fetch_assoc($result_sosmed)) {
        $sosmed_data[] = $row;
    }
}

// Format credit tim untuk textarea
$credit_text = $settings_data['footer_credit_tim'] ?? "D4 Teknik Informatika\nAbelas Solihin\nEsatovin Ebenaezer Victoria\nMuhammad Nuril Huda\nNurfinka Lailasari\nZukhrufian Abdullah";
?>

<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: settings, sosial_media)</h1>
    <p>Kelola konten footer website termasuk judul lab, daftar developer, sosial media, dan teks copyright.</p>
</div>

<!-- Form untuk update footer -->
<div class="card">
    <form method="post" action="../../admin/proses/proses_footer.php">
        <input type="hidden" name="update_footer" value="1">
        
        <fieldset>
            <legend>Informasi Laboratorium</legend>
            <div class="form-group">
                <label for="site_title">Judul Laboratorium (setting_name: site_title)</label>
                <input type="text" id="site_title" name="site_title" 
                       value="<?php echo htmlspecialchars($settings_data['site_title'] ?? 'Network and Cyber Security Laboratory'); ?>">
                <span class="form-help-text">Judul ini akan muncul di footer dan bagian lain website.</span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Tim Developer</legend>
            <div class="form-group">
                <label for="footer_developer_title">Judul Kolom Developer (setting_name: footer_developer_title)</label>
                <input type="text" id="footer_developer_title" name="footer_developer_title" 
                       value="<?php echo htmlspecialchars($settings_data['footer_developer_title'] ?? 'Developed by'); ?>">
            </div>
            <div class="form-group">
                <label for="footer_credit_tim">Daftar Nama Developer/Tim (setting_name: footer_credit_tim)</label>
                <textarea id="footer_credit_tim" name="footer_credit_tim" rows="6"><?php echo htmlspecialchars($credit_text); ?></textarea>
                <span class="form-help-text">Setiap baris akan ditampilkan sebagai item terpisah di footer.</span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Hak Cipta</legend>
            <div class="form-group">
                <label for="footer_copyright">Teks Hak Cipta Lengkap (setting_name: footer_copyright)</label>
                <!-- PERBAIKAN: Ubah default value menjadi teks lengkap -->
                <textarea id="footer_copyright" name="footer_copyright" rows="2" placeholder="Contoh: © 2025 Network and Cyber Security Laboratory. All Rights Reserved."><?php echo htmlspecialchars($settings_data['footer_copyright'] ?? '© 2025 Network and Cyber Security Laboratory. All Rights Reserved.'); ?></textarea>
                <span class="form-help-text">Edit keseluruhan teks hak cipta termasuk simbol ©, tahun, dan teks lengkap.</span>
            </div>
        </fieldset>

        <div class="form-group">
            <button type="submit" class="btn-primary">Simpan Perubahan Footer</button>
        </div>
    </form>
</div>

<!-- Form untuk menambah sosial media -->
<div class="card">
    <form method="post" action="../../admin/proses/proses_footer.php">
        <input type="hidden" name="tambah_sosmed" value="1">
        
        <fieldset>
            <legend>Tambah Sosial Media Baru</legend>
            <div class="form-group">
                <label for="nama_sosialmedia">Nama Sosial Media *</label>
                <input type="text" id="nama_sosialmedia" name="nama_sosialmedia" placeholder="Contoh: Instagram Official" required>
            </div>
            <div class="form-group">
                <label for="platform">Platform *</label>
                <select id="platform" name="platform" required>
                    <option value="">Pilih Platform</option>
                    <option value="linkedin">LinkedIn</option>
                    <option value="youtube">YouTube</option>
                    <option value="twitter">Twitter</option>
                    <option value="instagram">Instagram</option>
                    <option value="facebook">Facebook</option>
                    <option value="sinta">SINTA</option>
                    <option value="other">Lainnya</option>
                </select>
            </div>
            <div class="form-group">
                <label for="url">URL Lengkap *</label>
                <input type="url" id="url" name="url" placeholder="https://instagram.com/labncs" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-primary">Tambahkan Sosial Media</button>
            </div>
        </fieldset>
    </form>
</div>

<!-- Daftar Sosial Media Existing -->
<?php if (!empty($sosmed_data)): ?>
<div class="card">
    <fieldset>
        <legend>Daftar Sosial Media</legend>
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Platform</th>
                    <th>URL</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sosmed_data as $sosmed): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sosmed['nama_sosialmedia']); ?></td>
                    <td><?php echo htmlspecialchars($sosmed['platform']); ?></td>
                    <td><?php echo htmlspecialchars($sosmed['url']); ?></td>
                    <td>
                        <form method="post" action="../../admin/proses/proses_footer.php" style="display:inline;">
                            <input type="hidden" name="hapus_sosmed" value="1">
                            <input type="hidden" name="id_sosialmedia" value="<?php echo $sosmed['id_sosialmedia']; ?>">
                            <button type="submit" class="btn-danger btn-sm" onclick="return confirm('Yakin hapus sosial media ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </fieldset>
</div>
<?php endif; ?>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>