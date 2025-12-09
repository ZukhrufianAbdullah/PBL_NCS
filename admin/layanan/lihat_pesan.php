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
        $_SESSION['success'] = 'Pesan berhasil dihapus!';
        echo "<script>window.location.href = '../layanan/lihat_pesan.php'</script>";
        exit();
    } else {
        $_SESSION['error'] = 'Pesan gagal dihapus!';
        echo "<script>window.location.href = '../layanan/lihat_pesan.php'</script>";
        exit();
    }
}

// PROSES UPDATE STATUS PESAN
if (isset($_POST['update_status'])) {
    $id = (int)$_POST['pesan_id'];
    $status = $_POST['status'] ?? 'pending';
    
    $sql = "UPDATE konsultatif SET status = $1 WHERE id_konsultatif = $2";
    $result = pg_query_params($conn, $sql, array($status, $id));
    
    if ($result) {
        $_SESSION['success'] = 'Status pesan berhasil diperbarui!';
    } else {
        $_SESSION['error'] = 'Gagal memperbarui status pesan!';
    }
    echo "<script>window.location.href = '../layanan/lihat_pesan.php'</script>";
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
    $section_title = '';
}

// Ambil section description
$qDesc = pg_query_params($conn, 
    "SELECT content_value FROM page_content WHERE id_page = $1 AND content_key = 'section_description'",
    array($id_page)
);
if ($qDesc && pg_num_rows($qDesc) > 0) {
    $section_description = pg_fetch_assoc($qDesc)['content_value'];
} else {
    $section_description = '';
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
    <h1><?php echo $pageTitle; ?></h1>
    <p>Kelola pesan konsultatif dan balas melalui email.</p>
</div>

<!-- Alert untuk pesan sukses/error -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert-success">
        <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; ?>
        <button type="button" class="close-alert" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert-danger">
        <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; ?>
        <button type="button" class="close-alert" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
    <?php unset($_SESSION['error']); ?>
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
                       placeholder="Masukkan judul utama konsultatif"
                       value="<?php echo htmlspecialchars($section_title); ?>"
                       data-autofocus="true">
            </div>
            <div class="form-group">
                <label for="section_description">Deskripsi Halaman</label>
                <textarea id="section_description"
                          name="section_description"
                          rows="4"
                          placeholder="Masukkan deskripsi singkat konsultatif"><?php echo htmlspecialchars($section_description); ?></textarea>
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
        <div class="status-filter">
            <button class="btn-secondary btn-sm" onclick="filterMessages('all')">Semua</button>
            <button class="btn-warning btn-sm" onclick="filterMessages('pending')">Pending</button>
            <button class="btn-success btn-sm" onclick="filterMessages('replied')">Sudah Dibalas</button>
            <button class="btn-danger btn-sm" onclick="filterMessages('archived')">Diarsipkan</button>
        </div>
    </div>

    <table class="data-table" id="pesanTable">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th>Waktu Kirim</th>
                <th>Nama Pengirim</th>
                <th>Email</th>
                <th>Status</th>
                <th>Isi Pesan Singkat</th>
                <th style="width:350px;text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pesan_list)): ?>
                <tr>
                    <td colspan="7" style="text-align:center; padding:15px; color:#777;">
                        <strong>Tidak ada pesan masuk</strong>
                    </td>
                </tr>
            <?php else: ?>
                <?php 
                $no = 1;
                    foreach ($pesan_list as $pesan): 
                    // Bersihkan data untuk JavaScript
                    $nama_clean = htmlspecialchars($pesan['nama_pengirim'], ENT_QUOTES, 'UTF-8');
                    
                    // EMAIL: Hapus karakter < > jika ada
                    $email_raw = $pesan['email'] ?? '';
                    
                    // Untuk tampilan HTML (clean version)
                    $email_display = $email_raw;
                    // Hapus format <email> jika ada
                    if (strpos($email_display, '<') !== false && strpos($email_display, '>') !== false) {
                        // Pattern untuk mengekstrak email dari format "Name <email@domain.com>"
                        if (preg_match('/<([^>]+)>/', $email_display, $matches)) {
                            $email_display = $matches[1];
                        }
                    }
                    $email_display = htmlspecialchars($email_display, ENT_QUOTES, 'UTF-8');
                    
                    // Untuk JavaScript (bersih tanpa HTML entities)
                    $email_js = $email_raw;
                    // Hapus format <email> jika ada
                    if (strpos($email_js, '<') !== false && strpos($email_js, '>') !== false) {
                        if (preg_match('/<([^>]+)>/', $email_js, $matches)) {
                            $email_js = $matches[1];
                        }
                    }
                    // Escape single quote untuk JavaScript
                    $email_js = str_replace("'", "\\'", $email_js);
                    
                    // Untuk fungsi lihatDetail di JavaScript (dengan format asli)
                    $email_detail = htmlspecialchars($email_raw, ENT_QUOTES, 'UTF-8');
                    
                    $tanggal_clean = date('d M Y H:i', strtotime($pesan['tanggal_kirim']));
                    $pesan_clean = htmlspecialchars($pesan['isi_pesan'], ENT_QUOTES, 'UTF-8');
                    
                    // Escape untuk JavaScript
                    $pesan_js = str_replace(["\r", "\n"], ['', '\\n'], $pesan_clean);
                    $pesan_js = str_replace("'", "\\'", $pesan_js);
                    
                    // Status badge
                    $status = $pesan['status'] ?? 'pending';
                    $status_class = '';
                    $status_text = '';
                    switch($status) {
                        case 'replied': $status_class = 'badge-success'; $status_text = 'Dibalas'; break;
                        case 'archived': $status_class = 'badge-secondary'; $status_text = 'Arsip'; break;
                        default: $status_class = 'badge-warning'; $status_text = 'Pending'; break;
                    }

                ?>
                <tr class="message-row" data-status="<?php echo $status; ?>">
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $tanggal_clean; ?></td>
                    <td><?php echo $nama_clean; ?></td>
                    <td>
                        <?php if (!empty($email_display)): ?>
                            <a href="mailto:<?php echo $email_display; ?>" title="Kirim email ke <?php echo $email_display; ?>">
                                <?php echo $email_display; ?>
                            </a>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                    <td>
                        <strong><?php echo htmlspecialchars(substr($pesan['isi_pesan'], 0, 60)); ?></strong>
                        <?php if (strlen($pesan['isi_pesan']) > 60): ?>
                            <span style="color: #666;">...</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <button type="button" class="btn-warning btn-sm" 
                                onclick="lihatDetail(
                                    '<?php echo $nama_clean; ?>',
                                    '<?php echo $email_detail; ?>',
                                    '<?php echo $tanggal_clean; ?>',
                                    '<?php echo $pesan_js; ?>',
                                    <?php echo $pesan['id_konsultatif']; ?>,
                                    '<?php echo $status; ?>'
                                )">
                            <i class="fas fa-eye"></i> Detail
                        </button>

                        <?php if (!empty($email_display)): ?>
                        <button type="button" class="btn-primary btn-sm" 
                                onclick="balasEmail('<?php echo $email_js; ?>', '<?php echo $nama_clean; ?>')"
                                title="Balas via Email">
                            <i class="fas fa-reply"></i> Balas
                        </button>
                        <?php endif; ?>

                        <form method="POST" action="lihat_pesan.php" style="display:inline;">
                            <input type="hidden" name="hapus_pesan" value="1">
                            <input type="hidden" name="hapus_id" value="<?php echo $pesan['id_konsultatif']; ?>">
                            <button type="submit" class="btn-danger btn-sm" onclick="return confirm('Yakin hapus pesan ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <p class="mt-20 text-gray">
        * Klik "Detail" untuk membaca pesan lengkap. <br>
        * Klik "Balas" untuk membuka email client dan membalas pesan.
    </p>
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
            <div class="action-buttons">
                <button type="button" id="btnBalas" class="btn-primary" onclick="balasFromModal()">
                    <i class="fas fa-reply"></i> Balas via Email
                </button>
                <form method="POST" action="lihat_pesan.php" style="display:inline;">
                    <input type="hidden" name="update_status" value="1">
                    <input type="hidden" name="pesan_id" id="pesanId" value="">
                    <select name="status" id="statusSelect" onchange="this.form.submit()" style="margin: 0 10px; padding: 8px 12px; border-radius: 4px;">
                        <option value="pending">Pending</option>
                        <option value="replied">Dibalas</option>
                        <option value="archived">Arsipkan</option>
                    </select>
                </form>
                <button type="button" class="btn-secondary" onclick="closeDetailModal()">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================
     SCRIPT JS
