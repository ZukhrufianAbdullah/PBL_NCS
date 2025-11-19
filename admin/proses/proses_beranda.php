<?php
session_start();
include '../../config/koneksi.php';

// Ambil id_user dari session (fallback 1 jika belum login)
$id_user = $_SESSION['id_user'] ?? 1;

// Pastikan tombol submit ditekan
if (isset($_POST['submit'])) {

    // Ambil input dari form
    $deskripsi = $_POST['deskripsi'];

    // Query UPDATE HANYA untuk page_key = 'home'
    // Judul tidak diupdate sesuai permintaan
    $query = "UPDATE page_content 
              SET deskripsi = $1, id_user = $2
              WHERE page_key = 'home'";

    $params = array($deskripsi, $id_user);

    // Eksekusi query
    $result = pg_query_params($conn, $query, $params);

    // Cek hasil eksekusi
    if ($result) {
        echo "<script>
                alert('Deskripsi Beranda berhasil diperbarui!');
                window.location.href = '../beranda/edit_beranda.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Gagal memperbarui deskripsi Beranda!');
                window.location.href = '../beranda/edit_beranda.php';
              </script>";
        exit();
    }

} else {
    // Jika file diakses tanpa submit
    echo "<script>
            alert('Akses tidak valid!');
            window.location.href = '../beranda/edit_beranda.php';
          </script>";
    exit();
}
?>
