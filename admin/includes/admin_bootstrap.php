<?php
if (!isset($pageTitle)) {
    $pageTitle = 'Dashboard Admin';
}

if (!isset($currentPage)) {
    $currentPage = '';
}

if (!isset($adminPageStyles) || !is_array($adminPageStyles)) {
    $adminPageStyles = [];
}

if (!isset($adminPageScripts) || !is_array($adminPageScripts)) {
    $adminPageScripts = [];
}

$adminRootPath = realpath(__DIR__ . '/..');
$scriptDirPath = realpath(dirname($_SERVER['SCRIPT_FILENAME']));

$relativePath = '';
if ($adminRootPath && $scriptDirPath && strpos($scriptDirPath, $adminRootPath) === 0) {
    $relativePath = trim(str_replace($adminRootPath, '', $scriptDirPath), DIRECTORY_SEPARATOR);
}

if ($relativePath === '') {
    $adminDirDepth = 0;
} else {
    $adminDirDepth = substr_count($relativePath, DIRECTORY_SEPARATOR) + 1;
}

$adminBasePath = $adminDirDepth === 0 ? '' : str_repeat('../', $adminDirDepth);
$projectBasePath = str_repeat('../', $adminDirDepth + 1);
$assetAdminBase = $projectBasePath . 'assets/admin';

$coreStyleFiles = ['base.css', 'layout.css', 'components.css'];

$optionalStyleMap = [
    'forms'     => 'forms.css',
    'tables'    => 'tables.css',
    'dashboard' => 'pages-dashboard.css',
];

$scriptMap = [];

