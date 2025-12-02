<?php
define('BASE_URL', '../..');
$pageTitle = 'Profil - Logo';
$activePage = 'profil-logo';
$pageStyles = ['profil'];
require_once __DIR__ . '/../../config/koneksi.php';

$qJudulLogo = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'profil_logo' AND pc.content_key = 'section_title'
    LIMIT 1");
$judulLogo = pg_fetch_assoc($qJudulLogo)['content_value'] ?? '';

$qDeskripsiLogo = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'profil_logo' AND pc.content_key = 'section_description'
    LIMIT 1");
$deskripsiLogo = pg_fetch_assoc($qDeskripsiLogo)['content_value'] ?? '';

// Ambil data Logo 1
$qLogo1 = pg_query($conn, "
    SELECT media_path
    FROM logo 
    WHERE nama_logo = 'logo_utama'
    LIMIT 1");
$hasilLogo1 = pg_fetch_assoc($qLogo1);
$logo1 = (!empty($hasilLogo1['media_path']))
    ? BASE_URL . '/uploads/logo/' . $hasilLogo1['media_path']
    : BASE_URL . '/assets/site/img/logo/logo-jti.svg';

// Ambil data Logo 2
$qLogo2 = pg_query($conn, "
    SELECT media_path
    FROM logo
    WHERE nama_logo = 'logo_deskripsi'
    LIMIT 1");
$hasilLogo2 = pg_fetch_assoc($qLogo2);
$logo2 = (!empty($hasilLogo2['media_path']))
    ? BASE_URL . '/uploads/logo/' . $hasilLogo2['media_path']
    : BASE_URL . '/assets/site/img/logo/logo-polinema.svg';

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';

$logos = get_logos($conn);
?>

<main class="section-gap">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2><?= nl2br($judulLogo); ?></h2>
            <p><?= nl2br($deskripsiLogo); ?></p>
        </div>
        <div class="card-grid sm">

            <!-- Logo Utama -->
                <div class="card-basic logo-card text-center">
                    <img src="<?= htmlspecialchars($logo1); ?>" 
                        alt="Logo Utama">
                    <h5>Logo Utama</h5>
                    <p class="text-muted mb-0">Logo utama laboratorium.</p>
                </div>

            <!-- Logo Deskripsi -->
                <div class="card-basic logo-card text-center">
                    <img src="<?= htmlspecialchars($logo2); ?>" 
                        alt="Logo Deskripsi">
                    <h5>Logo Deskripsi</h5>
                    <p class="text-muted mb-0">Logo pendukung deskripsi laboratorium.</p>
                </div>

        </div>


    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

