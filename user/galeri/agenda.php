<?php
define('BASE_URL', '../..');
$pageTitle = 'Galeri - Agenda';
$activePage = 'galeri-agenda';
$pageStyles = ['galeri'];
$bannerTitle = 'Network and Cyber Security Laboratory';
$bannerSubtitle = 'Innovating in Network Security & Cyber Defense';

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';
require_once __DIR__ . '/../../app/helpers/agenda_helper.php';

// PAGINATION
$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

// Ambil agenda berdasarkan limit paging
$agendaItems = get_agenda_paginated($conn, $limit, $offset);

// Hitung total agenda
$totalAgenda = get_agenda_count($conn);
$totalPages = ceil($totalAgenda / $limit);

?>
 
<main class="section-gap">
    <div class="container">
        <div class="section-header">
            <h2>Agenda</h2>
            <p>Explore our upcoming commitments and the impact of our projects.</p>
        </div>
        <?php if (empty($agendaItems)): ?>
            <p class="text-center text-muted">Belum ada agenda terbaru.</p>
        <?php else: ?>
            <div class="card-grid sm">
                <?php foreach ($agendaItems as $item): ?>
                    <?php
                        $dateLabel = $item['tanggal_agenda']
                            ? date('d M', strtotime($item['tanggal_agenda']))
                            : 'TBA';
                    ?>
                    <article class="agenda-card">
                        <span class="date-pill"><?php echo htmlspecialchars($dateLabel); ?></span>
                        <h5><?php echo htmlspecialchars($item['judul_agenda']); ?></h5>
                        <p class="text-muted mb-0"><?php echo nl2br(htmlspecialchars($item['deskripsi'] ?? '')); ?></p>
                        <small class="text-muted d-block mt-2"><?php echo $item['status'] ? 'Aktif' : 'Selesai'; ?></small>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>" class="btn-pagination"><</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>"
                class="btn-pagination <?php echo $i == $page ? 'active' : ''; ?>">
                <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>" class="btn-pagination">></a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

