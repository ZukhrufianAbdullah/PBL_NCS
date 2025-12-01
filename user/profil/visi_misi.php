<?php
define('BASE_URL', '../..');
$pageTitle = 'Profil - Visi & Misi';
$activePage = 'profil-visi';
$pageStyles = ['profil'];

require_once __DIR__ . '/../../config/koneksi.php';

// Ambil data judul
$qJudulVisiMisi = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'profil_visi_misi' AND pc.content_key = 'section_title'
    LIMIT 1");
$judulVisiMisi = pg_fetch_assoc($qJudulVisiMisi)['content_value'] ?? 'VISI & MISI';

// Ambil data deskripsi
$qDeskripsiVisiMisi = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'profil_visi_misi' AND pc.content_key = 'section_description'
    LIMIT 1");
$deskripsiVisiMisi = pg_fetch_assoc($qDeskripsiVisiMisi)['content_value'] ?? 'Deskripsi visi misi belum ditambahkan.';

//Ambil data visi
$qVisi = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'profil_visi_misi' AND pc.content_key = 'visi'
    LIMIT 1");
$visi = pg_fetch_assoc($qVisi)['content_value'] ?? 'Visi belum ditambahkan.';

//Ambil data misi
$qMisi = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'profil_visi_misi' AND pc.content_key = 'misi'
    LIMIT 1");
$misi = pg_fetch_assoc($qMisi)['content_value'] ?? 'Misi belum ditambahkan.';

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';
?>


<main class="section-gap">
    <div class="container">
        <div class="section-header">
            <h2><?= nl2br($judulVisiMisi); ?></h2>
            <p><?= nl2br($deskripsiVisiMisi); ?></p>  
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


