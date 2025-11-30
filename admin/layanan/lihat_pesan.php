<?php
// File: admin/layanan/lihat_pesan.php
session_start();
$pageTitle = 'Kelola Konsultatif';
$currentPage = 'lihat_pesan';
$adminPageStyles = ['tables', 'modal'];

require_once dirname(__DIR__) . '/includes/admin_header.php';

// Koneksi database
$config_path = $_SERVER['DOCUMENT_ROOT'] . '/PBL_NCS/config/koneksi.php';
if (!file_exists($config_path)) {
    die("Database configuration file not found");
}
require_once $config_path;

// Ambil id_user dari session
$id_user = $_SESSION['id_user'] ?? 1;

// PROSES HAPUS PESAN - DIUBAH MENJADI POST
if (isset($_POST['hapus_pesan'])) {
    $id = (int)$_POST['hapus_id'];
    $sql = "DELETE FROM konsultatif WHERE id_konsultatif = $1";
    $result = pg_query_params($conn, $sql, array($id));
    
    if ($result) {
        echo "<script>alert('Pesan berhasil dihapus!'); window.location.href='lihat_pesan.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menghapus pesan: " . pg_last_error($conn) . "'); window.location.href='lihat_pesan.php';</script>";
    }
    exit();
}

// PROSES EDIT KONTEN KONSULTATIF
if (isset($_POST['edit_page_content'])) {
    $judul_page = trim($_POST['judul_page'] ?? '');
    $deskripsi_page = trim($_POST['deskripsi_page'] ?? '');
    $page_key = "layanan_konsultatif";

    // Ambil id_page
    $pg = pg_query_params($conn, "SELECT id_page FROM pages WHERE nama = $1 LIMIT 1", array($page_key));
    if (!$pg || pg_num_rows($pg) === 0) {
        echo "<script>alert('Halaman tidak ditemukan.'); window.location.href='lihat_pesan.php';</script>"; 
        exit();
    }
    $page = pg_fetch_assoc($pg);
    $id_page = $page['id_page'];

    // upsert judul
    $check = pg_query_params($conn, "SELECT id_page_content FROM page_content WHERE id_page=$1 AND content_key='judul' LIMIT 1", array($id_page));
    if (pg_num_rows($check) > 0) {
        pg_query_params($conn, "UPDATE page_content SET content_value=$1, id_user=$2 WHERE id_page=$3 AND content_key='judul'",
            array($judul_page, $id_user, $id_page));
    } else {
        pg_query_params($conn, "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user) VALUES ($1,'judul','text',$2,$3)",
            array($id_page, $judul_page, $id_user));
    }

    // upsert deskripsi
    $check2 = pg_query_params($conn, "SELECT id_page_content FROM page_content WHERE id_page=$1 AND content_key='deskripsi' LIMIT 1", array($id_page));
    if (pg_num_rows($check2) > 0) {
        pg_query_params($conn, "UPDATE page_content SET content_value=$1, id_user=$2 WHERE id_page=$3 AND content_key='deskripsi'",
            array($deskripsi_page, $id_user, $id_page));
    } else {
        pg_query_params($conn, "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user) VALUES ($1,'deskripsi','text',$2,$3)",
            array($id_page, $deskripsi_page, $id_user));
    }

    echo "<script>alert('Konten halaman Konsultatif berhasil diperbarui!'); window.location.href='lihat_pesan.php';</script>";
    exit();
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

// AMBIL KONTEN HALAMAN KONSULTATIF
$id_page = null;
$res = pg_query_params($conn, "SELECT id_page FROM pages WHERE nama = $1 LIMIT 1", ['layanan_konsultatif']);
if ($res && pg_num_rows($res) > 0) {
    $id_page = pg_fetch_result($res, 0, 'id_page');
}
$judul = '';
$deskripsi = '';
if ($id_page) {
    $pc = pg_query_params($conn, "SELECT content_key, content_value FROM page_content WHERE id_page = $1", array($id_page));
    while ($r = pg_fetch_assoc($pc)) {
        if ($r['content_key'] === 'judul') $judul = $r['content_value'];
        if ($r['content_key'] === 'deskripsi') $deskripsi = $r['content_value'];
    }
}
?>

<div class="admin-header">
    <div class="header-content">
        <h1><?php echo $pageTitle; ?></h1>
        <p>Kelola pesan masuk dan konten halaman konsultatif</p>
    </div>
    <button type="button" class="btn btn-brand" onclick="openEditContentModal()">Edit Konten Halaman</button>
</div>

<div class="card">
    <h3>Pesan Masuk Konsultatif</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Waktu Kirim</th>
                <th>Nama Pengirim</th>
                <th>Isi Pesan Singkat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pesan_list)): ?>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada pesan masuk</td>
                </tr>
            <?php else: ?>
                <?php foreach ($pesan_list as $pesan): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pesan['tanggal_kirim']); ?></td>
                        <td><?php echo htmlspecialchars($pesan['nama_pengirim']); ?></td>
                        <td><?php echo htmlspecialchars(substr($pesan['isi_pesan'], 0, 80)) . '...'; ?></td>
                        <td>
                            <button type="button" class="btn-warning" onclick="lihatDetail(
                                '<?php echo htmlspecialchars($pesan['nama_pengirim']); ?>',
                                '<?php echo htmlspecialchars($pesan['tanggal_kirim']); ?>',
                                `<?php echo addslashes($pesan['isi_pesan']); ?>`
                            )">Lihat Detail</button>
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

