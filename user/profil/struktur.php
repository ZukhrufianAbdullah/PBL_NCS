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

$qJudulStruktur = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'profil_struktur' AND pc.content_key = 'section_title'
    LIMIT 1");
$judulStruktur = pg_fetch_assoc($qJudulStruktur)['content_value'] ?? 'Struktur Organisasi';

$qDeskripsiStruktur = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'profil_struktur' AND pc.content_key = 'section_description'
    LIMIT 1");
$deskripsiStruktur = pg_fetch_assoc($qDeskripsiStruktur)['content_value'] ?? 'Deskripsi struktur organisasi belum ditambahkan.';

// Ambil anggota dan pisahkan berdasarkan jabatan
$ketuaLab = [];
$peneliti = [];

$q = "SELECT a.id_anggota, a.jabatan, d.id_dosen, d.nama_dosen, d.media_path
      FROM anggota_lab a
      JOIN dosen d ON a.id_dosen = d.id_dosen
      ORDER BY d.nama_dosen ASC";
$rs = pg_query($conn, $q);
if ($rs && pg_num_rows($rs) > 0) {
    while ($r = pg_fetch_assoc($rs)) {
        if (stripos($r['jabatan'], 'ketua') !== false) {
            $ketuaLab[] = $r;
        } else {
            $peneliti[] = $r;
        }
    }
}

?>
<main class="section-gap">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2><?= nl2br($judulStruktur); ?></h2>
            <p><?= nl2br($deskripsiStruktur); ?></p>
        </div>

        <?php if (empty($ketuaLab) && empty($peneliti)): ?>
            <p class="text-center text-muted animate-on-scroll">Belum ada data struktur organisasi yang tersimpan.</p>
        <?php else: ?>
            
            <!-- Ketua Lab Section -->
            <?php if (!empty($ketuaLab)): ?>
                <div class="struktur-section ketua-section">
                    <?php foreach ($ketuaLab as $ketua): 
                        $photoPath = !empty($ketua['media_path']) 
                        ? BASE_URL . '/uploads/dosen/' . htmlspecialchars($ketua['media_path']) 
                        : BASE_URL . '/assets/site/img/struktur/default.jpg';
                    ?>
                        <div class="profile-card profile-card-ketua">
                            <img src="<?php echo $photoPath; ?>" alt="<?php echo htmlspecialchars($ketua['nama_dosen']); ?>">
                            <h5><?php echo htmlspecialchars($ketua['nama_dosen']); ?></h5>
                            <p class="text-muted mb-1"><?php echo htmlspecialchars($ketua['jabatan']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Peneliti Section -->
            <?php if (!empty($peneliti)): ?>
                <div class="struktur-section peneliti-section">
                    <div class="card-grid sm">
                        <?php foreach ($peneliti as $member): 
                            $photoPath = !empty($member['media_path']) ? BASE_URL . '/uploads/dosen/' . htmlspecialchars($member['media_path']) : BASE_URL . '/assets/site/img/struktur/default.jpg';
                        ?>
                            <div class="profile-card">
                                <img src="<?php echo $photoPath; ?>" alt="<?php echo htmlspecialchars($member['nama_dosen']); ?>">
                                <h5><?php echo htmlspecialchars($member['nama_dosen']); ?></h5>
                                <p class="text-muted mb-1"><?php echo htmlspecialchars($member['jabatan']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>