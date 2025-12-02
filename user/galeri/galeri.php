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
    WHERE p.nama = 'galeri_galeri' AND pc.content_key = 'section_title'
    LIMIT 1");
$judulGaleri = pg_fetch_assoc($qJudulGaleri)['content_value'] ?? 'Galeri';

// Ambil data Deskripsi
$qDeskripsiGaleri = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'galeri_galeri' AND pc.content_key = 'section_description'
    LIMIT 1");
$deskripsiGaleri = pg_fetch_assoc($qDeskripsiGaleri)['content_value'] ?? 'Deskripsi Galeri belum ditambahkan.';

// PAGINATION SETUP
$items_per_page = 8; // Sesuaikan dengan kebutuhan
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($current_page - 1) * $items_per_page;

// Hitung total data
$qTotal = pg_query($conn, "SELECT COUNT(*) as total FROM galeri");
$totalData = pg_fetch_assoc($qTotal)['total'];
$totalPages = ceil($totalData / $items_per_page);

// Ambil data Galeri dengan pagination
$qGaleri = pg_query($conn, "
    SELECT * 
    FROM galeri
    ORDER BY tanggal DESC
    LIMIT $items_per_page OFFSET $offset");

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
        <div class="section-header animate-on-scroll">
            <h2><?= nl2br(htmlspecialchars($judulGaleri)); ?></h2>
            <p><?= nl2br(htmlspecialchars($deskripsiGaleri)); ?></p>
        </div>

        <?php if (empty($posts)): ?>
            <p class="text-center text-muted animate-on-scroll">Belum ada dokumentasi galeri.</p>
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

        <!-- PAGINATION -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?page=<?= $current_page - 1 ?>" class="btn-pagination">&lt;</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == 1 || $i == $totalPages || ($i >= $current_page - 2 && $i <= $current_page + 2)): ?>
                    <a href="?page=<?= $i ?>" class="btn-pagination <?= $i == $current_page ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php elseif ($i == $current_page - 3 || $i == $current_page + 3): ?>
                    <span class="btn-pagination">...</span>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($current_page < $totalPages): ?>
                <a href="?page=<?= $current_page + 1 ?>" class="btn-pagination">&gt;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>