<?php
define('BASE_URL', '../..');
$pageTitle = 'Layanan - Konsultatif';
$activePage = 'layanan-konsultatif';
$pageStyles = ['layanan'];
$bannerTitle = 'Network and Cyber Security Laboratory';
$bannerSubtitle = 'Innovating in Network Security & Cyber Defense';

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';
?>

<main class="section-gap">
    <div class="container">
        <div class="section-header">
            <h2>Konsultatif</h2>
            <p>Leveraging academic expertise to offer specialized network and cybersecurity consulting to industry, government, and academic partners.</p>
        </div>
        <form class="card-basic contact-form" method="POST" action="<?php echo $baseUrl; ?>/user/proses/proses_konsultatif.php">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" id="nama" name="nama_pengirim" class="form-control" placeholder="Nama lengkap" required>
            </div>
            <div class="mb-3">
                <label for="pesan" class="form-label">Pesan</label>
                <textarea id="pesan" name="isi_pesan" class="form-control" placeholder="Tuliskan kebutuhan dan pertanyaan Anda" required></textarea>
            </div>
            <button type="submit" class="btn btn-brand w-auto">Kirim</button>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

