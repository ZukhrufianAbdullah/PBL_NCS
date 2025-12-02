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
$query_settings = "SELECT setting_name, setting_value FROM settings WHERE setting_name IN ('site_title', 'footer_description', 'footer_copyright', 'footer_developer_title', 'footer_credit_tim', 'footer_show_quick_links')";
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
$credit_text = $settings_data['footer_credit_tim'] ?? "";

// Status quick links
$show_quick_links = ($settings_data['footer_show_quick_links'] ?? 'true') === 'true';
?>

<div class="admin-header">
    <h1><?php echo $pageTitle; ?></h1>
    <p>Gunakan form berikut untuk mengelola footer.</p>
</div>

<!-- Form untuk update footer -->
<div class="card">
    <form method="post" action="../../admin/proses/proses_footer.php">
        <input type="hidden" name="update_footer" value="1">
        
        <fieldset>
            <legend>Konten</legend>
            <div class="form-group">
                <label for="site_title">Judul Laboratorium</label>
                <input type="text" id="site_title" name="site_title" 
                        placeholder="Masukkan judul utama footer"
                       value="<?php echo htmlspecialchars($settings_data['site_title'] ?? ''); ?>">
                <span class="form-help-text">Judul ini akan muncul di title footer.</span>
            </div>
            
            <div class="form-group">
                <label for="footer_description">Deskripsi Footer</label>
                <textarea id="footer_description" name="footer_description" rows="3" 
                          placeholder="Masukkan deskripsi singkat footer"><?php echo htmlspecialchars($settings_data['footer_description'] ?? ''); ?></textarea>
                <span class="form-help-text">Deskripsi ini akan muncul di bawah judul laboratorium pada footer.</span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Akses Cepat (Quick Links)</legend>
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="footer_show_quick_links" value="true" 
                           <?php echo $show_quick_links ? 'checked' : ''; ?>>
                    <span>Tampilkan Akses Cepat di Footer</span>
                </label>
                <span class="form-help-text">Jika dicentang, menu navigasi cepat akan muncul di bagian tengah footer antara informasi lab dan tim developer.</span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Tim Developer</legend>
            <div class="form-group">
                <label for="footer_developer_title">Judul Kolom Developer</label>
                <input type="text" id="footer_developer_title" name="footer_developer_title" 
                       placeholder="Masukkan judul section"
                       value="<?php echo htmlspecialchars($settings_data['footer_developer_title'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="footer_credit_tim">Daftar Nama Developer/Tim</label>
                <textarea id="footer_credit_tim" name="footer_credit_tim" rows="6"
                placeholder="Masukkan nama tim developed"><?php echo htmlspecialchars($credit_text); ?></textarea>
                <span class="form-help-text">Setiap baris akan ditampilkan sebagai item terpisah di footer.</span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Hak Cipta</legend>
            <div class="form-group">
                <label for="footer_copyright">Teks Hak Cipta Lengkap</label>
                <textarea id="footer_copyright" name="footer_copyright" rows="2" 
                placeholder="Masukkan hak cipta"><?php echo htmlspecialchars($settings_data['footer_copyright'] ?? ''); ?></textarea>
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
                <label for="nama_sosialmedia">Nama Sosial Media</label>
                <input type="text" id="nama_sosialmedia" name="nama_sosialmedia" placeholder="Masukkan nama sosial media" required>
            </div>
            <div class="form-group">
                <label for="platform">Platform</label>
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
                <label for="url">URL Lengkap</label>
                <input type="url" id="url" name="url" placeholder="Masukkan link URL" required>
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
                                <button type="button" class="btn-edit btn-sm" 
                                        onclick="editSosmed(
                                            <?php echo $sosmed['id_sosialmedia']; ?>,
                                            '<?php echo htmlspecialchars(addslashes($sosmed['nama_sosialmedia'])); ?>',
                                            '<?php echo htmlspecialchars(addslashes($sosmed['platform'])); ?>',
                                            '<?php echo htmlspecialchars(addslashes($sosmed['url'])); ?>'
                                        )">
                                    Edit
                                </button>
                                
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

/* Checkbox Styling */
.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-weight: 500;
}

.checkbox-label input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.checkbox-label span {
    user-select: none;
}
</style>

<script>
// Fungsi untuk membuka modal edit
function editSosmed(id, nama, platform, url) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_platform').value = platform;
    document.getElementById('edit_url').value = url;
    
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