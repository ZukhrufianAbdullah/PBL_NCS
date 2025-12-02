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
$deskripsiPengabdian = pg_fetch_assoc($qDeskripsiPengabdian)['content_value'] ?? 'Explore our commitment to community service and the impact of our projects.';

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';

$records = get_pengabdian($conn);
?>

<main class="section-gap">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2><?= nl2br(htmlspecialchars($judulPengabdian)); ?></h2>
            <p><?= nl2br(htmlspecialchars($deskripsiPengabdian)); ?></p>
        </div>
        <?php if (empty($records)): ?>
            <p class="text-center text-muted">Belum ada program pengabdian yang tercatat.</p>
        <?php else: ?>
            <div class="table-responsive">
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
                                <td><?php echo htmlspecialchars($record['tahun']); ?></td>
                                <td><?php echo htmlspecialchars($record['judul_pengabdian']); ?></td>
                                <td><?php echo htmlspecialchars($record['ketua'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($record['skema']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

