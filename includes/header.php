<?php
/**
 * Header include for Network & Cyber Security Laboratory site
 * Usage:
 *   $pageTitle = 'Custom Title';
 *   $pageDescription = 'Optional meta description';
 *   $pageKeywords = 'keyword1, keyword2';
 *   define('BASE_URL', '/web-lab/web-lab/'); // optional override before include
 *   require_once '../includes/header.php';
 */

$siteTitle = 'Network & Cyber Security Laboratory';
$title = isset($pageTitle) ? "{$pageTitle} | {$siteTitle}" : $siteTitle;
$description = $pageDescription ?? 'Laboratorium Keamanan Jaringan & Siber – informasi profil, kegiatan penelitian, pengabdian, dan layanan.';
$keywords = $pageKeywords ?? 'network security, cyber security, laboratorium, penelitian, pengabdian';
$baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
$configPath = __DIR__ . '/../config/koneksi.php';
if (file_exists($configPath)) {
    require_once $configPath;
}

$helperPath = __DIR__ . '/../app/helpers/site_content.php';
if (file_exists($helperPath)) {
    require_once $helperPath;
}
$assetBaseUrl = ($baseUrl !== '' ? $baseUrl : '') . '/assets/site';
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

$globalStyles = [
    '/css/base.css',
];

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

    <!-- Preconnect for Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Custom CSS -->
    <?php foreach ($stylesToLoad as $stylePath): ?>
        <link rel="stylesheet" href="<?php echo lab_build_asset_href($assetBaseUrl, $assetBasePath, $stylePath); ?>">
    <?php endforeach; ?>
</head>

<body>

