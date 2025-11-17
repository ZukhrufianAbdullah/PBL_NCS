<?php
include '../../config/koneksi.php';

// Pastikan request dari form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama_pengirim = $_POST['nama_pengirim'];
    $isi_pesan     = $_POST['isi_pesan'];

    // Validasi sederhana
    if (empty($nama_pengirim) || empty($isi_pesan)) {
        echo "<script>
                alert('Nama dan pesan tidak boleh kosong!');
                window.location.href='../konsultatif.php';
              </script>";
        exit();
    }

    // Query INSERT ke tabel konsultatif
    $query = "
        INSERT INTO konsultatif (nama_pengirim, isi_pesan, tanggal_kirim)
        VALUES ($1, $2, NOW())
    ";

    $result = pg_query_params($conn, $query, array($nama_pengirim, $isi_pesan));

    if ($result) {
        echo "<script>
                alert('Pesan berhasil dikirim!');
                window.location.href='../konsultatif.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal mengirim pesan!');
                window.location.href='../konsultatif.php';
              </script>";
    }

    exit();
}

// Jika akses langsung tanpa POST
echo "<script>
        alert('Akses tidak valid!');
        window.location.href='../konsultatif.php';
      </script>";
exit();
?>
