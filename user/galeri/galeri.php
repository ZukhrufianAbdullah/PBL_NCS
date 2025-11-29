<?php
define('BASE_URL', '../..');
$pageTitle = 'Galeri - Galeri';
$activePage = 'galeri-galeri';
$pageStyles = ['galeri'];
require_once __DIR__ . '/../../config/koneksi.php';

// Ambil data Judul
$qJudulGaleri = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'galeri_galeri' AND pc.content_key = 'judul_galeri'
    LIMIT 1");
$judulGaleri = pg_fetch_assoc($qJudulGaleri)['content_value'] ?? '';

// Ambil data Deskripsi
$qDeskripsiGaleri = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'galeri_galeri' AND pc.content_key = 'deskripsi_galeri'
    LIMIT 1");
$deskripsiGaleri = pg_fetch_assoc($qDeskripsiGaleri)['content_value'] ?? '';

// Ambil data Galeri (DATA DATABASE)
$qGaleri = pg_query($conn, "
    SELECT * 
    FROM galeri
    ORDER BY tanggal ASC");

// Ubah query menjadi array PHP
$posts = [];
while ($row = pg_fetch_assoc($qGaleri)) {
    $posts[] = $row;
}

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';
?>
<main class="section-gap">
    <div class="container">
        <div class="section-header">
            <h2><?= nl2br($judulGaleri); ?></h2>
            <p><?= nl2br($deskripsiGaleri); ?></p>
        </div>

        <?php if (empty($posts)): ?>
            <p class="text-center text-muted">Belum ada dokumentasi galeri.</p>

        <?php else: ?>
            <div class="card-grid">
                <?php foreach ($posts as $post): ?>
                    <?php
                    $imagePath = BASE_URL . '/uploads/galeri/' . htmlspecialchars($post['media_path']);
                    $dateLabel = date('d M Y', strtotime($post['tanggal']));
                    ?>
                    <article class="article-card">
                        <img src="<?= $imagePath ?>"
                            alt="<?= htmlspecialchars($post['judul']) ?>">
                        <div class="card-body">
                            <span><?= htmlspecialchars($dateLabel) ?></span>
                            <h5><?= htmlspecialchars($post['judul']) ?></h5>
                            <p class="text-muted mb-0">
                                <?= nl2br(htmlspecialchars($post['deskripsi'] ?? '')); ?>
                            </p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>