<?php
define('BASE_URL', '../..');
$pageTitle = 'Profil - Struktur Organisasi';
$activePage = 'profil-struktur';
$pageStyles = ['profil'];
$bannerTitle = 'Network and Cyber Security Laboratory';
$bannerSubtitle = 'Innovating in Network Security & Cyber Defense';

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';

// Ambil judul & deskripsi dari page_content (profil_struktur)
$id_page = null;
$res = pg_query_params($conn, "SELECT id_page FROM pages WHERE nama = $1 LIMIT 1", ['profil_struktur']);
if ($res && pg_num_rows($res) > 0) {
    $id_page = pg_fetch_result($res, 0, 'id_page');
}
$judul = '';
$deskripsi = '';
if ($id_page) {
    $pc = pg_query_params($conn, "SELECT content_key, content_value FROM page_content WHERE id_page = $1", array($id_page));
    while ($r = pg_fetch_assoc($pc)) {
        if ($r['content_key'] === 'section_title') $judul = $r['content_value'];
        if ($r['content_key'] === 'section_description') $deskripsi = $r['content_value'];
    }
}

// Ambil anggota (join)
$members = [];
$q = "SELECT a.id_anggota, a.jabatan, d.id_dosen, d.nama_dosen, d.media_path
      FROM anggota_lab a
      JOIN dosen d ON a.id_dosen = d.id_dosen
      ORDER BY d.nama_dosen ASC";
$rs = pg_query($conn, $q);
if ($rs && pg_num_rows($rs) > 0) {
    while ($r = pg_fetch_assoc($rs)) $members[] = $r;
}

?>
<main class="section-gap">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2><?php echo $judul ?: 'Struktur Organisasi'; ?></h2>
            <?php if (!empty($deskripsi)): ?>
                <p><?php echo nl2br(htmlspecialchars($deskripsi)); ?></p>
            <?php else: ?>
                <p class="text-muted">Meet the dedicated researchers and students of our laboratory.</p>
            <?php endif; ?>
        </div>

        <?php if (empty($members)): ?>
            <p class="text-center text-muted">Belum ada data struktur organisasi yang tersimpan.</p>
        <?php else: ?>
            <div class="card-grid sm">
                <?php foreach ($members as $member): 
                    $photoPath = !empty($member['media_path']) ? BASE_URL . '/uploads/dosen/' . htmlspecialchars($member['media_path']) : BASE_URL . '/uploads/dosen/default.png';
                ?>
                    <div class="profile-card">
                        <img src="<?php echo $photoPath; ?>" alt="<?php echo htmlspecialchars($member['nama_dosen']); ?>">
                        <h5><?php echo htmlspecialchars($member['nama_dosen']); ?></h5>
                        <p class="text-muted mb-1"><?php echo htmlspecialchars($member['jabatan']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
