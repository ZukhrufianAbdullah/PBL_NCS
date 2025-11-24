<?php
define('BASE_URL', '../..');
$pageTitle = 'Layanan - Sarana & Prasarana';
$activePage = 'layanan-sarana';
$pageStyles = ['layanan'];
$bannerTitle = 'Network and Cyber Security Laboratory';
$bannerSubtitle = 'Innovating in Network Security & Cyber Defense';

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';

$facilities = get_sarana_items($conn);
?>

<main class="section-gap">
    <div class="container">
        <div class="section-header">
            <h2>Sarana &amp; Prasarana</h2>
            <p>Explore our state-of-the-art resources and their role in supporting cutting-edge research.</p>
        </div>
        <?php if (empty($facilities)): ?>
            <p class="text-center text-muted">Belum ada data sarana & prasarana.</p>
        <?php else: ?>
            <div class="card-grid">
                <?php foreach ($facilities as $facility): ?>
                    <?php
                        $imagePath = !empty($facility['media_path'])
                            ? BASE_URL . '/uploads/sarana/' . htmlspecialchars($facility['media_path'])
                            : BASE_URL . '/assets/site/img/logo/lab-logo.svg';
                    ?>
                    <article class="card-basic facility-card">
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($facility['nama_sarana']); ?>">
                        <h5><?php echo htmlspecialchars($facility['nama_sarana']); ?></h5>
                        <p class="text-muted mb-0">Fasilitas terdaftar di laboratorium.</p>
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

