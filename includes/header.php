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

// ====== 2. AMBIL TITLE & LOGO DARI DB ======
$titleHeader = null;
$logoHeader  = null;

// Ambil title_header
$qTitle = pg_query($conn,
    "SELECT setting_value FROM settings WHERE setting_name = 'title_header' "
);

if ($qTitle && pg_num_rows($qTitle) > 0) {
    $titleHeader = pg_fetch_assoc($qTitle)['setting_value'];
}

// Ambil logo_header
$qLogo = pg_query($conn,
    "SELECT setting_value FROM settings WHERE setting_name = 'logo_header' "
);

if ($qLogo && pg_num_rows($qLogo) > 0) {
    $logoHeader = pg_fetch_assoc($qLogo)['setting_value'];
}

// Jika tidak ada di DB, gunakan default
$titleHeader = $titleHeader ?: "Network & Cyber Security Laboratory";
if (!empty($logoHeader)) {
    // Ada file dari DB
    $logoHeader = $baseUrl . "/uploads/header/" . $logoHeader;
} else {
    // Tidak ada → pakai default
    $logoHeader = $baseUrl . "/assets/site/img/logo/logo-poltek.jpg";
}

// ====== 3. FORMAT TITLE HALAMAN ======
$title = isset($pageTitle)
    ? "{$pageTitle} | {$titleHeader}"
    : $titleHeader;

$description = $pageDescription ?? 'Laboratorium Keamanan Jaringan & Siber.';
$keywords    = $pageKeywords ?? 'network security, cyber security';

// ====== 4. HELPER ASSET ======
$helperPath = __DIR__ . '/../app/helpers/site_content.php';
if (file_exists($helperPath)) {
    require_once $helperPath;
}

$assetBaseUrl  = ($baseUrl !== '' ? $baseUrl : '') . '/assets/site';
$assetBasePath = realpath(__DIR__ . '/../assets/site');

$pageStyles = $pageStyles ?? [];

if (!function_exists('lab_build_asset_href')) {
    function lab_build_asset_href(string $baseUrl, ?string $basePath, string $relative): string
    {
        $cleanRelative = '/' . ltrim($relative, '/');
        $version = '';

        if ($basePath) {
            $absolutePath = $basePath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim($relative, '/'));
            if (file_exists($absolutePath)) {
                $version = '?v=' . filemtime($absolutePath);
            }
        }

        return $baseUrl . $cleanRelative . ($version ?: '?v=' . time());
    }
}

$globalStyles = ['/css/base.css'];
$pageSpecificStyles = array_map(fn ($slug) => "/css/pages-{$slug}.css", $pageStyles);

$stylesToLoad = array_merge($globalStyles, $pageSpecificStyles);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?>" />
    <meta name="keywords" content="<?php echo htmlspecialchars($keywords, ENT_QUOTES, 'UTF-8'); ?>" />
    <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>

    <!-- Preconnect Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
          crossorigin="anonymous" />

    <!-- Custom CSS -->
    <?php foreach ($stylesToLoad as $stylePath): ?>
        <link rel="stylesheet" href="<?php echo lab_build_asset_href($assetBaseUrl, $assetBasePath, $stylePath); ?>">
    <?php endforeach; ?>
</head>

<body>