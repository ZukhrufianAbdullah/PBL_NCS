<?php
session_start();
include '../../config/koneksi.php';

// Ambil id_user dari session (fallback 1 jika belum login)
$id_user = $_SESSION['id_user'] ?? 1;

// Pastikan tombol submit ditekan
if (isset($_POST['submit'])) {

    // Ambil input dari form
    $deskripsi = trim($_POST['deskripsi']);

    // 1. Ambil id_page untuk halaman 'home'
    $sqlPage = "SELECT id_page FROM pages WHERE nama = 'home' LIMIT 1";
    $resultPage = pg_query($conn, $sqlPage);

    if (!$resultPage || pg_num_rows($resultPage) === 0) {
        echo "<script>
                alert('Halaman home tidak ditemukan di tabel pages!');
                window.location.href = '../beranda/edit_beranda.php';
              </script>";
        exit();
    }

    $page = pg_fetch_assoc($resultPage);
    $id_page = $page['id_page'];

    // 2. UPDATE atau INSERT konten deskripsi untuk home
    // Periksa apakah content sudah ada
    $check = "SELECT id_page_content FROM page_content 
              WHERE id_page = $1 AND content_key = 'deskripsi' LIMIT 1";
    $checkResult = pg_query_params($conn, $check, array($id_page));

    if (pg_num_rows($checkResult) > 0) {
        // Data sudah ada → UPDATE
        $query = "UPDATE page_content 
                  SET content_value = $1, id_user = $2
                  WHERE id_page = $3 AND content_key = 'deskripsi'";
        $params = array($deskripsi, $id_user, $id_page);
        $result = pg_query_params($conn, $query, $params);

    } else {
        // Data belum ada → INSERT
        $query = "INSERT INTO page_content (id_page, content_key, content_type, content_value, id_user)
                  VALUES ($1, 'deskripsi', 'text', $2, $3)";
        $params = array($id_page, $deskripsi, $id_user);
        $result = pg_query_params($conn, $query, $params);
    }

    // 3. Cek hasil
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
