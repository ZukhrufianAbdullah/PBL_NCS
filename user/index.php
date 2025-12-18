<?php
define('BASE_URL', '..');
$pageTitle = 'Beranda';
$activePage = 'home';
$pageStyles = ['home'];

require_once '../config/koneksi.php';

// Function untuk get visibility settings
function get_home_visibility($conn)
{
    $query = "SELECT pc.content_key, pc.content_value 
              FROM page_content pc
              JOIN pages p ON pc.id_page = p.id_page
              WHERE p.nama = 'home' 
              AND pc.content_key LIKE 'show_%'";
    $result = pg_query($conn, $query);

    $visibility = [];
    while ($row = pg_fetch_assoc($result)) {
        $visibility[$row['content_key']] = ($row['content_value'] === 'true');
    }
    return $visibility;
}

// Ambil visibility settings
$visibility = get_home_visibility($conn);

// Ambil deskripsi home
$qDeskripsi = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'home' AND pc.content_key = 'deskripsi'
    LIMIT 1");
$deskripsi = pg_fetch_assoc($qDeskripsi)['content_value'] ?? 'Selamat datang di laboratorium Network & Cyber Security.';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
require_once __DIR__ . '/../includes/page-hero.php';
?>

<main>
    <!-- Intro Section (Always Shown) -->
    <section class="section-gap">
        <div class="container">
            <div class="intro-card">
                <p class="intro-text"><?= nl2br(htmlspecialchars($deskripsi)) ?></p>
            </div>
        </div>
    </section>

    <?php
    // ========================================
    // SECTION 1: VISI & MISI
    // ========================================
    if ($visibility['show_visi_misi'] ?? true):

        $qJudulVisiMisi = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'profil_visi_misi' AND pc.content_key = 'section_title'
            LIMIT 1");
        $judulVisiMisi = pg_fetch_assoc($qJudulVisiMisi)['content_value'] ?? 'Visi & Misi';

        $qDeskripsiVisiMisi = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'profil_visi_misi' AND pc.content_key = 'section_description'
            LIMIT 1");
        $deskripsiVisiMisi = pg_fetch_assoc($qDeskripsiVisiMisi)['content_value'] ?? 'Deskripsi visi misi belum ditambahkan.';

        $qVisi = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'profil_visi_misi' AND pc.content_key = 'visi'
            LIMIT 1");
        $visi = pg_fetch_assoc($qVisi)['content_value'] ?? '';

        $qMisi = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'profil_visi_misi' AND pc.content_key = 'misi'
            LIMIT 1");
        $misi = pg_fetch_assoc($qMisi)['content_value'] ?? '';

        if (!empty($visi) || !empty($misi)):
    ?>
            <section class="section-gap section-alt">
                <div class="container">
                    <div class="section-header animate-on-scroll">
                        <h2><?= nl2br(htmlspecialchars($judulVisiMisi)) ?></h2>
                        <p><?= nl2br(htmlspecialchars($deskripsiVisiMisi)) ?></p>
                    </div>

                    <div class="row g-4 mb-4">
                        <?php if (!empty($visi)): ?>
                            <div class="col-lg-6">
                                <div class="card-basic h-100 animate-on-scroll">
                                    <h3 style="text-align: center;">Visi</h3>
                                    <p><?= nl2br(htmlspecialchars(substr($visi, 0, 250))) ?><?= strlen($visi) > 250 ? '...' : '' ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($misi)): ?>
                            <div class="col-lg-6">
                                <div class="card-basic h-100 animate-on-scroll">
                                    <h3 style="text-align: center;">Misi</h3>
                                    <p><?= nl2br(htmlspecialchars(substr($misi, 0, 250))) ?><?= strlen($misi) > 250 ? '...' : '' ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="text-center mt-4 animate-on-scroll">
                        <a href="<?= BASE_URL ?>/user/profil/visi_misi.php" class="btn-brand">View More</a>
                    </div>
                </div>
            </section>
    <?php endif;
    endif; ?>

    <?php
    // ========================================
    // SECTION 2: LOGO
    // ========================================
    if ($visibility['show_logo'] ?? true):

        $qJudulLogo = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'profil_logo' AND pc.content_key = 'section_title'
            LIMIT 1");
        $judulLogo = pg_fetch_assoc($qJudulLogo)['content_value'] ?? 'Logo';

        $qDeskripsiLogo = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'profil_logo' AND pc.content_key = 'section_description'
            LIMIT 1");
        $deskripsiLogo = pg_fetch_assoc($qDeskripsiLogo)['content_value'] ?? 'Deskripsi logo belum ditambahkan.';

        $qLogo1 = pg_query($conn, "SELECT media_path FROM logo WHERE nama_logo = 'logo_utama' LIMIT 1");
        $logo1 = pg_fetch_assoc($qLogo1);

        if ($logo1):
            $logo1Path = BASE_URL . '/uploads/logo/' . $logo1['media_path'];
    ?>
            <section class="section-gap">
                <div class="container">
                    <div class="section-header animate-on-scroll">
                        <h2><?= nl2br(htmlspecialchars($judulLogo)) ?></h2>
                        <p><?= nl2br(htmlspecialchars($deskripsiLogo)) ?></p>
                    </div>

                    <div class="row justify-content-center animate-on-scroll">
                        <div class="col-md-4">
                            <div class="card-basic logo-card text-center animate-on-scroll">
                                <img src="<?= htmlspecialchars($logo1Path) ?>" alt="Logo Utama">
                                <h5>Logo Utama</h5>
                                <p class="text-muted mb-0">Logo utama laboratorium</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4 animate-on-scroll">
                        <a href="<?= BASE_URL ?>/user/profil/logo.php" class="btn-brand">View More</a>
                    </div>
                </div>
            </section>
    <?php endif;
    endif; ?>

    <?php
    // ========================================
    // SECTION 3: STRUKTUR ORGANISASI
    // ========================================
    if ($visibility['show_struktur'] ?? true):

        $qJudulStruktur = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'profil_struktur' AND pc.content_key = 'section_title'
            LIMIT 1");
        $judulStruktur = pg_fetch_assoc($qJudulStruktur)['content_value'] ?? 'Struktur Organisasi';

        $qDeskripsiStruktur = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'profil_struktur' AND pc.content_key = 'section_description'
            LIMIT 1");
        $deskripsiStruktur = pg_fetch_assoc($qDeskripsiStruktur)['content_value'] ?? 'Deskripsi struktur organisasi belum ditambahkan.';

        $qStruktur = pg_query($conn, "
            SELECT a.jabatan, d.nama_dosen, d.media_path
            FROM anggota_lab a
            JOIN dosen d ON a.id_dosen = d.id_dosen
            WHERE LOWER(a.jabatan) LIKE '%ketua%' or LOWER(a.jabatan) LIKE '%kepala%'
            ORDER BY d.nama_dosen ASC
            LIMIT 1");

        $members = [];
        while ($row = pg_fetch_assoc($qStruktur)) {
            $members[] = $row;
        }

        if (!empty($members)):
    ?>
            <section class="section-gap section-alt">
                <div class="container">
                    <div class="section-header animate-on-scroll">
                        <h2><?= nl2br(htmlspecialchars($judulStruktur)) ?></h2>
                        <p><?= nl2br(htmlspecialchars($deskripsiStruktur)) ?></p>
                    </div>

                    <div class="struktur-section ketua-section animate-on-scroll">
                        <?php foreach ($members as $member):
                            $photoPath = !empty($member['media_path'])
                                ? BASE_URL . '/uploads/dosen/' . htmlspecialchars($member['media_path'])
                                : BASE_URL . '/assets/site/img/struktur/default.jpg';
                        ?>
                            <div class="profile-card profile-card-ketua">
                                <img src="<?= $photoPath ?>" alt="<?= htmlspecialchars($member['nama_dosen']) ?>">
                                <h5><?= htmlspecialchars($member['nama_dosen']) ?></h5>
                                <p class="text-muted mb-1"><?= htmlspecialchars($member['jabatan']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="text-center mt-4 animate-on-scroll">
                        <a href="<?= BASE_URL ?>/user/profil/struktur.php" class="btn-brand">View More</a>
                    </div>
                </div>
            </section>
    <?php endif;
    endif; ?>

    <?php
    // ========================================
    // SECTION 4: AGENDA
    // ========================================
    if ($visibility['show_agenda'] ?? true):

        $qJudulAgenda = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'galeri_agenda' AND pc.content_key = 'section_title'
            LIMIT 1");
        $judulAgenda = pg_fetch_assoc($qJudulAgenda)['content_value'] ?? 'Agenda';

        $qDeskripsiAgenda = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'galeri_agenda' AND pc.content_key = 'section_description'
            LIMIT 1");
        $deskripsiAgenda = pg_fetch_assoc($qDeskripsiAgenda)['content_value'] ?? 'Deskripsi agenda belum ditambahkan.';

        $qAgenda = pg_query($conn, "
            SELECT * FROM agenda
            ORDER BY tanggal DESC
            LIMIT 3");

        $agendaData = [];
        while ($row = pg_fetch_assoc($qAgenda)) {
            $agendaData[] = $row;
        }

        if (!empty($agendaData)):
    ?>
            <section class="section-gap">
                <div class="container">
                    <div class="section-header animate-on-scroll">
                        <h2><?= nl2br(htmlspecialchars($judulAgenda)) ?></h2>
                        <p><?= nl2br(htmlspecialchars($deskripsiAgenda)) ?></p>
                    </div>

                    <div class="table-responsive animate-on-scroll">
                        <table class="lab-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul Agenda</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($agendaData as $index => $row): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($row['judul']) ?></strong></td>
                                        <td><?= htmlspecialchars(substr($row['deskripsi'], 0, 100)) ?><?= strlen($row['deskripsi']) > 100 ? '...' : '' ?></td>
                                        <td>
                                            <span class="date-badge">
                                                <?= date('d M Y', strtotime($row['tanggal'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($row['status'] === 't'): ?>
                                                <span class="status-badge active">
                                                    <i class="fas fa-check-circle"></i> Aktif
                                                </span>
                                            <?php else: ?>
                                                <span class="status-badge archived">
                                                    <i class="fas fa-archive"></i> Arsip
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-4 animate-on-scroll">
                        <a href="<?= BASE_URL ?>/user/galeri/agenda.php" class="btn-brand">View More</a>
                    </div>
                </div>
            </section>
    <?php endif;
    endif; ?>

    <?php
    // ========================================
    // SECTION 5: GALERI
    // ========================================
    if ($visibility['show_galeri'] ?? true):

        $qJudulGaleri = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'galeri' AND pc.content_key = 'section_title'
            LIMIT 1");
        $judulGaleri = pg_fetch_assoc($qJudulGaleri)['content_value'] ?? 'Galeri';

        $qDeskripsiGaleri = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'galeri' AND pc.content_key = 'section_description'
            LIMIT 1");
        $deskripsiGaleri = pg_fetch_assoc($qDeskripsiGaleri)['content_value'] ?? 'Deskripsi galeri belum ditambahkan.';

        $qGaleri = pg_query($conn, "
            SELECT * FROM galeri
            ORDER BY tanggal DESC
            LIMIT 3");

        $galeriData = [];
        while ($row = pg_fetch_assoc($qGaleri)) {
            $galeriData[] = $row;
        }

        if (!empty($galeriData)):
    ?>
            <section class="section-gap section-alt">
                <div class="container">
                    <div class="section-header animate-on-scroll">
                        <h2><?= nl2br(htmlspecialchars($judulGaleri)) ?></h2>
                        <p><?= nl2br(htmlspecialchars($deskripsiGaleri)) ?></p>
                    </div>

                    <div class="card-grid sm animate-on-scroll">
                        <?php foreach ($galeriData as $post):
                            $imagePath = BASE_URL . '/uploads/galeri/' . htmlspecialchars($post['media_path']);
                            $dateLabel = date('d M Y', strtotime($post['tanggal']));
                        ?>
                            <article class="article-card">
                                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($post['judul']) ?>">
                                <div class="card-body">
                                    <span><?= htmlspecialchars($dateLabel) ?></span>
                                    <h5><?= htmlspecialchars($post['judul']) ?></h5>
                                    <p class="text-muted mb-0">
                                        <?= nl2br(htmlspecialchars(substr($post['deskripsi'] ?? '', 0, 100))) ?>
                                        <?= strlen($post['deskripsi'] ?? '') > 100 ? '...' : '' ?>
                                    </p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <div class="text-center mt-4 animate-on-scroll">
                        <a href="<?= BASE_URL ?>/user/galeri/galeri.php" class="btn-brand">View More</a>
                    </div>
                </div>
            </section>
    <?php endif;
    endif; ?>

    <?php
    // ========================================
    // SECTION 6: PENELITIAN
    // ========================================
    if ($visibility['show_penelitian'] ?? true):

        $qJudulPenelitian = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'penelitian' AND pc.content_key = 'section_title'
            LIMIT 1");
        $judulPenelitian = pg_fetch_assoc($qJudulPenelitian)['content_value'] ?? 'Penelitian';

        $qDeskripsiPenelitian = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'penelitian' AND pc.content_key = 'section_description'
            LIMIT 1");
        $deskripsiPenelitian = pg_fetch_assoc($qDeskripsiPenelitian)['content_value'] ?? 'Deskripsi penelitian belum ditambahkan.';

        $qPenelitian = pg_query($conn, "
            SELECT p.id_penelitian, p.judul_penelitian, p.tahun, p.deskripsi, p.media_path,
                   d.nama_dosen
            FROM penelitian p
            LEFT JOIN dosen d ON p.id_author = d.id_dosen
            ORDER BY p.tahun DESC, p.id_penelitian DESC
            LIMIT 3");

        $penelitianData = [];
        while ($row = pg_fetch_assoc($qPenelitian)) {
            $penelitianData[] = $row;
        }

        if (!empty($penelitianData)):
    ?>
            <section class="section-gap">
                <div class="container">
                    <div class="section-header animate-on-scroll">
                        <h2><?= nl2br(htmlspecialchars($judulPenelitian)) ?></h2>
                        <p><?= nl2br(htmlspecialchars($deskripsiPenelitian)) ?></p>
                    </div>

                    <div class="research-grid sm animate-on-scroll">
                        <?php foreach ($penelitianData as $research):
                            $fileUrl = !empty($research['media_path'])
                                ? BASE_URL . '/uploads/penelitian/' . htmlspecialchars($research['media_path'])
                                : null;
                            $authorName = !empty($research['nama_dosen'])
                                ? htmlspecialchars($research['nama_dosen'])
                                : 'Peneliti tidak diketahui';
                        ?>
                            <article class="research-card">
                                <div class="research-badge">
                                    <div class="badge-year"><?= htmlspecialchars($research['tahun']) ?></div>
                                    <div class="badge-author"><?= $authorName ?></div>
                                </div>

                                <h5><?= htmlspecialchars($research['judul_penelitian']) ?></h5>
                                <p class="text-muted">
                                    <?= nl2br(htmlspecialchars(substr($research['deskripsi'] ?? '', 0, 150))) ?>
                                    <?= strlen($research['deskripsi'] ?? '') > 150 ? '...' : '' ?>
                                </p>

                                <?php if ($fileUrl): ?>
                                    <a class="btn btn-brand btn-sm" href="<?= $fileUrl ?>" target="_blank" rel="noopener">Download PDF</a>
                                <?php else: ?>
                                    <span class="no-file">Tidak ada file tersedia</span>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <div class="text-center mt-4 animate-on-scroll">
                        <a href="<?= BASE_URL ?>/user/arsip/penelitian.php" class="btn-brand">View More</a>
                    </div>
                </div>
            </section>
    <?php endif;
    endif; ?>

    <?php
    // ========================================
    // SECTION 7: PENGABDIAN
    // ========================================
    if ($visibility['show_pengabdian'] ?? true):

        $qJudulPengabdian = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'pengabdian' AND pc.content_key = 'section_title'
            LIMIT 1");
        $judulPengabdian = pg_fetch_assoc($qJudulPengabdian)['content_value'] ?? 'Pengabdian';

        $qDeskripsiPengabdian = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'pengabdian' AND pc.content_key = 'section_description'
            LIMIT 1");
        $deskripsiPengabdian = pg_fetch_assoc($qDeskripsiPengabdian)['content_value'] ?? 'Deskripsi pengabdian belum ditambahkan.';

        $qPengabdian = pg_query($conn, "
            SELECT p.tahun, p.judul_pengabdian, p.skema, d.nama_dosen
            FROM pengabdian p
            LEFT JOIN dosen d ON p.id_ketua = d.id_dosen
            ORDER BY p.tahun DESC
            LIMIT 3");

        $pengabdianData = [];
        while ($row = pg_fetch_assoc($qPengabdian)) {
            $pengabdianData[] = $row;
        }

        if (!empty($pengabdianData)):
    ?>
            <section class="section-gap section-alt">
                <div class="container">
                    <div class="section-header animate-on-scroll">
                        <h2><?= nl2br(htmlspecialchars($judulPengabdian)) ?></h2>
                        <p><?= nl2br(htmlspecialchars($deskripsiPengabdian)) ?></p>
                    </div>

                    <div class="table-responsive animate-on-scroll">
                        <table class="lab-table">
                            <thead>
                                <tr>
                                    <th>Tahun</th>
                                    <th>Judul Pengabdian</th>
                                    <th>Ketua</th>
                                    <th>Skema</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pengabdianData as $record): ?>
                                    <tr>
                                        <td>
                                            <span class="year-badge">
                                                <?= htmlspecialchars($record['tahun']) ?>
                                            </span>
                                        </td>
                                        <td><strong><?= htmlspecialchars($record['judul_pengabdian']) ?></strong></td>
                                        <td>
                                            <?= !empty($record['nama_dosen'])
                                                ? htmlspecialchars($record['nama_dosen'])
                                                : '<span class="text-muted">-</span>' ?>
                                        </td>
                                        <td>
                                            <span class="scheme-badge">
                                                <?= htmlspecialchars($record['skema']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-4 animate-on-scroll">
                        <a href="<?= BASE_URL ?>/user/arsip/pengabdian.php" class="btn-brand">View More</a>
                    </div>
                </div>
            </section>
    <?php endif;
    endif; ?>

    <?php
    // ========================================
    // SECTION 8: SARANA & PRASARANA
    // ========================================
    if ($visibility['show_sarana'] ?? true):

        $qJudulSarana = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'sarana' AND pc.content_key = 'section_title'
            LIMIT 1");
        $judulSarana = pg_fetch_assoc($qJudulSarana)['content_value'] ?? 'Sarana & Prasarana';

        $qDeskripsiSarana = pg_query($conn, "
            SELECT pc.content_value 
            FROM page_content pc
            JOIN pages p ON pc.id_page = p.id_page
            WHERE p.nama = 'sarana' AND pc.content_key = 'section_description'
            LIMIT 1");
        $deskripsiSarana = pg_fetch_assoc($qDeskripsiSarana)['content_value'] ?? 'Deskripsi sarana & prasarana belum ditambahkan.';

        $qSarana = pg_query($conn, "
            SELECT * FROM sarana
            ORDER BY nama_sarana ASC
            LIMIT 3");

        $saranaData = [];
        while ($row = pg_fetch_assoc($qSarana)) {
            $saranaData[] = $row;
        }

        if (!empty($saranaData)):
    ?>
            <section class="section-gap">
                <div class="container">
                    <div class="section-header animate-on-scroll">
                        <h2><?= nl2br(htmlspecialchars($judulSarana)) ?></h2>
                        <p><?= nl2br(htmlspecialchars($deskripsiSarana)) ?></p>
                    </div>

                    <div class="card-grid sm animate-on-scroll">
                        <?php foreach ($saranaData as $post):
                            $imagePath = BASE_URL . '/uploads/sarana/' . htmlspecialchars($post['media_path']);
                        ?>
                            <article class="card-basic facility-card">
                                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($post['nama_sarana']) ?>">
                                <h5><?= htmlspecialchars($post['nama_sarana']) ?></h5>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <div class="text-center mt-4 animate-on-scroll">
                        <a href="<?= BASE_URL ?>/user/layanan/sarana_prasarana.php" class="btn-brand">View More</a>
                    </div>
                </div>
            </section>
    <?php endif;
    endif; ?>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>