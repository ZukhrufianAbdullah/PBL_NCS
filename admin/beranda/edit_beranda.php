<?php
// File: admin/beranda/edit_beranda.php
session_start();
$pageTitle = 'Edit Beranda';
$currentPage = 'edit_beranda';
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

// Include helper functions
require_once __DIR__ . '/../../app/helpers/page_helper.php';

// Inisialisasi halaman home jika belum ada
$homePageId = init_home_page_and_sections($conn, $_SESSION['id_user'] ?? 1);

if (!$homePageId) {
    die("Gagal menginisialisasi halaman home. Pastikan koneksi database berhasil.");
}

// Ambil data deskripsi beranda
$qDeskripsi = pg_query($conn, "
    SELECT content_value 
    FROM page_content 
    WHERE id_page = $homePageId AND content_key = 'deskripsi' 
    LIMIT 1");
$deskripsi = pg_fetch_assoc($qDeskripsi)['content_value'] ?? '';

// Ambil data visibility settings untuk setiap section
$qVisibility = pg_query($conn, "
    SELECT content_key, content_value 
    FROM page_content 
    WHERE id_page = $homePageId AND content_key LIKE 'show_%'");

$visibility = [];
while ($row = pg_fetch_assoc($qVisibility)) {
    $visibility[$row['content_key']] = ($row['content_value'] === 'true');
}

// Default visibility jika belum ada di database
$sections = [
    'show_visi_misi' => 'Visi & Misi',
    'show_logo' => 'Logo',
    'show_struktur' => 'Struktur Organisasi',
    'show_agenda' => 'Agenda',
    'show_galeri' => 'Galeri',
    'show_penelitian' => 'Penelitian',
    'show_pengabdian' => 'Pengabdian',
    'show_sarana' => 'Sarana & Prasarana'
];

// Pastikan semua section ada dalam visibility array
foreach ($sections as $key => $label) {
    if (!isset($visibility[$key])) {
        // Default: tampilkan semua
        $visibility[$key] = true;
        
        // Otomatis tambahkan ke database jika belum ada
        pg_query_params($conn, 
            "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user) 
             VALUES ($1, $2, 'boolean', 'true', $3)
             ON CONFLICT DO NOTHING",
            array($homePageId, $key, $_SESSION['id_user'] ?? 1));
    }
}
?>

<div class="admin-header">
    <h1><?php echo $pageTitle; ?></h1>
    <p>Kelola konten halaman beranda (home page) termasuk deskripsi dan visibilitas section preview.</p>
</div>

<!-- Form Edit Deskripsi Beranda -->
<div class="card">
    <form method="post" action="../../admin/proses/proses_beranda.php">
        <input type="hidden" name="update_deskripsi" value="1">
        
        <fieldset>
            <legend>Deskripsi Beranda</legend>
            <div class="form-group">
                <label for="deskripsi">Deskripsi Singkat Beranda</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" 
                          placeholder="Masukkan deskripsi singkat yang akan ditampilkan di bagian atas halaman beranda"><?php echo htmlspecialchars($deskripsi); ?></textarea>
                <span class="form-help-text">Deskripsi ini akan muncul di intro card pada halaman beranda.</span>
            </div>  
        </fieldset>

        <div class="form-group">
            <button type="submit" class="btn-primary">Simpan Deskripsi</button>
        </div>
    </form>
</div>

<!-- Form Kontrol Visibilitas Section -->
<div class="card">
    <form method="post" action="../../admin/proses/proses_beranda.php">
        <input type="hidden" name="update_visibility" value="1">
        
        <fieldset>
            <legend>Kontrol Tampilan Section di Home Page</legend>
            <p class="form-help-text mb-3">
                Centang section yang ingin ditampilkan di halaman beranda. 
                Setiap section akan menampilkan preview (3 item) dengan tombol "View More" 
                yang mengarah ke halaman lengkap.
            </p>
            
            <div class="checkbox-grid">
                <?php foreach ($sections as $key => $label): ?>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" 
                                   name="<?php echo $key; ?>" 
                                   value="true" 
                                   <?php echo $visibility[$key] ? 'checked' : ''; ?>>
                            <span><?php echo $label; ?></span>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="alert alert-info mt-3">
                <strong>Info:</strong> Section yang tidak dicentang tidak akan muncul di halaman beranda. 
                Perubahan akan langsung terlihat setelah disimpan.
            </div>
        </fieldset>

        <div class="form-group">
            <button type="submit" class="btn-primary">Simpan Pengaturan Visibilitas</button>
        </div>
    </form>
</div>

<!-- Info Preview Content -->
<div class="card">
    <fieldset>
        <legend>Informasi Preview Content</legend>
        <div class="info-table">
            <table class="my-table">
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Preview</th>
                        <th>Link Halaman Lengkap</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Visi & Misi</strong></td>
                        <td>2 card (Visi + Misi), terpotong 250 karakter</td>
                        <td>/user/profil/visi_misi.php</td>
                    </tr>
                    <tr>
                        <td><strong>Logo</strong></td>
                        <td>1 logo utama</td>
                        <td>/user/profil/logo.php</td>
                    </tr>
                    <tr>
                        <td><strong>Struktur Organisasi</strong></td>
                        <td>3 anggota pertama</td>
                        <td>/user/profil/struktur.php</td>
                    </tr>
                    <tr>
                        <td><strong>Agenda</strong></td>
                        <td>Tabel 3 baris terbaru</td>
                        <td>/user/galeri/agenda.php</td>
                    </tr>
                    <tr>
                        <td><strong>Galeri</strong></td>
                        <td>3 card gambar terbaru</td>
                        <td>/user/galeri/galeri.php</td>
                    </tr>
                    <tr>
                        <td><strong>Penelitian</strong></td>
                        <td>3 card penelitian terbaru</td>
                        <td>/user/arsip/penelitian.php</td>
                    </tr>
                    <tr>
                        <td><strong>Pengabdian</strong></td>
                        <td>Tabel 3 baris terbaru</td>
                        <td>/user/arsip/pengabdian.php</td>
                    </tr>
                    <tr>
                        <td><strong>Sarana & Prasarana</strong></td>
                        <td>3 card fasilitas</td>
                        <td>/user/layanan/sarana_prasarana.php</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </fieldset>
</div>

<!-- Tambahkan kotak informasi tentang inisialisasi otomatis -->
<div class="card mt-4">
    <fieldset>
        <legend>Informasi Sistem</legend>
        <div class="system-info">
            <p><strong>Sistem Inisialisasi Otomatis:</strong></p>
            <ul>
                <li>Sistem akan otomatis membuat halaman 'home' jika belum ada</li>
                <li>Semua pengaturan section akan dibuat dengan nilai default 'true'</li>
                <li>Deskripsi default akan ditambahkan jika kosong</li>
                <li>Tidak perlu menjalankan query SQL manual di DBeaver</li>
            </ul>
            <div class="alert alert-success mt-2">
                <strong>Status:</strong> Sistem berhasil diinisialisasi. 
                Halaman home ID: <?php echo $homePageId; ?>
            </div>
        </div>
    </fieldset>
</div>

    <style>
    /* Checkbox Grid Layout */
    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-weight: 500;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 8px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .checkbox-label:hover {
        background: #e9ecef;
        border-color: #153b91;
    }

    .checkbox-label input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .checkbox-label input[type="checkbox"]:checked + span {
        color: #153b91;
        font-weight: 600;
    }

    .checkbox-label span {
        user-select: none;
        flex: 1;
    }

    /* Alert Styling */
    .alert {
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid;
    }

    .alert-info {
        background: #e7f3ff;
        border-color: #1f54c5;
        color: #0c1b40;
    }

    .alert strong {
        font-weight: 600;
    }

    /* Info Table */
    .info-table {
        overflow-x: auto;
    }

    .my-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .my-table th,
    .my-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #e9ecef;
    }

    .my-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .my-table tbody tr:hover {
        background: #f8f9fa;
    }

    .my-table tbody tr:last-child td {
        border-bottom: none;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    .mt-3 {
        margin-top: 1rem;
    }
    
    .system-info {
    padding: 1rem;
    }

    .system-info ul {
        margin-left: 1.5rem;
        margin-bottom: 1rem;
    }

    .system-info li {
        margin-bottom: 0.5rem;
        line-height: 1.5;
    }

    .alert-success {
        background: #d4edda;
        border-color: #28a745;
        color: #155724;
        padding: 0.75rem;
        border-radius: 6px;
        margin-top: 1rem;
    }

    .mt-2 {
        margin-top: 0.5rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }
    </style>

    <?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>