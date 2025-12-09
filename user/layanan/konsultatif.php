<?php
// PENTING: Tambahkan session_start() di awal file
session_start();

define('BASE_URL', '../..');
$pageTitle = 'Layanan - Konsultatif';
$activePage = 'layanan-konsultatif';
$pageStyles = ['layanan'];
$bannerTitle = 'Network and Cyber Security Laboratory';
$bannerSubtitle = 'Innovating in Network Security & Cyber Defense';

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';

// Koneksi database
$config_path = $_SERVER['DOCUMENT_ROOT'] . '/PBL_NCS/config/koneksi.php';
if (file_exists($config_path)) {
    require_once $config_path;
    
    $id_page = null;
    $res = pg_query_params($conn, "SELECT id_page FROM pages WHERE nama = $1 LIMIT 1", ['layanan_konsultatif']);
    if ($res && pg_num_rows($res) > 0) {
        $id_page = pg_fetch_result($res, 0, 'id_page');
    }
    
    $section_title = '';
    $section_description = '';
    
    if ($id_page) {
        $pc = pg_query_params($conn, "SELECT content_key, content_value FROM page_content WHERE id_page = $1", array($id_page));
        $content_data = [];
        while ($r = pg_fetch_assoc($pc)) {
            $content_data[$r['content_key']] = $r['content_value'];
        }
        
        $section_title = $content_data['section_title'] ?? 'Konsultatif';
        $section_description = $content_data['section_description'] ?? 'Deskripsi konsultatif belum ditambahkan.';
    } else {
        $section_title = 'Konsultatif';
        $section_description = 'Deskripsi konsultatif belum ditambahkan.';
    }
} else {
    $section_title = 'Konsultatif';
    $section_description = 'Leveraging academic expertise to offer specialized network and cybersecurity consulting to industry, government, and academic partners.';
}
?>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<main class="section-gap">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2><?php echo htmlspecialchars($section_title); ?></h2>
            <p><?php echo htmlspecialchars($section_description); ?></p>
        </div>
        <form class="card-basic contact-form" method="POST" action="<?php echo BASE_URL; ?>/user/proses/proses_konsultatif.php" id="konsultasiForm">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap *</label>
                <input type="text" id="nama" name="nama_pengirim" class="form-control" placeholder="Nama lengkap Anda" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="email@anda.com" required>
                <small class="text-muted">Email ini akan digunakan untuk membalas pesan Anda</small>
            </div>
            <div class="mb-3">
                <label for="pesan" class="form-label">Pesan *</label>
                <textarea id="pesan" name="isi_pesan" class="form-control" placeholder="Tuliskan kebutuhan dan pertanyaan Anda secara detail" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <small class="text-muted">* Wajib diisi</small>
            </div>
            <button type="submit" class="btn btn-brand w-auto">Kirim Pesan</button>
        </form>
    </div>
</main>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
// Fungsi untuk menampilkan notifikasi
function showAlert(type, message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
}

// Cek jika ada session alert dan tampilkan
<?php if (isset($_SESSION['alert_type']) && isset($_SESSION['alert_message'])): ?>
    showAlert('<?php echo $_SESSION["alert_type"]; ?>', '<?php echo addslashes($_SESSION["alert_message"]); ?>');
    <?php
    // Hapus session setelah ditampilkan
    unset($_SESSION['alert_type']);
    unset($_SESSION['alert_message']);
    ?>
<?php endif; ?>

// Validasi form sebelum submit
document.getElementById('konsultasiForm').addEventListener('submit', function(e) {
    const nama = document.getElementById('nama').value.trim();
    const email = document.getElementById('email').value.trim();
    const pesan = document.getElementById('pesan').value.trim();
    
    // Validasi email
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!nama || !email || !pesan) {
        e.preventDefault();
        showAlert('warning', 'Harap lengkapi semua field yang diperlukan.');
        return;
    }
    
    if (!emailPattern.test(email)) {
        e.preventDefault();
        showAlert('error', 'Format email tidak valid.');
        return;
    }
    
    // Tampilkan loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
    submitBtn.disabled = true;
    
    // Reset button setelah 3 detik (fallback jika redirect gagal)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 3000);
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>