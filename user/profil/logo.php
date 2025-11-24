<?php
define('BASE_URL', '../..');
$pageTitle = 'Profil - Logo';
$activePage = 'profil-logo';
$pageStyles = ['profil'];
$bannerTitle = 'Network and Cyber Security Laboratory';
$bannerSubtitle = 'Innovating in Network Security & Cyber Defense';

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';

$logos = get_logos($conn);
?>

<main class="section-gap">
    <div class="container">
        <div class="section-header">
            <h2>Logo</h2>
            <p>The official logos of the Network and Cyber Security Laboratory and its affiliated institutions.</p>
        </div>
        <div class="card-grid sm">
            <?php foreach ($logos as $logo): ?>
                <div class="card-basic logo-card text-center">
                    <img src="<?php echo BASE_URL . '/uploads/logo/' . htmlspecialchars($logo['media_path']); ?>" alt="<?php echo htmlspecialchars($logo['nama_logo']); ?>">
                    <h5><?php echo htmlspecialchars($logo['nama_logo']); ?></h5>
                    <p class="text-muted mb-0">Logo resmi yang terdaftar pada CMS.</p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

