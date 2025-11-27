<?php
// File: includes/footer.php

// Fungsi untuk mengambil settings
if (!function_exists('get_settings')) {
    function get_settings($conn, $setting_names) {
        $settings = [];
        $placeholders = implode(',', array_fill(0, count($setting_names), '?'));
        
        $query = "SELECT setting_name, setting_value FROM settings WHERE setting_name IN (" . implode(',', array_fill(0, count($setting_names), '?')) . ")";
        
        $result = pg_query_params($conn, $query, $setting_names);
        
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $settings[$row['setting_name']] = $row;
            }
        }
        return $settings;
    }
}

// Fungsi untuk mengambil social links
if (!function_exists('get_social_links')) {
    function get_social_links($conn) {
        $social_links = [];
        $query = "SELECT * FROM sosial_media ORDER BY id_sosialmedia";
        $result = pg_query($conn, $query);
        
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $social_links[] = $row;
            }
        }
        return $social_links;
    }
}

// Fungsi untuk social icon
if (!function_exists('lab_social_icon')) {
    function lab_social_icon(string $platform): string {
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

// Ambil data
$defaultSiteTitle = 'Network and Cyber Security Laboratory';
$settings = isset($conn) ? get_settings($conn, ['site_title', 'footer_copyright', 'footer_developer_title', 'footer_credit_tim']) : [];
$socialLinks = isset($conn) ? get_social_links($conn) : [];

// PERBAIKAN: Ambil copyright text LENGKAP dari database
$siteTitleValue = $settings['site_title']['setting_value'] ?? $defaultSiteTitle;
$footerCopy = $settings['footer_copyright']['setting_value'] ?? '© 2025 Network and Cyber Security Laboratory. All Rights Reserved.';
$developerTitle = $settings['footer_developer_title']['setting_value'] ?? 'Developed by';
$creditTim = $settings['footer_credit_tim']['setting_value'] ?? "D4 Teknik Informatika\nAbelas Solihin\nEsatovin Ebenaezer Victoria\nMuhammad Nuril Huda\nNurfinka Lailasari\nZukhrufian Abdullah";

// Parse credit tim menjadi array
$creditLines = explode("\n", $creditTim);
$creditLines = array_map('trim', $creditLines);
$creditLines = array_filter($creditLines);
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
                <span><?php echo htmlspecialchars($developerTitle); ?></span>
                <?php foreach ($creditLines as $line): ?>
                    <span><?php echo htmlspecialchars($line); ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- PERBAIKAN: Tampilkan copyright text LENGKAP dari database -->
        <div class="footer-bottom">
            <?php echo htmlspecialchars($footerCopy); ?>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- Custom JS -->
<script src="<?php echo $baseUrl; ?>/assets/site/js/main.js?v=<?php echo time(); ?>"></script>
</body>
</html>