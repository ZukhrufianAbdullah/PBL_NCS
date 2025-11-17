<?php
session_start();
include '../../config/koneksi.php';

// Ambil id_user dari session (fallback ke 1)
$id_user = $_SESSION['id_user'] ?? 1;

// Cek apakah tombol submit ditekan
if (isset($_POST['submit'])) {

    // Ambil data dari form
    $deskripsi = $_POST['deskripsi'];

    // Query UPDATE hanya deskripsi, karena judul sudah dihapus
    $query = "UPDATE home 
              SET deskripsi = $1, id_user = $2
              WHERE id_home = 1";

    $params = array($deskripsi, $id_user);

    // Eksekusi query
    $result = pg_query_params($conn, $query, $params);

    // Cek hasil
    if ($result) {
        echo "<script>
                alert('Deskripsi beranda berhasil diperbarui!');
                window.location.href = '../beranda/edit_beranda.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Gagal memperbarui deskripsi beranda!');
                window.location.href = '../beranda/edit_beranda.php';
              </script>";
        exit();
    }

} else {
    // Jika bukan melalui tombol submit
    echo "<script>
            alert('Akses tidak valid!');
            window.location.href = '../beranda/edit_beranda.php';
          </script>";
    exit();
}
?>
