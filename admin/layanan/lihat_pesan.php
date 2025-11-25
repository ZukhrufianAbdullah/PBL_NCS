<?php
// File: admin/layanan/lihat_pesan.php
session_start();
$pageTitle = 'Pesan Masuk Konsultatif';
$currentPage = 'lihat_pesan';
$adminPageStyles = ['tables'];

// Data dummy untuk pesan konsultatif (Tabel: konsultatif)
$dummy_pesan = [
    ['id' => 1, 'nama_pengirim' => 'Esatovin', 'isi_pesan' => 'Saya tertarik konsultasi tentang kriptografi kuantum.', 'tanggal_kirim' => '2025-11-15 10:30'],
    ['id' => 2, 'nama_pengirim' => 'Muhammad Nuril', 'isi_pesan' => 'Perlu bantuan setting VPN untuk kantor.', 'tanggal_kirim' => '2025-11-14 14:00'],
];

require_once dirname(__DIR__) . '/includes/admin_header.php';
?>
<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: konsultatif)</h1>
    <p>Daftar pesan masuk dari pengunjung yang mengajukan konsultasi.</p>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Waktu Kirim</th>
                <th>Nama Pengirim</th>
                <th>Isi Pesan Singkat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dummy_pesan as $pesan): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pesan['tanggal_kirim']); ?></td>
                    <td><?php echo htmlspecialchars($pesan['nama_pengirim']); ?></td>
                    <td><?php echo htmlspecialchars(substr($pesan['isi_pesan'], 0, 80)) . '...'; ?></td>
                    <td>
                        <button type="button" class="btn-warning">Lihat Detail</button>
                        <a href="<?php echo $adminBasePath; ?>layanan/hapus_pesan.php?id=<?php echo $pesan['id']; ?>"
                           onclick="return confirm('Yakin hapus pesan ini?');"
                           class="btn-danger">
                            Hapus
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p class="mt-20 text-gray">*Klik "Lihat Detail" untuk membaca pesan secara lengkap.</p>
</div>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>