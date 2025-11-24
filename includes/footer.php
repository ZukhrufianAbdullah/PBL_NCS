<?php
$defaultSiteTitle = 'Network and Cyber Security Laboratory';
$settings = isset($conn) ? get_settings($conn, ['site_title', 'footer_copyright']) : [];
$socialLinks = isset($conn) ? get_social_links($conn) : [];
$siteTitleValue = $settings['site_title']['setting_value'] ?? $defaultSiteTitle;
$footerCopy = $settings['footer_copyright']['setting_value'] ?? 'All Rights Reserved.';

if (!function_exists('lab_social_icon')) {
    function lab_social_icon(string $platform): string
    {
        $platform = strtolower($platform);
        return match ($platform) {
            'linkedin' => 'fa-brands fa-linkedin',
            'youtube' => 'fa-brands fa-youtube',
            'twitter' => 'fa-brands fa-x-twitter',
            'instagram' => 'fa-brands fa-instagram',
            'facebook' => 'fa-brands fa-facebook',
            'sinta' => 'fa-solid fa-graduation-cap',
            default => 'fa-solid fa-link',
        };
    }
}
?>

<footer class="lab-footer">
    <div class="container">
        <div class="footer-top">
            <div>
                <div class="footer-brand"><?php echo htmlspecialchars($siteTitleValue); ?></div>
                <p class="mb-2"><?php echo htmlspecialchars($siteTitleValue); ?></p>
                <?php if (!empty($socialLinks)): ?>
                    <div class="footer-social">
                        <?php foreach ($socialLinks as $social): ?>
                            <?php $iconClass = lab_social_icon($social['platform'] ?? ''); ?>
                            <a href="<?php echo htmlspecialchars($social['url']); ?>" target="_blank" rel="noopener" aria-label="<?php echo htmlspecialchars($social['nama_sosialmedia']); ?>">
                                <i class="<?php echo $iconClass; ?>"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="developer-list">
                <span>Developed by</span>
                <span>D4 Teknik Informatika</span>
                <span>Abelas Solihin</span>
                <span>Esatovin Ebenaezer Victoria</span>
                <span>Muhammad Nuril Huda</span>
                <span>Nurfinka Lailasari</span>
                <span>Zukhrufian Abdullah</span>
            </div>
        </div>
        <div class="footer-bottom">
            © <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteTitleValue); ?>. <?php echo htmlspecialchars($footerCopy); ?>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- Custom JS -->
<script src="<?php echo $baseUrl; ?>/assets/site/js/main.js?v=<?php echo time(); ?>"></script>
</body>

</html>

