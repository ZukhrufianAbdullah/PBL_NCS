<?php
define('BASE_URL', '../..');
$pageTitle = 'Galeri - Galeri';
$activePage = 'galeri-galeri';
$pageStyles = ['galeri'];
$bannerTitle = 'Network and Cyber Security Laboratory';
$bannerSubtitle = 'Innovating in Network Security & Cyber Defense';

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';

require_once __DIR__ . '/../../app/helpers/galeri_helper.php';

// Ambil halaman + pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 8;
$galeri = get_galeri_paginated($conn, $page, $limit);
$posts = $galeri['data'];           // ← ini WAJIB
$total_pages = $galeri['total_pages'];
$current_page = $galeri['current_page'];


// Ambil konten header galeri
$pageContent = get_galeri_page_content($conn);
$sectionTitle = $pageContent['section_title'] ?? 'Galeri';
$sectionDescription = $pageContent['section_description'] ??
                      'Explore our commitment to community service and the impact of our projects.';

?>
<main class="section-gap">
    <div class="container">
        <div class="section-header">
            <h2><?php echo htmlspecialchars($sectionTitle); ?></h2>
            <p><?php echo htmlspecialchars($sectionDescription); ?></p>
        </div>


        <?php if (empty($posts)): ?>
            <p class="text-center text-muted">Belum ada dokumentasi galeri.</p>

        <?php else: ?>
            <div class="card-grid">
                <?php foreach ($posts as $post): ?>
                    <?php
                        $imagePath = !empty($post['media_path'])
                            ? BASE_URL . '/uploads/galeri/' . htmlspecialchars($post['media_path'])
                            : BASE_URL . '/assets/site/img/logo/lab-logo.svg';

                        $dateLabel = $post['tanggal_kegiatan']
                            ? date('d M Y', strtotime($post['tanggal_kegiatan']))
                            : 'Dokumentasi';
                    ?>
                    <article class="article-card">
                        <img src="<?php echo $imagePath; ?>" 
                             alt="<?php echo htmlspecialchars($post['judul']); ?>">
                        <div class="card-body">
                            <span><?php echo htmlspecialchars($dateLabel); ?></span>
                            <h5><?php echo htmlspecialchars($post['judul']); ?></h5>
                            <p class="text-muted mb-0">
                                <?php echo nl2br(htmlspecialchars($post['deskripsi'] ?? '')); ?>
                            </p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- PAGINATION -->
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a class="btn-pagination" 
                       href="?page=<?php echo $current_page - 1; ?>"><</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a class="btn-pagination <?php echo ($i == $current_page) ? 'active' : ''; ?>" 
                       href="?page=<?php echo $i; ?>">
                       <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                    <a class="btn-pagination" 
                       href="?page=<?php echo $current_page + 1; ?>">></a>
                <?php endif; ?>
            </div>

        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
