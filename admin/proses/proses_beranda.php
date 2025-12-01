<?php
session_start();
include '../../config/koneksi.php';
// Include helper
include __DIR__ . "/../../app/helpers/page_helper.php";

// Ambil id_user dari session (fallback 1)
$id_user = $_SESSION['id_user'] ?? 1;

// Pastikan tombol submit ditekan
if (isset($_POST['submit'])) {
    // Ambil input dari form
    $deskripsi = ($_POST['deskripsi']);

    // GUNAKAN HELPER FUNCTION untuk mendapatkan/membuat halaman
    $id_page = ensure_page_exists($conn, 'home');

    if (!$id_page) {
        echo "<script>
                alert('Gagal membuat atau mendapatkan halaman Home!');
                window.location.href = '../beranda/edit_beranda.php';
              </script>";
        exit();
    }

    // Gunakan helper untuk upsert content
    // Untuk beranda, kita tetap gunakan content_key 'deskripsi' karena ini bukan section
    $result = upsert_page_content($conn, $id_page, 'deskripsi', $deskripsi, $id_user);

    // Cek hasil
    if ($result) {
        echo "<script>
                alert('Deskripsi Beranda berhasil diperbarui!');
                window.location.href = '../beranda/edit_beranda.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui deskripsi Beranda!');
                window.location.href = '../beranda/edit_beranda.php';
              </script>";
    }
    exit();
} else {
    // Jika file diakses tanpa submit
    echo "<script>
            alert('Akses tidak valid!');
            window.location.href = '../beranda/edit_beranda.php';
          </script>";
    exit();
}
?>