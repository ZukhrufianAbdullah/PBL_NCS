<?php
define('BASE_URL', '../..');
$pageTitle = 'Layanan - Sarana & Prasarana';
$activePage = 'layanan-sarana';
$pageStyles = ['layanan'];
require_once __DIR__ . '/../../config/koneksi.php';

// Ambil data judul
$qJudulSarana = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'layanan_sarana' AND pc.content_key = 'section_title'
    LIMIT 1");
$judulSarana = pg_fetch_assoc($qJudulSarana)['content_value'] ?? 'Sarana & Prasarana';

// Ambil data deskripsi
$qDeskripsiSarana = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'layanan_sarana' AND pc.content_key = 'section_description'
    LIMIT 1");
$deskripsiSarana = pg_fetch_assoc($qDeskripsiSarana)['content_value'] ?? 'Deskripsi sarana & prasarana belum ditambahkan.';

// PAGINATION SETUP - sama seperti galeri
$items_per_page = 6; // 8 item per halaman
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($current_page - 1) * $items_per_page;

// Hitung total data
$qTotal = pg_query($conn, "SELECT COUNT(*) as total FROM sarana");
$totalData = pg_fetch_assoc($qTotal)['total'];
$totalPages = ceil($totalData / $items_per_page);

// Ambil data Sarana dengan pagination
$qSarana = pg_query($conn, "
    SELECT * 
    FROM sarana
    ORDER BY nama_sarana ASC
    LIMIT $items_per_page OFFSET $offset");

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
        <div class="section-header animate-on-scroll">
            <h2><?= nl2br(htmlspecialchars($judulSarana)); ?></h2>
            <p><?= nl2br(htmlspecialchars($deskripsiSarana)); ?></p>
        </div>
        
        <?php if (empty($posts)): ?>
            <p class="text-center text-muted animate-on-scroll">Belum ada data sarana & prasarana.</p>
        <?php else: ?>
            <div class="card-grid">
                <?php 
                $count = 0;
                foreach ($posts as $post): 
                    $count++;
                ?>
                    <?php
                        $imagePath = BASE_URL . '/uploads/sarana/' . htmlspecialchars($post['media_path']);
                    ?>
                    <article class="card-basic facility-card <?php echo $count % 4 == 0 ? 'last-in-row' : ''; ?>">
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($post['nama_sarana']); ?>">
                        <h5><?php echo htmlspecialchars($post['nama_sarana']); ?></h5>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- PAGINATION - sama seperti galeri -->
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