<!-- MODAL UNTUK LIHAT DETAIL PESAN -->
<div id="modalDetail" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeDetailModal()">&times;</span>
        <h2>Detail Pesan</h2>
        <div id="detailContent">
            <!-- Konten detail akan diisi oleh JavaScript -->
        </div>
    </div>
</div>

<!-- MODAL UNTUK EDIT KONTEN HALAMAN -->
<div id="modalEditContent" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditContentModal()">&times;</span>
        <h2>Edit Konten Halaman Konsultatif</h2>
        <form method="POST" action="lihat_pesan.php">
            <input type="hidden" name="edit_page_content" value="1">
            <div class="form-group">
                <label for="judul_page">Judul Halaman</label>
                <input type="text" id="judul_page" name="judul_page" class="form-control" 
                       value="<?php echo htmlspecialchars($judul ?: 'Konsultatif'); ?>" required>
            </div>
            <div class="form-group">
                <label for="deskripsi_page">Deskripsi Halaman</label>
                <textarea id="deskripsi_page" name="deskripsi_page" class="form-control" rows="4" required><?php echo htmlspecialchars($deskripsi ?: 'Leveraging academic expertise to offer specialized network and cybersecurity consulting to industry, government, and academic partners.'); ?></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeEditContentModal()">Batal</button>
                <button type="submit" class="btn btn-brand">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
// FUNGSI UNTUK LIHAT DETAIL PESAN
function lihatDetail(nama, tanggal, pesan) {
    const detailContent = document.getElementById('detailContent');
    detailContent.innerHTML = `
        <p><strong>Nama Pengirim:</strong> ${nama}</p>
        <p><strong>Tanggal Kirim:</strong> ${tanggal}</p>
        <p><strong>Isi Pesan:</strong></p>
        <div style="background: #f5f5f5; padding: 15px; border-radius: 5px; margin-top: 10px; white-space: pre-wrap;">
            ${pesan}
        </div>
    `;
    document.getElementById('modalDetail').style.display = 'block';
}

function closeDetailModal() {
    document.getElementById('modalDetail').style.display = 'none';
}

// FUNGSI UNTUK EDIT KONTEN
function openEditContentModal() {
    document.getElementById('modalEditContent').style.display = 'block';
}

function closeEditContentModal() {
    document.getElementById('modalEditContent').style.display = 'none';
}

// TUTUP MODAL KETIKA KLIK DI LUAR
window.onclick = function(event) {
    const modals = document.getElementsByClassName('modal');
    for (let i = 0; i < modals.length; i++) {
        if (event.target == modals[i]) {
            modals[i].style.display = 'none';
        }
    }
}
</script>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>