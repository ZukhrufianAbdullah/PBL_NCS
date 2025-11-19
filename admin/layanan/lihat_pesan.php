<?php 
// File: admin/layanan/lihat_pesan.php
session_start();
$page_title = "Pesan Masuk Konsultatif";
$current_page = "lihat_pesan";
$base_url = '../../'; // Path relatif naik dua tingkat ke folder admin/

// Data dummy untuk pesan konsultatif (Tabel: konsultatif)
$dummy_pesan = [
    ['id' => 1, 'nama_pengirim' => 'Esatovin', 'isi_pesan' => 'Saya tertarik konsultasi tentang kriptografi kuantum.', 'tanggal_kirim' => '2025-11-15 10:30'],
    ['id' => 2, 'nama_pengirim' => 'Muhammad Nuril', 'isi_pesan' => 'Perlu bantuan setting VPN untuk kantor.', 'tanggal_kirim' => '2025-11-14 14:00'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>style_admin.css">
    <script src="<?php echo $base_url; ?>script_admin.js"></script>
    <style>
        .pesan-table th, .pesan-table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .pesan-table thead tr { background-color: #f7f7f7; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>ADMIN NCS LAB</h2>
        <a href="../index.php">Dashboard</a>
        <a href="edit_sarana_prasarana.php">Sarana & Prasarana</a>
        <a href="lihat_pesan.php" class="<?php echo $current_page == 'lihat_pesan' ? 'active' : ''; ?>">Pesan Konsultatif</a>
        <a href="../logout.php">Logout</a>
    </div>

    <div class="content">
        <div class="admin-header">
            <h1><?php echo $page_title; ?> (Tabel: konsultatif)</h1>
        </div>

        <p>Daftar pesan masuk dari pengunjung yang ingin berkonsultasi (Services/konsultatif).</p>

        <table class="pesan-table">
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
                    <td><?php echo htmlspecialchars(substr($pesan['isi_pesan'], 0, 50)) . '...'; ?></td>
                    <td>
                        <button class="btn-primary" style="background-color: orange; padding: 5px 10px;">Lihat Detail</button>
                        <a href="hapus_pesan.php?id=<?php echo $pesan['id']; ?>" onclick="return confirm('Yakin hapus pesan ini?')" class="btn-primary" style="background-color: red; padding: 5px 10px; text-decoration: none;">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <p style="margin-top: 20px;">*Untuk melihat pesan lengkap, klik tombol "Lihat Detail".</p>
    </div>
</body>
</html>