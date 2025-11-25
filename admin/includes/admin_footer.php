        </div>
    </div>
    <script src="<?php echo $assetAdminBase; ?>/js/sidebar.js"></script>
    <script src="<?php echo $assetAdminBase; ?>/js/admin-core.js"></script>
    <?php foreach ($adminPageScripts as $scriptKey): ?>
        <?php if (isset($scriptMap[$scriptKey])): ?>
            <script src="<?php echo $assetAdminBase; ?>/js/<?php echo $scriptMap[$scriptKey]; ?>"></script>
        <?php endif; ?>
    <?php endforeach; ?>
</body>
</html>

