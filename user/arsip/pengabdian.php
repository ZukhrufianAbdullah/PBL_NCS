<?php
define('BASE_URL', '../..');
$pageTitle = 'Arsip - Pengabdian';
$activePage = 'arsip-pengabdian';
$pageStyles = ['arsip'];
$bannerTitle = 'Network and Cyber Security Laboratory';
$bannerSubtitle = 'Innovating in Network Security & Cyber Defense';

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';

$records = get_pengabdian($conn);
?>

<main class="section-gap">
    <div class="container">
        <div class="section-header">
            <h2>Pengabdian</h2>
            <p>Explore our commitment to community service and the impact of our projects.</p>
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

