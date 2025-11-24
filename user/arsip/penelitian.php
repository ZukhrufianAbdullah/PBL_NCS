<?php
define('BASE_URL', '../..');
$pageTitle = 'Arsip - Penelitian';
$activePage = 'arsip-penelitian';
$pageStyles = ['arsip'];
$bannerTitle = 'Network and Cyber Security Laboratory';
$bannerSubtitle = 'Innovating in Network Security & Cyber Defense';

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';

$researches = get_penelitian($conn);
?>

<main class="section-gap">
    <div class="container">
        <div class="section-header">
            <h2>Penelitian</h2>
            <p>Detailed reports and findings from our various initiatives.</p>
        </div>
        <?php if (empty($researches)): ?>
            <p class="text-center text-muted">Belum ada penelitian yang dipublikasikan.</p>
        <?php else: ?>
            <div class="card-grid">
                <?php foreach ($researches as $research): ?>
                    <?php
                        $fileUrl = !empty($research['media_path'])
                            ? BASE_URL . '/uploads/penelitian/' . htmlspecialchars($research['media_path'])
                            : null;
                    ?>
                    <article class="research-card">
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

