<?php
// File: includes/footer.php

// Fungsi untuk mengambil settings
if (!function_exists('get_settings')) {
    function get_settings($conn, $setting_names) {
        $settings = [];
        
        if (empty($setting_names)) {
            return $settings;
        }
        
        // Buat placeholder untuk parameter
        $placeholders = implode(',', array_fill(0, count($setting_names), '?'));
        
        // Bangun query dengan parameter
        $query = "SELECT setting_name, setting_value FROM settings WHERE setting_name IN (" . $placeholders . ")";
        
        // Eksekusi query dengan parameter
        $result = pg_query_params($conn, $query, $setting_names);
        
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $settings[$row['setting_name']] = $row;
            }
        } else {
            error_log("Error in get_settings: " . pg_last_error($conn));
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
        } else {
            error_log("Error in get_social_links: " . pg_last_error($conn));
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

// Ambil data dari database jika koneksi tersedia
if (isset($conn) && $conn) {
    // Tentukan setting names yang akan diambil - TAMBAH 'footer_description'
    $setting_names = ['site_title', 'footer_description', 'footer_copyright', 'footer_developer_title', 'footer_credit_tim'];
    $settings = get_settings($conn, $setting_names);
    $socialLinks = get_social_links($conn);
} else {
    // Fallback jika tidak ada koneksi
    $settings = [];
    $socialLinks = [];
}

// Default values
$defaultSiteTitle = 'Network and Cyber Security Laboratory';
$defaultFooterDescription = 'Network and Cyber Security Laboratory';

// Ambil nilai dari settings
$siteTitleValue = $settings['site_title']['setting_value'] ?? $defaultSiteTitle;

// BARU: Ambil deskripsi footer terpisah, jika tidak ada gunakan default
$footerDescription = $settings['footer_description']['setting_value'] ?? $defaultFooterDescription;

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
                <!-- Judul Laboratorium -->
                <div class="footer-brand"><?php echo htmlspecialchars($siteTitleValue); ?></div>
                
                <!-- BARU: Deskripsi Footer (Terpisah dari Judul) -->
                <p class="mb-2"><?php echo htmlspecialchars($footerDescription); ?></p>
                
                <?php if (!empty($socialLinks)): ?>
                    <div class="footer-social">
                        <?php foreach ($socialLinks as $social): ?>
                            <?php 
                            $iconClass = lab_social_icon($social['platform'] ?? '');
                            $socialName = htmlspecialchars($social['nama_sosialmedia']);
                            $socialUrl = htmlspecialchars($social['url']);
                            ?>
                            <a href="<?php echo $socialUrl; ?>" target="_blank" rel="noopener noreferrer" 
                               aria-label="<?php echo $socialName; ?>" title="<?php echo $socialName; ?>">
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
        
        <!-- Teks Hak Cipta -->
        <div class="footer-bottom">
            <?php echo htmlspecialchars($footerCopy); ?>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- Custom JS -->
<script src="<?php echo $baseUrl; ?>/assets/site/js/main.js?v=<?php echo time(); ?>"></script>

<script>
    // File: assets/js/animations.js
document.addEventListener('DOMContentLoaded', function() {
    // Elemen yang akan dianimasikan
    const animateElements = document.querySelectorAll('.animate-on-scroll');
    
    // Function untuk mengecek apakah elemen ada di viewport
    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;
        
        // Trigger animasi saat elemen masuk 80% dari viewport
        return (
            rect.top <= windowHeight * 0.8 &&
            rect.bottom >= 0
        );
    }
    
    // Function untuk menangani animasi scroll
    function handleScrollAnimation() {
        animateElements.forEach(element => {
            if (isInViewport(element) && !element.classList.contains('animated')) {
                // Ambil delay dari data attribute atau hitung berdasarkan posisi
                const delay = element.getAttribute('data-delay') || 0;
                
                // Tambahkan class animated dengan delay
                setTimeout(() => {
                    element.classList.add('animated');
                }, delay * 1000);
            }
        });
    }
    
    // Jalankan saat halaman dimuat
    handleScrollAnimation();
    
    // Jalankan saat scroll dengan debounce untuk performance
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(handleScrollAnimation, 50);
    });
    
    // Tambahkan efek hover pada semua card
    const cards = document.querySelectorAll('.card-basic, .profile-card, .logo-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'var(--shadow)';
        });
    });
});
</script>
</body>
</html>