<?php
// ====== 1. LOAD BASE URL & KONEKSI ======
$baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';

$configPath = __DIR__ . '/../config/koneksi.php';
if (file_exists($configPath)) {
    require_once $configPath;
}

if (!isset($conn)) {
    die("Koneksi database tidak ditemukan.");
}

// ====== 2. AMBIL DATA BANNER DARI DB ======
$titleBanner    = null;
$subheadlineBanner = null;
$backgroundBanner      = null;

// Ambil title_banner
$qTitleBanner = pg_query($conn,
    "SELECT setting_value FROM settings WHERE setting_name = 'title_banner' "
);
if ($qTitleBanner && pg_num_rows($qTitleBanner) > 0) {
    $titleBanner = pg_fetch_assoc($qTitleBanner)['setting_value'];
}

//Ambil subheadline_banner
$qSubheadlineBanner = pg_query($conn,
    "SELECT setting_value FROM settings WHERE setting_name = 'subheadline_banner' "
);
if ($qSubheadlineBanner && pg_num_rows($qSubheadlineBanner) > 0) {
    $subheadlineBanner = pg_fetch_assoc($qSubheadlineBanner)['setting_value'];
}

// Ambil background_banner
$qBackgroundBanner = pg_query($conn,
    "SELECT setting_value FROM settings WHERE setting_name = 'image_banner' "
);
if ($qBackgroundBanner && pg_num_rows($qBackgroundBanner) > 0) {
    $backgroundBanner = pg_fetch_assoc($qBackgroundBanner)['setting_value'];
}

// Jika tidak ada di DB, gunakan default
$titleBanner       = $titleBanner ?: "Network & Cyber Security Laboratory";
$subheadlineBanner = $subheadlineBanner ?: "Innovating in Network Security & Cyber Defense";
if (!empty($backgroundBanner)) {
    // Ada file dari DB
    $backgroundBanner = $baseUrl . "/uploads/banner/" . $backgroundBanner;
} else {
    // Tidak ada → pakai default
    $backgroundBanner = $baseUrl . "/assets/site/img/beranda/default_banner.jpg";
}
?>
<section class="page-hero"
    style="background:
        url('<?php echo $backgroundBanner; ?>') center/cover no-repeat,
        linear-gradient(135deg, var(--blue-dark), var(--blue-mid));">
    <div class="container hero-content">
        <h1><?php echo htmlspecialchars($titleBanner, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><?php echo htmlspecialchars($subheadlineBanner, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
</section>


