<?php
define('BASE_URL', '../..');
$pageTitle = 'Galeri - Agenda';
$activePage = 'galeri-agenda';
$pageStyles = ['galeri'];
require_once __DIR__ . '/../../config/koneksi.php';

//Ambil data judul
$qJudulAgenda = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'galeri_agenda' AND pc.content_key = 'section_title'
    LIMIT 1");
$judulAgenda = pg_fetch_assoc($qJudulAgenda)['content_value'] ?? 'AGENDA';

//Ambil data deskripsi
$qDeskripsiAgenda = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'galeri_agenda' AND pc.content_key = 'section_description'
    LIMIT 1");
$deskripsiAgenda = pg_fetch_assoc($qDeskripsiAgenda)['content_value'] ?? 'Deskripsi agenda belum ditambahkan.';

// Ambil data agenda
$qAgenda = pg_query($conn, "
    SELECT * 
    FROM agenda
    ORDER BY tanggal ASC");


require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';


?>

<main class="section-gap">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2><?= nl2br($judulAgenda); ?></h2>
            <p><?= nl2br($deskripsiAgenda); ?></p>

            <div class="card" style="margin-top:30px;">
                <h3 style="margin-bottom:15px;">Daftar Agenda</h3>

                <table class="data-table">
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
                        <?php
                        $no = 1;
                        $hasData = false;

                        while ($row = pg_fetch_assoc($qAgenda)):
                            $hasData = true;
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($row['judul']); ?></td>
                                <td><?= nl2br(htmlspecialchars($row['deskripsi'])); ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                <td>
                                    <?= ($row['status'] === 't')
                                        ? '<span class="badge badge-success">Aktif</span>'
                                        : '<span class="badge badge-danger">Arsip</span>'; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                        <?php if (!$hasData): ?>
                            <tr>
                                <td colspan="5" style="text-align:center; padding:15px; color:#777;">
                                    <strong>Belum ada agenda yang ditambahkan</strong>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</main>


<?php require_once __DIR__ . '/../../includes/footer.php'; ?>