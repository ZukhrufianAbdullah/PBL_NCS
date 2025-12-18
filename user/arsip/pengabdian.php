<?php
define('BASE_URL', '../..');
$pageTitle = 'Arsip - Pengabdian';
$activePage = 'arsip-pengabdian';
$pageStyles = ['arsip'];
$bannerTitle = 'Network and Cyber Security Laboratory';
$bannerSubtitle = 'Innovating in Network Security & Cyber Defense';

require_once __DIR__ . '/../../config/koneksi.php';

// Ambil data Judul dan Deskripsi dari database
$qJudulPengabdian = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'arsip_pengabdian' AND pc.content_key = 'section_title'
    LIMIT 1");
$judulPengabdian = pg_fetch_assoc($qJudulPengabdian)['content_value'] ?? 'Pengabdian';

$qDeskripsiPengabdian = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'arsip_pengabdian' AND pc.content_key = 'section_description'
    LIMIT 1");
$deskripsiPengabdian = pg_fetch_assoc($qDeskripsiPengabdian)['content_value'] ?? 'Deskripsi pengabdian belum ditambahkan.';

// PAGINATION SETUP
$items_per_page = 10; // 10 konten per page
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($current_page - 1) * $items_per_page;

// Hitung total data
$qTotal = pg_query($conn, "SELECT COUNT(*) as total FROM pengabdian");
$totalData = pg_fetch_assoc($qTotal)['total'];
$totalPages = ceil($totalData / $items_per_page);

// Ambil data pengabdian dengan pagination
$qPengabdian = pg_query($conn, "
    SELECT p.*, d.nama_dosen as nama_dosen
    FROM pengabdian p
    LEFT JOIN dosen d ON p.id_ketua = d.id_dosen
    ORDER BY p.tahun DESC, p.id_pengabdian DESC
    LIMIT $items_per_page OFFSET $offset");

$records = [];
while ($row = pg_fetch_assoc($qPengabdian)) {
    $records[] = $row;
}

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';
?>

<main class="section-gap">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2><?= nl2br(htmlspecialchars($judulPengabdian)); ?></h2>
            <p><?= nl2br(htmlspecialchars($deskripsiPengabdian)); ?></p>
        </div>
        
        <?php if (empty($records)): ?>
            <p class="text-center text-muted animate-on-scroll">Belum ada program pengabdian yang tercatat.</p>
        <?php else: ?>
            <div class="table-responsive animate-on-scroll">
                <table class="lab-table">
                    <thead>
                        <tr>
                            <th>Tahun</th>
                            <th>Judul Pengabdian</th>
                            <th>Ketua</th>
                            <th>Skema</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td>
                                    <span class="year-badge">
                                        <?php echo htmlspecialchars($record['tahun']); ?>
                                    </span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($record['judul_pengabdian']); ?></strong>
                                </td>
                                <td>
                                    <?php if (!empty($record['nama_dosen'])): ?>
                                        <?php echo htmlspecialchars($record['nama_dosen']); ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="scheme-badge">
                                        <?php echo htmlspecialchars($record['skema']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
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
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>