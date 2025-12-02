<?php
define('BASE_URL', '..');
$pageTitle = 'Login Admin';
$activePage = 'login';

$siteTitle = 'Network & Cyber Security Laboratory';
$title = "{$pageTitle} | {$siteTitle}";
$description = 'Halaman login admin untuk Network & Cyber Security Laboratory';
$baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
$assetBaseUrl = ($baseUrl !== '' ? $baseUrl : '') . '/assets/site';
$assetBasePath = realpath(__DIR__ . '/../assets/site');

if (!function_exists('lab_login_asset_href')) {
    function lab_login_asset_href(string $baseUrl, ?string $basePath, string $relative): string
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

$loginStyles = ['/css/base.css', '/css/pages-auth.css'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?>" />
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
    <?php foreach ($loginStyles as $stylePath): ?>
        <link rel="stylesheet" href="<?php echo lab_login_asset_href($assetBaseUrl, $assetBasePath, $stylePath); ?>">
    <?php endforeach; ?>
</head>
<body class="login-body">

<div class="login-page">
    <div class="container">
        <div class="login-container">
            <!-- Left Side: Branding -->
            <div class="login-branding">
                <div class="brand-content">
                    <h1>
                        laboratorium<br>
                        Network &<br>
                        Cyber Security
                    </h1>
                    <div class="password-box">
                        *****
                    </div>
                    <div class="security-icons">
                        <div class="icon-wrapper">
                            <svg class="icon-padlock" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 10V8C6 5.79086 7.79086 4 10 4H14C16.2091 4 18 5.79086 18 8V10M6 10H4C2.89543 10 2 10.8954 2 12V20C2 21.1046 2.89543 22 4 22H20C21.1046 22 22 21.1046 22 20V12C22 10.8954 21.1046 10 20 10H18M6 10H18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="icon-wrapper">
                            <svg class="icon-shield" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L4 5V11C4 16.55 7.16 21.74 12 23C16.84 21.74 20 16.55 20 11V5L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Login Form -->
            <div class="login-form-section">
                <h2>Log In</h2>
                <form class="login-form" method="POST" action="<?php echo $baseUrl; ?>/admin/proses/proses_login.php">
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control" 
                            placeholder="nama@labncs.id" 
                            required 
                            autocomplete="email"
                        >
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-wrapper">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-control" 
                                placeholder="Masukan Password Disini" 
                                required 
                                autocomplete="current-password"
                            >
                            <button 
                                type="button" 
                                class="password-toggle" 
                                id="togglePassword" 
                                aria-label="Toggle password visibility"
                            >
                                <i class="fa-solid fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn-login">Log In</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (togglePassword && passwordInput && eyeIcon) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    }
});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- Custom JS -->
<script src="<?php echo $baseUrl; ?>/assets/site/js/main.js?v=<?php echo time(); ?>"></script>
</body>
</html>

