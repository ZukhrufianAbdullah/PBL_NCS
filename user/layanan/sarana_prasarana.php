<?php
define('BASE_URL', '../..');
$pageTitle = 'Layanan - Sarana & Prasarana';
$activePage = 'layanan-sarana';
$pageStyles = ['layanan'];
require_once __DIR__ . '/../../config/koneksi.php';

//Ambil data judul
$qJudulSarana = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'layanan_sarana' AND pc.content_key = 'judul_sarana'
    LIMIT 1");
$judulSarana = pg_fetch_assoc($qJudulSarana)['content_value'] ?? '';

// Ambil data deskripsi
$qDeskripsiSarana = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'layanan_sarana' AND pc.content_key = 'deskripsi_sarana'
    LIMIT 1");
$deskripsiSarana = pg_fetch_assoc($qDeskripsiSarana)['content_value'] ?? '';

// Ambil data Sarana
$qSarana = pg_query($conn, "
    SELECT * 
    FROM sarana
    ORDER BY nama_sarana ASC");

// Ubah query menjadi array PHP
$posts = [];
while ($row = pg_fetch_assoc($qSarana)) {
    $posts[] = $row;
}
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';
?>

<main class="section-gap">
    <div class="container">
        <div class="section-header">
            <h2><?= nl2br($judulSarana); ?></h2>
            <p><?= nl2br($deskripsiSarana); ?></p>
        </div>
        <?php if (empty($posts)): ?>
            <p class="text-center text-muted">Belum ada data sarana & prasarana.</p>
        <?php else: ?>
            <div class="card-grid">
                <?php foreach ($posts as $post): ?>
                    <?php
                        $imagePath = BASE_URL . '/uploads/sarana/' . htmlspecialchars($post['media_path']);
                    ?>
                    <article class="card-basic facility-card">
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($post['nama_sarana']); ?>">
                        <h5><?php echo htmlspecialchars($post['nama_sarana']); ?></h5>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="pagination-nav">
            <span class="active">1</span>
            <a href="#">2</a>
            <span>3</span>
            <span>...</span>
            <a href="#">&gt;</a>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

