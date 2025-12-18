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
            </p>
            
            <div class="checkbox-grid">
                <?php foreach ($sections as $key => $label): ?>
                    <div class="form-group" style="margin-bottom: 0;">
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

    <!-- Tambahkan CSS responsif di bagian style -->
<style>
    /* =======================================================
       CHECKBOX GRID RESPONSIVE FIX
    ======================================================== */

    /* Checkbox Grid Layout - PERBAIKAN UTAMA */
    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    /* Untuk mobile kecil: 1 kolom */
    @media (max-width: 480px) {
        .checkbox-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
    }

    /* Untuk tablet: 2 kolom */
    @media (min-width: 481px) and (max-width: 768px) {
        .checkbox-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Untuk desktop: 3 kolom */
    @media (min-width: 769px) and (max-width: 1024px) {
        .checkbox-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Untuk desktop besar: 4 kolom */
    @media (min-width: 1025px) {
        .checkbox-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    /* Card container - PERBAIKAN PENTING */
    .card {
        background: var(--white);
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 24px;
        margin-bottom: 24px;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        overflow: hidden; /* Mencegah overflow */
    }

    /* Form container fix */
    form {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    /* Fieldset fix */
    fieldset {
        border: none;
        background-color: var(--white);
        padding: 24px;
        margin-bottom: 0;
        border-radius: 10px;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    /* Legend fix untuk responsif */
    legend {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-color);
        border-bottom: 3px solid var(--accent-yellow);
        padding: 10px 15px;
        /* margin-bottom: 20px; */
        width: 100%;
        max-width: calc(100% + 48px);
        margin-left: -24px;
        margin-right: -24px;
        margin-top: -24px;
        display: block;
        background-color: #ffffff;
        border-radius: 10px 10px 0 0;
        box-sizing: border-box;
        position: relative;
        left: 0;
        right: 0;
    }

    /* Checkbox label yang lebih responsif */
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
        width: 100%;
        box-sizing: border-box;
        word-break: break-word; /* Untuk text panjang */
        hyphens: auto; /* Tambah hyphen jika perlu */
    }

    .checkbox-label:hover {
        background: #e9ecef;
        border-color: #153b91;
    }

    .checkbox-label input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        flex-shrink: 0; /* Mencegah checkbox menyusut */
    }

    .checkbox-label input[type="checkbox"]:checked + span {
        color: #153b91;
        font-weight: 600;
    }

    .checkbox-label span {
        user-select: none;
        flex: 1;
        word-wrap: break-word;
        overflow-wrap: break-word;
        line-height: 1.4;
    }

    /* Alert Styling */
    .alert {
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .alert-info {
        background: #e7f3ff;
        border-color: #1f54c5;
        color: #0c1b40;
    }

    .alert strong {
        font-weight: 600;
    }

    /* Admin header */
    .admin-header {
        margin-bottom: 2rem;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .admin-header h1 {
        margin-bottom: 0.5rem;
        color: var(--primary-color);
    }

    .admin-header p {
        color: var(--text-gray);
        font-size: 0.95rem;
    }

    /* Form group fix */
    .form-group {
        margin-bottom: 20px;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    /* Help text */
    .form-help-text {
        display: block;
        font-size: 0.85rem;
        color: var(--text-gray);
        margin-top: 6px;
        font-style: italic;
        line-height: 1.4;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    /* Responsive textarea */
    textarea {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        min-height: 120px;
        resize: vertical;
        line-height: 1.6;
    }

    /* Button fix */
    .btn-primary {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: auto;
        min-width: 150px;
    }

    .btn-primary:hover {
        background-color: #0a2666;
    }

    /* Mobile-specific fixes */
    @media (max-width: 768px) {
        .card {
            padding: 16px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        
        fieldset {
            padding: 16px;
        }
        
        legend {
            font-size: 1.1rem;
            padding: 8px 12px;
            margin-left: -16px;
            margin-right: -16px;
            margin-top: -16px;
            max-width: calc(100% + 32px);
        }
        
        .checkbox-label {
            padding: 0.6rem;
            font-size: 0.9rem;
        }
        
        .checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }
        
        .alert {
            padding: 0.75rem;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            width: 100%; /* Full width di mobile */
            text-align: center;
        }
    }

    /* Very small mobile */
    @media (max-width: 360px) {
        .checkbox-grid {
            gap: 0.5rem;
        }
        
        .checkbox-label {
            padding: 0.5rem;
            font-size: 0.85rem;
        }
        
        .checkbox-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
        }
    }
</style>

    <?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>