<?php
define('BASE_URL', '..');
$pageTitle = 'Beranda';
$activePage = 'home';
$pageStyles = ['home'];


require_once '../config/koneksi.php';

// Ambil id_page = 'home'
$sqlPage = "SELECT id_page FROM pages WHERE nama = 'home' LIMIT 1";
$pageResult = pg_query($conn, $sqlPage);
$page = pg_fetch_assoc($pageResult);
$id_page = $page['id_page'];

// Ambil deskripsi beranda
$sqlDesc = "SELECT content_value FROM page_content 
            WHERE id_page = $1 AND content_key = 'deskripsi' LIMIT 1";
$descResult = pg_query_params($conn, $sqlDesc, array($id_page));
$homeDeskripsi = pg_num_rows($descResult) > 0 
    ? pg_fetch_assoc($descResult)['content_value']
    : "Deskripsi belum ditambahkan.";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
require_once __DIR__ . '/../includes/page-hero.php';
?>

<main class="section-gap">
    <div class="container">
        <div class="intro-card">
            <p class="intro-text"><?= nl2br($homeDeskripsi) ?></p>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
