<?php
define('BASE_URL', '..');
$pageTitle = 'Beranda';
$activePage = 'home';
$pageStyles = ['home'];


require_once '../config/koneksi.php';

// Ambil deskripsi dari page_content
$qDeskripsi = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'home' AND pc.content_key = 'deskripsi'
    LIMIT 1");
$deskripsi = pg_fetch_assoc($qDeskripsi)['content_value'] ?? 'Deskripsi belum ditambahkan.';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
require_once __DIR__ . '/../includes/page-hero.php';
?>

<main class="section-gap">
    <div class="container">
        <div class="intro-card">
            <p class="intro-text"><?= nl2br($deskripsi) ?></p>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
