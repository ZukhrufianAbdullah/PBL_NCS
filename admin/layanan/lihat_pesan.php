<?php
// File: admin/layanan/lihat_pesan.php
session_start();
$pageTitle = 'Kelola Konsultatif';
$currentPage = 'lihat_pesan';
$adminPageStyles = ['forms', 'tables', 'modal'];
include '../../config/koneksi.php';
require_once dirname(__DIR__) . '/includes/admin_header.php';

// Ambil id_user dari session
$id_user = $_SESSION['id_user'] ?? 1;

// Helper function untuk ensure page exists
function ensure_page($conn, string $pageName): int
{
    $pageResult = pg_query_params($conn, "SELECT id_page FROM pages WHERE nama = $1", array($pageName));
    if ($pageResult && pg_num_rows($pageResult) > 0) {
        $page = pg_fetch_assoc($pageResult);
        return (int) $page['id_page'];
    }

    $insertPage = pg_query_params($conn, "INSERT INTO pages (nama) VALUES ($1) RETURNING id_page", array($pageName));
    $page = pg_fetch_assoc($insertPage);
    return (int) $page['id_page'];
}

// PROSES HAPUS PESAN - HARUS DI ATAS SEBELUM OUTPUT APAPUN
if (isset($_POST['hapus_pesan'])) {
    $id = (int)$_POST['hapus_id'];
    $sql = "DELETE FROM konsultatif WHERE id_konsultatif = $1";
    $result = pg_query_params($conn, $sql, array($id));
    
    if ($result) {
        echo "<script>
        alert('Pesan berhasil dihapus!');
        window.location.href = '../layanan/lihat_pesan.php';
        </script>";
    } else {
        echo "<script>
        alert('Pesan gagal dihapus!');
        window.location.href = '../layanan/lihat_pesan.php';
        </script>";    
    }
    exit();
}

// Ambil id_page untuk halaman 'layanan_konsultatif'
$page_key = "layanan_konsultatif";
$id_page = ensure_page($conn, $page_key);

// AMBIL DATA SECTION TITLE & DESCRIPTION
$section_title = '';
$section_description = '';

// Ambil section title
$qTitle = pg_query_params($conn, 
    "SELECT content_value FROM page_content WHERE id_page = $1 AND content_key = 'section_title'",
    array($id_page)
);
if ($qTitle && pg_num_rows($qTitle) > 0) {
    $section_title = pg_fetch_assoc($qTitle)['content_value'];
} else {
    $section_title = 'Konsultatif';
}

// Ambil section description
$qDesc = pg_query_params($conn, 
    "SELECT content_value FROM page_content WHERE id_page = $1 AND content_key = 'section_description'",
    array($id_page)
);
if ($qDesc && pg_num_rows($qDesc) > 0) {
    $section_description = pg_fetch_assoc($qDesc)['content_value'];
} else {
    $section_description = 'Leveraging academic expertise to offer specialized network and cybersecurity consulting to industry, government, and academic partners.';
}

// AMBIL DATA PESAN DARI DATABASE
$sql = "SELECT * FROM konsultatif ORDER BY tanggal_kirim DESC";
$result = pg_query($conn, $sql);
$pesan_list = [];
if ($result && pg_num_rows($result) > 0) {
    while ($row = pg_fetch_assoc($result)) {
        $pesan_list[] = $row;
    }
}
?>

<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: konsultatif)</h1>
    <p>Kelola konten halaman dan pesan konsultatif</p>
</div>

<!-- Alert untuk pesan sukses/error -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert-success">
        <?php 
        echo $_SESSION['success']; 
        unset($_SESSION['success']);
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert-danger">
        <?php 
        echo $_SESSION['error']; 
        unset($_SESSION['error']);
        ?>
    </div>
<?php endif; ?>

<!-- ============================
     FORM EDIT SECTION TITLE & DESCRIPTION
=============================== -->
<div class="card">
    <form method="post" action="../proses/proses_konsultatif.php">
        <input type="hidden" name="edit_section_content" value="1">
        <fieldset>
            <legend>Judul dan Deskripsi Halaman Konsultatif</legend>
            <div class="form-group">
                <label for="section_title">Judul Halaman</label>
                <input type="text"
                       id="section_title"
                       name="section_title"
                       value="<?php echo htmlspecialchars($section_title); ?>"
                       data-autofocus="true">
            </div>
            <div class="form-group">
                <label for="section_description">Deskripsi Halaman</label>
                <textarea id="section_description"
                          name="section_description"
                          rows="4"><?php echo htmlspecialchars($section_description); ?></textarea>
            </div>
        </fieldset>

        <div class="form-group">
            <button type="submit" name="submit_section_content" class="btn-primary">Simpan Konten Halaman</button>
        </div>
    </form>
