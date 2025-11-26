<?php
$activePage = $activePage ?? 'home';

// Gunakan variabel dari header.php:
// $titleHeader → judul dari DB
// $logoHeader  → URL logo dari DB
?>

<header class="sticky-top">
    <nav class="navbar navbar-expand-lg navbar-light lab-navbar py-3">
        <div class="container">

            <!-- Logo + Judul -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo $baseUrl; ?>/user/index.php">
                <img src="<?php echo htmlspecialchars($logoHeader); ?>" 
                     alt="Logo" 
                     width="44" 
                     height="44"
                     style="object-fit: contain;">
                <span><?php echo htmlspecialchars($titleHeader); ?></span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#mainNavbar" aria-controls="mainNavbar"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link <?php echo $activePage === 'home' ? 'active' : ''; ?>"
                           href="<?php echo $baseUrl; ?>/user/index.php">Home</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo strpos($activePage, 'profil') === 0 ? 'active' : ''; ?>"
                           href="#" role="button" data-bs-toggle="dropdown">
                            Profil
                        </a>
                        <div class="dropdown-menu nav-dropdown">
                            <a class="dropdown-item" href="<?php echo $baseUrl; ?>/user/profil/visi_misi.php">Visi & Misi</a>
                            <a class="dropdown-item" href="<?php echo $baseUrl; ?>/user/profil/logo.php">Logo</a>
                            <a class="dropdown-item" href="<?php echo $baseUrl; ?>/user/profil/struktur.php">Struktur Organisasi</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo strpos($activePage, 'galeri') === 0 ? 'active' : ''; ?>"
                           href="#" role="button" data-bs-toggle="dropdown">
                            Galeri
                        </a>
                        <div class="dropdown-menu nav-dropdown">
                            <a class="dropdown-item" href="<?php echo $baseUrl; ?>/user/galeri/agenda.php">Agenda</a>
                            <a class="dropdown-item" href="<?php echo $baseUrl; ?>/user/galeri/galeri.php">Galeri Foto</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo strpos($activePage, 'arsip') === 0 ? 'active' : ''; ?>"
                           href="#" role="button" data-bs-toggle="dropdown">
                            Arsip
                        </a>
                        <div class="dropdown-menu nav-dropdown">
                            <a class="dropdown-item" href="<?php echo $baseUrl; ?>/user/arsip/penelitian.php">Penelitian</a>
                            <a class="dropdown-item" href="<?php echo $baseUrl; ?>/user/arsip/pengabdian.php">Pengabdian</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo strpos($activePage, 'layanan') === 0 ? 'active' : ''; ?>"
                           href="#" role="button" data-bs-toggle="dropdown">
                            Layanan
                        </a>
                        <div class="dropdown-menu nav-dropdown">
                            <a class="dropdown-item" href="<?php echo $baseUrl; ?>/user/layanan/sarana_prasarana.php">Sarana & Prasarana</a>
                            <a class="dropdown-item" href="<?php echo $baseUrl; ?>/user/layanan/konsultatif.php">Konsultatif</a>
                        </div>
                    </li>

                </ul>

                <!-- Tombol Admin -->
                <div class="ms-lg-3 mt-3 mt-lg-0">
                    <a href="<?php echo $baseUrl; ?>/user/login_admin.php" class="btn btn-brand btn-sm">
                        <i class="fa-solid fa-shield-halved me-1"></i> Admin
                    </a>
                </div>

            </div>
        </div>
    </nav>
</header>
