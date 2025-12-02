<?php
define('BASE_URL', '../..');
$pageTitle = 'Arsip - Penelitian';
$activePage = 'arsip-penelitian';
$pageStyles = ['arsip'];
$bannerTitle = 'Network and Cyber Security Laboratory';
$bannerSubtitle = 'Innovating in Network Security & Cyber Defense';

require_once __DIR__ . '/../../config/koneksi.php';

// Ambil data Judul dan Deskripsi dari database
$qJudulPenelitian = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'arsip_penelitian' AND pc.content_key = 'section_title'
    LIMIT 1");
$judulPenelitian = pg_fetch_assoc($qJudulPenelitian)['content_value'] ?? 'Penelitian';

$qDeskripsiPenelitian = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'arsip_penelitian' AND pc.content_key = 'section_description'
    LIMIT 1");
$deskripsiPenelitian = pg_fetch_assoc($qDeskripsiPenelitian)['content_value'] ?? 'Detailed reports and findings from our various initiatives.';

// PAGINATION SETUP
$items_per_page = 6; // 6 items total, akan tampil 3x2
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($current_page - 1) * $items_per_page;

// Hitung total data
$qTotal = pg_query($conn, "SELECT COUNT(*) as total FROM penelitian");
$totalData = pg_fetch_assoc($qTotal)['total'];
$totalPages = ceil($totalData / $items_per_page);

// Ambil data Penelitian dengan pagination
$qPenelitian = pg_query($conn, "
    SELECT * 
    FROM penelitian
    ORDER BY tahun DESC, id_penelitian DESC
    LIMIT $items_per_page OFFSET $offset");

$researches = [];
while ($row = pg_fetch_assoc($qPenelitian)) {
    $researches[] = $row;
}

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';
?>

<main class="section-gap">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2><?= nl2br(htmlspecialchars($judulPenelitian)); ?></h2>
            <p><?= nl2br(htmlspecialchars($deskripsiPenelitian)); ?></p>
        </div>
        
        <?php if (empty($researches)): ?>
            <p class="text-center text-muted">Belum ada penelitian yang dipublikasikan.</p>
        <?php else: ?>
            <!-- Grid Container dengan 3 kolom -->
            <div class="research-grid">
                <?php 
                $count = 0;
                foreach ($researches as $research): 
                    $count++;
                ?>
                    <?php
                        $fileUrl = !empty($research['media_path'])
                            ? BASE_URL . '/uploads/penelitian/' . htmlspecialchars($research['media_path'])
                            : null;
                    ?>
                    <article class="research-card <?php echo $count % 3 == 0 ? 'last-in-row' : ''; ?>">
                        <span class="year"><?php echo htmlspecialchars($research['tahun']); ?></span>
                        <h5><?php echo htmlspecialchars($research['judul_penelitian']); ?></h5>
                        <?php if (!empty($research['author_name'])): ?>
                            <small class="text-muted d-block mb-2">Penulis: <?php echo htmlspecialchars($research['author_name']); ?></small>
                        <?php endif; ?>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($research['deskripsi'] ?? '')); ?></p>
                        <?php if ($fileUrl): ?>
                            <a class="btn btn-brand btn-sm" href="<?php echo $fileUrl; ?>" target="_blank" rel="noopener">Download PDF</a>
                        <?php endif; ?>
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