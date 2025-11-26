<?php
define('BASE_URL', '../..');
$pageTitle = 'Profil - Visi & Misi';
$activePage = 'profil-visi';
$pageStyles = ['profil'];

require_once __DIR__ . '/../../config/koneksi.php';

// Ambil id_page = 'profil_visi_misi'
$sqlPage = "SELECT id_page FROM pages WHERE nama = 'profil_visi_misi' LIMIT 1";
$pageResult = pg_query($conn, $sqlPage);
$page = pg_fetch_assoc($pageResult);
$id_page = $page['id_page'];

// Ambil VISI
$sqlVisi = "SELECT content_value FROM page_content 
            WHERE id_page = $1 AND content_key = 'visi' LIMIT 1";
$visiResult = pg_query_params($conn, $sqlVisi, array($id_page));
$visi = (pg_num_rows($visiResult) > 0)
    ? pg_fetch_assoc($visiResult)['content_value']
    : "Visi belum ditambahkan.";

// Ambil MISI
$sqlMisi = "SELECT content_value FROM page_content 
            WHERE id_page = $1 AND content_key = 'misi' LIMIT 1";
$misiResult = pg_query_params($conn, $sqlMisi, array($id_page));
$misi = (pg_num_rows($misiResult) > 0)
    ? pg_fetch_assoc($misiResult)['content_value']
    : "Misi belum ditambahkan.";

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';
?>


<main class="section-gap">
    <div class="container">
        <div class="section-header">
            <h2>Visi &amp; Misi</h2>
            <p>Vision and Mission of the Network & Cyber ​​Security Laboratory</p>  
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card-basic h-100">
                    <h3>Visi</h3>
                    <p><?= nl2br($visi); ?></p>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card-basic h-100">
                    <h3>Misi</h3>
                    <p><?= nl2br($misi); ?></p>
                </div>
            </div>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>


