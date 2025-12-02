<?php
// File: admin/setting/edit_footer.php
session_start();
$pageTitle = 'Edit Footer Details';
$currentPage = 'edit_footer';
$adminPageStyles = ['forms', 'tables'];

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
$query_settings = "SELECT setting_name, setting_value FROM settings WHERE setting_name IN ('site_title', 'footer_description', 'footer_copyright', 'footer_developer_title', 'footer_credit_tim')";
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
    <p>Kelola konten footer website termasuk judul lab, deskripsi footer, daftar developer, sosial media, dan teks copyright.</p>
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
                <span class="form-help-text">Judul ini akan muncul di title footer.</span>
            </div>
            
            <div class="form-group">
                <label for="footer_description">Deskripsi Footer (setting_name: footer_description)</label>
                <textarea id="footer_description" name="footer_description" rows="3" 
                          placeholder="Masukkan deskripsi singkat laboratorium yang akan ditampilkan di bawah judul footer"><?php echo htmlspecialchars($settings_data['footer_description'] ?? 'Network and Cyber Security Laboratory'); ?></textarea>
                <span class="form-help-text">Deskripsi ini akan muncul di bawah judul laboratorium pada footer.</span>
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

        <div class="data-table">
            <table class="my-table">
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
                            <div class="action-buttons">
                                <!-- Tombol Edit -->
                                <button type="button" class="btn-edit btn-sm" 
                                        onclick="editSosmed(
                                            <?php echo $sosmed['id_sosialmedia']; ?>,
                                            '<?php echo htmlspecialchars(addslashes($sosmed['nama_sosialmedia'])); ?>',
                                            '<?php echo htmlspecialchars(addslashes($sosmed['platform'])); ?>',
                                            '<?php echo htmlspecialchars(addslashes($sosmed['url'])); ?>'
                                        )">
                                    Edit
                                </button>
                                
                                <!-- Form Hapus -->
                                <form method="post" action="../../admin/proses/proses_footer.php" 
                                      class="inline-form" 
                                      onsubmit="return confirm('Yakin hapus sosial media ini?')">
                                    <input type="hidden" name="hapus_sosmed" value="1">
                                    <input type="hidden" name="id_sosialmedia" value="<?php echo $sosmed['id_sosialmedia']; ?>">
                                    <button type="submit" class="btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </fieldset>
</div>
<?php endif; ?>

<!-- Modal Edit Sosial Media -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Edit Sosial Media</h2>
        <form method="post" action="../../admin/proses/proses_footer.php" id="editForm">
            <input type="hidden" name="update_sosmed" value="1">
            <input type="hidden" name="id_sosialmedia" id="edit_id">
            
            <div class="form-group">
                <label for="edit_nama">Nama Sosial Media *</label>
                <input type="text" id="edit_nama" name="nama_sosialmedia" required>
            </div>
            
            <div class="form-group">
                <label for="edit_platform">Platform *</label>
                <select id="edit_platform" name="platform" required>
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
                <label for="edit_url">URL Lengkap *</label>
                <input type="url" id="edit_url" name="url" required>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Modal Styling */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fff;
    margin: 10% auto;
    padding: 25px;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #000;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-edit {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.btn-edit:hover {
    background-color: #45a049;
}

.inline-form {
    display: inline;
}
</style>

<script>
// Fungsi untuk membuka modal edit
function editSosmed(id, nama, platform, url) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_platform').value = platform;
    document.getElementById('edit_url').value = url;
    
    // Tampilkan modal
    document.getElementById('editModal').style.display = 'block';
}

// Fungsi untuk menutup modal
function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Tutup modal jika klik di luar konten modal
window.onclick = function(event) {
    var modal = document.getElementById('editModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Validasi form sebelum submit
document.getElementById('editForm').addEventListener('submit', function(e) {
    var nama = document.getElementById('edit_nama').value;
    var platform = document.getElementById('edit_platform').value;
    var url = document.getElementById('edit_url').value;
    
    if (!nama || !platform || !url) {
        e.preventDefault();
        alert('Nama, platform, dan URL harus diisi!');
        return false;
    }
    
    return true;
});
</script>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>