=============================== -->
<script>
// Variabel global untuk data modal
let currentEmail = '';
let currentNama = '';
let currentId = 0;
let currentStatus = '';

// FUNGSI UNTUK LIHAT DETAIL PESAN
function lihatDetail(nama, email, tanggal, pesan, id, status) {
    const detailContent = document.getElementById('detailContent');
    
    // Simpan data untuk digunakan nanti
    currentEmail = email;
    currentNama = nama;
    currentId = id;
    currentStatus = status;
    
    // Set nilai untuk form status
    document.getElementById('pesanId').value = id;
    document.getElementById('statusSelect').value = status;
    
    // Update tombol balas
    const btnBalas = document.getElementById('btnBalas');
    if (!email) {
        btnBalas.disabled = true;
        btnBalas.title = "Email tidak tersedia";
        btnBalas.innerHTML = '<i class="fas fa-ban"></i> Tidak dapat membalas';
    } else {
        btnBalas.disabled = false;
        btnBalas.title = "Balas ke " + email;
        btnBalas.innerHTML = '<i class="fas fa-reply"></i> Balas via Email';
    }
    
    // Restore newlines untuk tampilan yang proper
    const pesanWithNewlines = pesan.replace(/\\n/g, '\n');
    
    detailContent.innerHTML = `
        <div class="message-detail">
            <div class="detail-item">
                <strong>Nama Pengirim:</strong> ${nama}
            </div>
            <div class="detail-item">
                <strong>Email:</strong> 
                ${email ? `<a href="mailto:${email}">${email}</a>` : '<span class="text-muted">-</span>'}
            </div>
            <div class="detail-item">
                <strong>Tanggal Kirim:</strong> ${tanggal}
            </div>
            <div class="detail-item">
                <strong>Status:</strong> 
                <span class="badge ${getStatusBadgeClass(status)}">${getStatusText(status)}</span>
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

// Fungsi untuk membuka email client
// Fungsi untuk membuka email client dengan encoding khusus untuk Outlook
function balasEmail(email, nama) {
    if (!email) {
        alert('Email tidak tersedia untuk membalas.');
        return;
    }
    
    // Bersihkan email secara agresif
    let cleanEmail = email.trim();
    
    // Hapus semua karakter < dan > serta konten di antaranya
    cleanEmail = cleanEmail.replace(/<[^>]*>/g, '');
    
    // Hapus spasi berlebih
    cleanEmail = cleanEmail.trim();
    
    // Jika masih ada format seperti "email@gmail.com <email@gmail.com>"
    // Ambil hanya bagian pertama sebelum spasi
    if (cleanEmail.includes(' ')) {
        cleanEmail = cleanEmail.split(' ')[0].trim();
    }
    
    console.log('Email sebelum dibersihkan:', email);
    console.log('Email setelah dibersihkan:', cleanEmail);
    
    // Subject default dengan nama pengirim
    const subject = encodeURIComponent(`Balasan: Pesan Konsultatif dari ${nama}`);
    const body = encodeURIComponent(
        `Halo ${nama},\n\n` +
        `Terima kasih telah menghubungi Laboratorium Network & Cyber Security.\n\n` +
        `Berikut adalah balasan untuk pesan Anda:\n\n` +
        `[Tulis balasan Anda di sini]\n\n` +
        `Salam,\n` +
        `Admin Laboratorium NCS\n` +
        `Network & Cyber Security Laboratory`
    );
    
    // SOLUSI 1: Gunakan encodeURIComponent untuk email juga
    // Buka email client default dengan email yang sudah di-encode
    window.open(`mailto:${encodeURIComponent(cleanEmail)}?subject=${subject}&body=${body}`, '_blank');
    
    // SOLUSI ALTERNATIF 2: Jika SOLUSI 1 tidak bekerja, coba ini:
    // window.location.href = `mailto:${encodeURIComponent(cleanEmail)}?subject=${subject}&body=${body}`;
    
    // Update status menjadi replied
    if (currentId > 0) {
        updateStatus(currentId, 'replied');
    }
}

// Fungsi untuk balas dari modal
function balasFromModal() {
    balasEmail(currentEmail, currentNama);
    closeDetailModal();
}

// Fungsi update status
function updateStatus(id, status) {
    fetch('update_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `pesan_id=${id}&status=${status}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh page untuk update status
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Fungsi filter pesan
function filterMessages(status) {
    const rows = document.querySelectorAll('.message-row');
    rows.forEach(row => {
        if (status === 'all' || row.getAttribute('data-status') === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Helper functions untuk status
function getStatusBadgeClass(status) {
    switch(status) {
        case 'replied': return 'badge-success';
        case 'archived': return 'badge-secondary';
        default: return 'badge-warning';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'replied': return 'Dibalas';
        case 'archived': return 'Arsip';
        default: return 'Pending';
    }
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
</script>

<style>
/* Style tambahan untuk konsultatif */
.status-filter {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    flex-wrap: wrap;
}

.status-filter button {
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 0.85rem;
}

.action-buttons {
    display: flex;
    gap: 10px;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
}

.message-detail {
    line-height: 1.6;
}

.detail-item {
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid #eee;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-item strong {
    display: inline-block;
    width: 140px;
    color: #333;
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
    line-height: 1.6;
}

.badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

.badge-success { background-color: #28a745; color: white; }
.badge-warning { background-color: #ffc107; color: #333; }
.badge-secondary { background-color: #6c757d; color: white; }

.col-no { width: 60px; text-align: center; }

.close-alert {
    background: none;
    border: none;
    font-size: 20px;
    color: inherit;
    cursor: pointer;
    float: right;
    margin-left: 15px;
}

.alert-success, .alert-danger {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    margin-bottom: 20px;
    border-radius: 6px;
}

.alert-success i, .alert-danger i {
    margin-right: 10px;
}
</style>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>