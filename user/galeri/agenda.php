<?php
define('BASE_URL', '../..');
$pageTitle = 'Galeri - Agenda';
$activePage = 'galeri-agenda';
$pageStyles = ['galeri'];
require_once __DIR__ . '/../../config/koneksi.php';

// Ambil data judul
$qJudulAgenda = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'galeri_agenda' AND pc.content_key = 'section_title'
    LIMIT 1");
$judulAgenda = pg_fetch_assoc($qJudulAgenda)['content_value'] ?? 'AGENDA';

// Ambil data deskripsi
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
    ORDER BY tanggal DESC");

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/page-hero.php';
?>

<main class="section-gap">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2><?= nl2br(htmlspecialchars($judulAgenda)); ?></h2>
            <p><?= nl2br(htmlspecialchars($deskripsiAgenda)); ?></p>
        </div>
        
        <?php 
        $hasData = false;
        $agendaData = [];
        
        while ($row = pg_fetch_assoc($qAgenda)) {
            $hasData = true;
            $agendaData[] = $row;
        }
        ?>
        
        <?php if (!$hasData): ?>
            <p class="text-center text-muted animate-on-scroll">Belum ada agenda yang ditambahkan.</p>
        <?php else: ?>
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
                                <td><?= $index + 1; ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($row['judul']); ?></strong>    
                                </td>
                                <td>
                                    <?php 
                                    $deskripsi = htmlspecialchars($row['deskripsi']);
                                        echo $deskripsi;
                                    ?>
                                </td>
                                <td>
                                    <span class="date-badge">
                                        <?= date('d M Y', strtotime($row['tanggal'])); ?>
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
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>