</div>

<!-- ============================
     TABEL PESAN MASUK
=============================== -->
<div class="card">
    <div class="card-header">
        <h3>Pesan Masuk Konsultatif</h3>
    </div>

    <table class="data-table" id="pesanTable">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th>Waktu Kirim</th>
                <th>Nama Pengirim</th>
                <th>Isi Pesan Singkat</th>
                <th style="width:300px;text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pesan_list)): ?>
                <tr>
                    <td colspan="5" style="text-align:center; padding:15px; color:#777;">
                        <strong>Tidak ada pesan masuk</strong>
                    </td>
                </tr>
            <?php else: ?>
                <?php 
                $no = 1;
                foreach ($pesan_list as $pesan): 
                    // Bersihkan data untuk JavaScript - FIX UNTUK PESAN PANJANG
                    $nama_clean = htmlspecialchars($pesan['nama_pengirim'], ENT_QUOTES, 'UTF-8');
                    $tanggal_clean = date('d M Y H:i', strtotime($pesan['tanggal_kirim']));
                    $pesan_clean = htmlspecialchars($pesan['isi_pesan'], ENT_QUOTES, 'UTF-8');
                    // Escape karakter newline dan lainnya untuk JavaScript
                    $pesan_js = str_replace(["\r", "\n"], ['', '\\n'], $pesan_clean);
                    $pesan_js = str_replace("'", "\\'", $pesan_js);
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $tanggal_clean; ?></td>
                    <td><?php echo $nama_clean; ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars(substr($pesan['isi_pesan'], 0, 80)); ?></strong>
                        <?php if (strlen($pesan['isi_pesan']) > 80): ?>
                            <span style="color: #666;">...</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <button type="button" class="btn-warning" 
                                onclick="lihatDetail(
                                    '<?php echo $nama_clean; ?>',
                                    '<?php echo $tanggal_clean; ?>',
                                    '<?php echo $pesan_js; ?>'
                                )">
                            Lihat Detail
                        </button>

                        <form method="POST" action="lihat_pesan.php" style="display:inline;">
                            <input type="hidden" name="hapus_pesan" value="1">
                            <input type="hidden" name="hapus_id" value="<?php echo $pesan['id_konsultatif']; ?>">
                            <button type="submit" class="btn-danger" onclick="return confirm('Yakin hapus pesan ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <p class="mt-20 text-gray">*Klik "Lihat Detail" untuk membaca pesan secara lengkap.</p>
</div>

<!-- ============================
     MODAL UNTUK LIHAT DETAIL PESAN
=============================== -->
<div id="modalDetail" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeDetailModal()">&times;</span>
        <h3>Detail Pesan</h3>
        <div id="detailContent">
            <!-- Konten detail akan diisi oleh JavaScript -->
        </div>
        <div class="form-group" style="margin-top: 20px; text-align: center;">
            <button type="button" class="btn-secondary" onclick="closeDetailModal()">Tutup</button>
        </div>
    </div>
</div>

<!-- ============================
     SCRIPT JS
=============================== -->
<script>
// FUNGSI UNTUK LIHAT DETAIL PESAN
function lihatDetail(nama, tanggal, pesan) {
    const detailContent = document.getElementById('detailContent');
    
    // Restore newlines untuk tampilan yang proper
    const pesanWithNewlines = pesan.replace(/\\n/g, '\n');
    
    detailContent.innerHTML = `
        <div class="message-detail">
            <div class="detail-item">
                <strong>Nama Pengirim:</strong> ${nama}
            </div>
            <div class="detail-item">
                <strong>Tanggal Kirim:</strong> ${tanggal}
            </div>
            <div class="detail-item">
                <strong>Isi Pesan:</strong>
            </div>
            <div class="message-content">
                ${pesanWithNewlines}
            </div>
        </div>
    `;
    document.getElementById('modalDetail').style.display = 'block';
}

function closeDetailModal() {
    document.getElementById('modalDetail').style.display = 'none';
}

// Close modal ketika klik di luar modal
window.onclick = function(event) {
    const modal = document.getElementById('modalDetail');
    if (event.target == modal) {
        closeDetailModal();
    }
}

// Fungsi untuk escape HTML (tambahan safety)
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
</script>

<style>
.message-detail {
    line-height: 1.6;
}

.detail-item {
    margin-bottom: 10px;
}

.message-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    margin-top: 10px;
    border-left: 4px solid var(--accent-yellow);
    white-space: pre-line;
    font-family: inherit;
    max-height: 400px;
    overflow-y: auto;
    padding-bottom: 27px;
    padding-top: 0px;
}

.col-no { width: 60px; text-align: center; }
</style>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>