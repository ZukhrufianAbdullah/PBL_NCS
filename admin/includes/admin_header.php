<?php
require_once __DIR__ . '/admin_bootstrap.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - NCS Lab</title>
    <?php foreach ($coreStyleFiles as $styleFile): ?>
        <link rel="stylesheet" href="<?php echo $assetAdminBase; ?>/css/<?php echo $styleFile; ?>">
    <?php endforeach; ?>
    <?php foreach ($adminPageStyles as $styleKey): ?>
        <?php if (isset($optionalStyleMap[$styleKey])): ?>
            <link rel="stylesheet" href="<?php echo $assetAdminBase; ?>/css/<?php echo $optionalStyleMap[$styleKey]; ?>">
        <?php endif; ?>
    <?php endforeach; ?>
</head>
<body>
    <div class="dashboard-shell">
        <?php include __DIR__ . '/sidebar.php'; ?>
        <div class="content">

